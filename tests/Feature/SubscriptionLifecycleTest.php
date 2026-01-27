<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubscriptionLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected SubscriptionPlan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->plan = SubscriptionPlan::factory()->create([
            'frequency' => 1,
            'frequency_type' => 'months',
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
            'ends_at' => now()->subDays(1), // Expiró ayer
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
            'ends_at' => now()->subDay(),
        ]);

        Http::fake([
            'api.mercadopago.com/checkout/preferences' => Http::response([
                'id' => 'pref_123',
                'init_point' => 'http://mp.test/init',
                'sandbox_init_point' => 'http://mp.test/sandbox',
            ], 201),
        ]);

        $response = $this->actingAs($this->user)
            ->post("/subscription/checkout/{$this->plan->id}");

        $response->assertSuccessful();
        $response->assertJson([
            'success' => true,
            'preference_id' => 'pref_123',
        ]);
    }

    /**
     * TEST 20: Suscripción activa tiene acceso a features premium
     */
    public function test_active_subscription_grants_premium_access()
    {
        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'active',
            'ends_at' => now()->addMonth(),
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
