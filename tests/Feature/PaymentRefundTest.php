<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentRefundTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Transaction $transaction;
    protected Subscription $subscription;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        $this->transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'approved',
            'amount' => 99.99,
            'mercado_pago_id' => 'MP-123456',
        ]);

        $this->subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'active',
            'transaction_id' => $this->transaction->id,
        ]);
    }

    /**
     * TEST 14: Usuario puede solicitar reembolso dentro de 7 días
     */
    public function test_user_can_request_refund_within_7_days()
    {
        // Transacción hace 5 días
        $this->transaction->update([
            'created_at' => now()->subDays(5)
        ]);

        $response = $this->actingAs($this->user)
            ->post('/payment/refund', [
                'transaction_id' => $this->transaction->id,
                'reason' => 'Not satisfied with service',
            ]);

        $response->assertSuccessful();

        // Verificar que Refund fue creado
        $this->assertDatabaseHas('refunds', [
            'transaction_id' => $this->transaction->id,
            'status' => 'requested',
        ]);
    }

    /**
     * TEST 15: No se permite reembolso después de 7 días
     */
    public function test_refund_not_allowed_after_7_days()
    {
        // Transacción hace 8 días
        $this->transaction->update([
            'created_at' => now()->subDays(8)
        ]);

        $response = $this->actingAs($this->user)
            ->post('/payment/refund', [
                'transaction_id' => $this->transaction->id,
            ]);

        $response->assertForbidden();
        
        $this->assertDatabaseMissing('refunds', [
            'transaction_id' => $this->transaction->id,
        ]);
    }

    /**
     * TEST 16: Reembolso exitoso cancela suscripción
     */
    public function test_successful_refund_cancels_subscription()
    {
        $response = $this->actingAs($this->user)
            ->post('/payment/refund', [
                'transaction_id' => $this->transaction->id,
                'reason' => 'Request refund',
            ]);

        $response->assertSuccessful();

        // Verificar que suscripción fue cancelada
        $this->assertDatabaseHas('subscriptions', [
            'id' => $this->subscription->id,
            'status' => 'cancelled',
        ]);
    }

    /**
     * TEST 17: No se puede reembolsar dos veces la misma transacción
     */
    public function test_cannot_refund_same_transaction_twice()
    {
        // Primer reembolso
        $this->actingAs($this->user)
            ->post('/payment/refund', [
                'transaction_id' => $this->transaction->id,
            ]);

        // Segundo reembolso
        $response = $this->actingAs($this->user)
            ->post('/payment/refund', [
                'transaction_id' => $this->transaction->id,
            ]);

        $response->assertForbidden();
        
        // Solo debe haber 1 refund
        $this->assertEquals(
            1,
            \App\Models\Refund::where('transaction_id', $this->transaction->id)->count()
        );
    }
}
