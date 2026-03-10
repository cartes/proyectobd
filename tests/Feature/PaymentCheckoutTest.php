<?php

namespace Tests\Feature;

use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected SubscriptionPlan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['user_type' => 'sugar_daddy']);
        $this->plan = SubscriptionPlan::factory()->create([
            'name' => 'Premium',
            'amount' => 99.99,
            'currency' => 'ARS',
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
        // Mock MercadoPagoService
        $this->mock(MercadoPagoService::class, function ($mock) {
            $mock->shouldReceive('createPaymentPreference')
                ->once()
                ->andReturn([
                    'success' => true,
                    'preference_id' => 'pref_123',
                    'init_point' => 'http://mp.test/init',
                    'sandbox_init_point' => 'http://mp.test/sandbox',
                    'external_reference' => 'ref_123',
                ]);
        });

        $response = $this->actingAs($this->user)
            ->post('/api/v1/checkout', [
                'product_type' => 'boost',
                'amount' => 99.99,
            ]);

        // Verificar que se creó Transaction en estado pending
        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'status' => 'pending',
            'amount' => 99.99,
            'currency' => 'ARS',
        ]);

        // Verificar respuesta exitosa con preference_id
        $response->assertSuccessful();
        $response->assertJsonStructure(['success', 'preference_id', 'init_point']);
    }

    /**
     * TEST 2: Usuario no autenticado NO puede acceder a checkout
     */
    public function test_unauthenticated_user_cannot_checkout()
    {
        $response = $this->post('/api/v1/checkout', [
            'product_type' => 'boost',
            'amount' => 99.99,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('transactions', [
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * TEST 3: Plan inexistente retorna error
     */
    public function test_invalid_parameters_returns_error()
    {
        $response = $this->actingAs($this->user)
            ->post('/api/v1/checkout', [
                'product_type' => '',
                'amount' => -10,
            ]);

        $response->assertStatus(302); // Redirect back due to validation error (default behavior)
        $this->assertDatabaseMissing('transactions', [
            'user_id' => $this->user->id,
        ]);
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
            'ends_at' => now()->addMonth(),
        ]);

        $response = $this->actingAs($this->user)
            ->post('/api/v1/checkout', [
                'product_type' => 'subscription',
                'amount' => 99.99,
            ]);

        $response->assertStatus(403);
    }
}
