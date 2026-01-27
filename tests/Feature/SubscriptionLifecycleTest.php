<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->plan = Plan::factory()->create([
            'duration_days' => 30,
        ]);
    }

    /**
     * TEST 18: Suscripción expira después de 30 días
     */
    public function test_subscription_expires_after_duration()
    {
        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'status' => 'active',
            'expires_at' => now()->subDays(1), // Expiró ayer
        ]);

        // Ejecutar cron job de expiración
        $this->artisan('subscriptions:expire');

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => 'expired',
        ]);
    }

    /**
     * TEST 19: Usuario puede renovar suscripción expirada
     */
    public function test_user_can_renew_expired_subscription()
    {
        $expiredSubscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'expired',
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($this->user)
            ->post('/payment/checkout', [
                'plan_id' => $this->plan->id,
            ]);

        $response->assertRedirect();

        // Nueva suscripción con status active debe existir
        $this->assertTrue(
            Subscription::where('user_id', $this->user->id)
                ->where('status', 'active')
                ->exists()
        );
    }

    /**
     * TEST 20: Suscripción activa tiene acceso a features premium
     */
    public function test_active_subscription_grants_premium_access()
    {
        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'active',
            'expires_at' => now()->addMonth(),
        ]);

        // Verificar que usuario tiene suscripción activa
        $this->assertTrue(
            $this->user->hasActiveSubscription()
        );
    }

    /**
     * TEST 21: Usuario sin suscripción activa NO tiene acceso premium
     */
    public function test_user_without_active_subscription_no_premium_access()
    {
        $this->assertFalse(
            $this->user->hasActiveSubscription()
        );
    }
}
