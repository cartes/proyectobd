<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['user_type' => 'sugar_daddy']);
        $this->plan = Plan::factory()->create([
            'name' => 'Premium',
            'price' => 99.99,
            'currency' => 'ARS'
        ]);
    }

    /**
     * TEST 1: Usuario logueado puede iniciar checkout
     * 
     * Verifica:
     * - Autenticación requerida
     * - Datos del plan válidos
     * - Transacción creada en BD
     * - Redireccionamiento a Mercado Pago
     */
    public function test_authenticated_user_can_initiate_checkout()
    {
        $response = $this->actingAs($this->user)
            ->post('/payment/checkout', [
                'plan_id' => $this->plan->id,
            ]);

        // Verificar que se creó Transaction en estado pending
        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'status' => 'pending',
            'amount' => 99.99,
            'currency' => 'ARS'
        ]);

        // Verificar redireccionamiento a Mercado Pago
        $response->assertRedirect();
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringContainsString('mercadopago.com', $redirectUrl);
    }

    /**
     * TEST 2: Usuario no autenticado NO puede acceder a checkout
     */
    public function test_unauthenticated_user_cannot_checkout()
    {
        $response = $this->post('/payment/checkout', [
            'plan_id' => $this->plan->id,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('transactions', [
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * TEST 3: Plan inexistente retorna error
     */
    public function test_invalid_plan_returns_error()
    {
        $response = $this->actingAs($this->user)
            ->post('/payment/checkout', [
                'plan_id' => 9999,
            ]);

        $response->assertNotFound();
        $this->assertDatabaseMissing('transactions', [
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * TEST 4: Validación de monto negativo
     */
    public function test_negative_plan_price_is_rejected()
    {
        $invalidPlan = Plan::factory()->create([
            'price' => -50.00,
        ]);

        $response = $this->actingAs($this->user)
            ->post('/payment/checkout', [
                'plan_id' => $invalidPlan->id,
            ]);

        $response->assertUnprocessable();
    }

    /**
     * TEST 5: Usuario no puede hacer checkout si ya tiene suscripción activa
     */
    public function test_user_with_active_subscription_cannot_checkout()
    {
        // Crear suscripción activa
        $this->user->subscriptions()->create([
            'plan_id' => $this->plan->id,
            'status' => 'active',
            'expires_at' => now()->addMonth(),
        ]);

        $response = $this->actingAs($this->user)
            ->post('/payment/checkout', [
                'plan_id' => $this->plan->id,
            ]);

        $response->assertForbidden();
    }

    /**
     * TEST 6: Rate limiting en endpoint de checkout
     * 
     * Máximo 5 intentos por minuto
     */
    public function test_checkout_is_rate_limited()
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->actingAs($this->user)
                ->post('/payment/checkout', [
                    'plan_id' => $this->plan->id,
                ]);

            if ($i < 5) {
                $response->assertSuccessful();
            } else {
                // El 6to intento debe ser rechazado
                $response->assertStatus(429); // Too Many Requests
            }
        }
    }
}
