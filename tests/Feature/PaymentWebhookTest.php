<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Transaction $transaction;

    protected string $webhookSecret;

    protected function setUp(): void
    {
        parent::setUp();

        $this->webhookSecret = config('services.mercadopago.webhook_secret');

        $this->user = User::factory()->create();
        $this->transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'amount' => 99.99,
        ]);
    }

    /**
     * Generar firma válida para webhook
     */
    protected function generateValidSignature(string $body, string $timestamp, string $resourceId): string
    {
        $manifest = "id:{$resourceId};ts:{$timestamp};";
        $v1 = hash_hmac('sha256', $manifest, $this->webhookSecret);

        return "ts={$timestamp},v1={$v1}";
    }

    /**
     * TEST 7: Webhook con firma válida procesa pago
     */
    public function test_webhook_with_valid_signature_processes_payment()
    {
        $timestamp = (string) now()->timestamp;
        $paymentId = $this->transaction->mp_payment_id;

        $payload = [
            'type' => 'payment',
            'data' => [
                'id' => $paymentId,
            ],
        ];

        Http::fake([
            'api.mercadopago.com/v1/payments/*' => Http::response([
                'id' => $paymentId,
                'status' => 'approved',
                'transaction_amount' => 99.99,
                'currency_id' => 'ARS',
                'external_reference' => $this->transaction->external_reference,
                'metadata' => [
                    'user_id' => $this->user->id,
                    'product_type' => 'boost',
                ],
            ], 200),
        ]);

        $signature = $this->generateValidSignature(json_encode($payload), $timestamp, $paymentId);

        $response = $this->post('/api/v1/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => $timestamp,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('transactions', [
            'id' => $this->transaction->id,
            'status' => 'approved',
        ]);
    }

    /**
     * TEST 8: Webhook con firma INVÁLIDA es rechazado
     */
    public function test_webhook_with_invalid_signature_is_rejected()
    {
        $paymentId = $this->transaction->mp_payment_id;
        $payload = [
            'type' => 'payment',
            'data' => ['id' => $paymentId, 'status' => 'approved'],
        ];

        $timestamp = (string) now()->timestamp;
        $signature = $this->generateValidSignature(json_encode($payload), $timestamp, $paymentId);

        $response = $this->post('/api/v1/webhook/mercado-pago', $payload, [
            'X-Signature' => 'invalid_signature',
            'X-Request-ID' => $timestamp,
        ]);

        $response->assertUnauthorized();

        // Verificar que NO se actualizó la transacción
        $this->assertDatabaseHas('transactions', [
            'id' => $this->transaction->id,
            'status' => 'pending', // Debe seguir pending
        ]);
    }

    /**
     * TEST 9: Webhook duplicado NO crea Subscription duplicada
     */
    public function test_duplicate_webhook_does_not_create_duplicate_subscription()
    {
        $preapprovalId = 'PA-123';
        $payload = [
            'type' => 'subscription_preapproval',
            'data' => ['id' => $preapprovalId],
        ];

        $timestamp = (string) now()->timestamp;
        $signature = $this->generateValidSignature(json_encode($payload), $timestamp, $preapprovalId);

        // Create initial subscription
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'mp_preapproval_id' => $preapprovalId,
            'status' => 'pending',
        ]);

        Http::fake([
            'api.mercadopago.com/preapproval/*' => Http::response([
                'status' => 'authorized',
                'external_reference' => 'ref_123',
            ], 200),
        ]);

        // First call
        $this->post('/api/v1/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => $timestamp,
        ])->assertSuccessful();

        // Second call
        $this->post('/api/v1/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => $timestamp,
        ])->assertSuccessful();

        // Verificar que solo existe 1 Subscription
        $this->assertEquals(
            1,
            Subscription::where('user_id', $this->user->id)->count()
        );
    }

    /**
     * TEST 10: Webhook con monto incorrecto es rechazado
     */
    public function test_webhook_with_mismatched_amount_is_rejected()
    {
        $paymentId = $this->transaction->mp_payment_id;
        $payload = [
            'type' => 'payment',
            'data' => [
                'id' => $paymentId,
                'amount' => 50.00,
                'status' => 'approved',
            ],
        ];

        $timestamp = (string) now()->timestamp;
        $signature = $this->generateValidSignature(json_encode($payload), $timestamp, $paymentId);

        Http::fake([
            'api.mercadopago.com/v1/payments/*' => Http::response([
                'id' => $paymentId,
                'status' => 'approved',
                'transaction_amount' => 50.00,
                'external_reference' => $this->transaction->external_reference,
                'metadata' => ['user_id' => $this->user->id],
            ], 200),
        ]);

        $response = $this->post('/api/v1/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => $timestamp,
        ]);

        $response->assertStatus(400); // Bad Request

        // Transacción debe seguir en pending
        $this->assertDatabaseHas('transactions', [
            'id' => $this->transaction->id,
            'status' => 'pending',
        ]);
    }

    /**
     * TEST 11: Pago rechazado actualiza estado en BD
     */
    public function test_rejected_payment_updates_transaction_status()
    {
        $paymentId = $this->transaction->mp_payment_id;
        $payload = [
            'type' => 'payment',
            'data' => [
                'id' => $paymentId,
                'status' => 'rejected',
            ],
        ];

        $timestamp = (string) now()->timestamp;
        $signature = $this->generateValidSignature(json_encode($payload), $timestamp, $paymentId);

        Http::fake([
            'api.mercadopago.com/v1/payments/*' => Http::response([
                'id' => $paymentId,
                'status' => 'rejected',
                'transaction_amount' => 99.99,
                'external_reference' => $this->transaction->external_reference,
                'metadata' => ['user_id' => $this->user->id],
            ], 200),
        ]);

        $response = $this->post('/api/v1/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => $timestamp,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('transactions', [
            'id' => $this->transaction->id,
            'status' => 'rejected',
        ]);

        // NO debe haber Subscription creada
        $this->assertDatabaseMissing('subscriptions', [
            'user_id' => $this->user->id,
            'status' => 'active',
        ]);
    }

    /**
     * TEST 12: Webhook sin headers requeridos es rechazado
     */
    public function test_webhook_without_required_headers_is_rejected()
    {
        $payload = [
            'action' => 'payment.created',
            'data' => ['id' => 'MP-123', 'status' => 'approved'],
        ];

        // Sin X-Signature header
        $response = $this->post('/api/v1/webhook/mercado-pago', $payload);

        $response->assertUnauthorized();
    }

    /**
     * TEST 13: Webhook retorna 200 OK en éxito
     */
    public function test_webhook_returns_200_on_success()
    {
        $paymentId = $this->transaction->mp_payment_id;
        $payload = [
            'type' => 'payment',
            'data' => ['id' => $paymentId, 'status' => 'approved'],
        ];

        $timestamp = (string) now()->timestamp;
        $signature = $this->generateValidSignature(json_encode($payload), $timestamp, $paymentId);

        Http::fake([
            'api.mercadopago.com/v1/payments/*' => Http::response([
                'id' => $paymentId,
                'status' => 'approved',
                'transaction_amount' => 99.99,
            ], 200),
        ]);

        $response = $this->post('/api/v1/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => $timestamp,
        ]);

        $response->assertStatus(200);
    }
}
