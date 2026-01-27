<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    protected function generateValidSignature(string $body): string
    {
        $timestamp = now()->timestamp;
        $signature = hash_hmac(
            'sha256',
            "$timestamp.$body",
            $this->webhookSecret
        );
        
        return $signature;
    }

    /**
     * TEST 7: Webhook con firma válida procesa pago
     */
    public function test_webhook_with_valid_signature_processes_payment()
    {
        $payload = [
            'action' => 'payment.created',
            'data' => [
                'id' => 'MP-' . $this->transaction->mercado_pago_id,
                'amount' => 99.99,
                'status' => 'approved',
            ]
        ];

        $body = json_encode($payload);
        $signature = $this->generateValidSignature($body);
        $timestamp = now()->timestamp;

        $response = $this->post('/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => $timestamp,
        ]);

        // Verificar respuesta exitosa
        $response->assertSuccessful();

        // Verificar que Transaction fue actualizada
        $this->assertDatabaseHas('transactions', [
            'id' => $this->transaction->id,
            'status' => 'approved',
        ]);

        // Verificar que Subscription fue creada
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $this->user->id,
            'status' => 'active',
        ]);
    }

    /**
     * TEST 8: Webhook con firma INVÁLIDA es rechazado
     */
    public function test_webhook_with_invalid_signature_is_rejected()
    {
        $payload = [
            'action' => 'payment.created',
            'data' => ['id' => 'MP-123', 'status' => 'approved']
        ];

        $response = $this->post('/webhook/mercado-pago', $payload, [
            'X-Signature' => 'invalid_signature',
            'X-Request-ID' => now()->timestamp,
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
        $payload = [
            'action' => 'payment.created',
            'data' => ['id' => 'MP-123', 'status' => 'approved']
        ];

        $body = json_encode($payload);
        $signature = $this->generateValidSignature($body);

        // Primer webhook
        $this->post('/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => now()->timestamp,
        ])->assertSuccessful();

        // Segundo webhook idéntico (duplicado)
        $this->post('/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => now()->timestamp,
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
        $payload = [
            'action' => 'payment.created',
            'data' => [
                'id' => 'MP-123',
                'amount' => 50.00, // Diferente al esperado (99.99)
                'status' => 'approved',
            ]
        ];

        $body = json_encode($payload);
        $signature = $this->generateValidSignature($body);

        $response = $this->post('/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => now()->timestamp,
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
        $payload = [
            'action' => 'payment.updated',
            'data' => [
                'id' => 'MP-123',
                'status' => 'rejected',
                'failure_reason' => 'insufficient_funds',
            ]
        ];

        $body = json_encode($payload);
        $signature = $this->generateValidSignature($body);

        $response = $this->post('/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => now()->timestamp,
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
            'data' => ['id' => 'MP-123', 'status' => 'approved']
        ];

        // Sin X-Signature header
        $response = $this->post('/webhook/mercado-pago', $payload);

        $response->assertUnauthorized();
    }

    /**
     * TEST 13: Webhook retorna 200 OK en éxito
     */
    public function test_webhook_returns_200_on_success()
    {
        $payload = [
            'action' => 'payment.created',
            'data' => ['id' => 'MP-123', 'status' => 'approved']
        ];

        $body = json_encode($payload);
        $signature = $this->generateValidSignature($body);

        $response = $this->post('/webhook/mercado-pago', $payload, [
            'X-Signature' => $signature,
            'X-Request-ID' => now()->timestamp,
        ]);

        $response->assertStatus(200);
    }
}
