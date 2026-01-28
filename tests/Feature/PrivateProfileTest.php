<?php

namespace Tests\Feature;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected SubscriptionPlan $privatePlan;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the private profile plan with the correct slug and features
        $this->privatePlan = SubscriptionPlan::factory()->create([
            'name' => 'Perfil Privado',
            'slug' => 'private-profile',
            'amount' => 5000,
            'currency' => 'CLP',
            'features' => ['private_profiles'],
            'is_active' => true,
        ]);

        $this->user = User::factory()->create(['user_type' => 'sugar_daddy']);
    }

    /**
     * Test user without plan sees the CTA button
     */
    public function test_user_without_plan_sees_cta_to_activate_private_profile()
    {
        $response = $this->actingAs($this->user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Perfil Privado');
        $response->assertSee('Activar Privacidad');
        $response->assertSee('$5.000');
    }

    /**
     * Test user with plan sees the toggle switch
     */
    public function test_user_with_plan_sees_the_private_profile_toggle()
    {
        // Give the user an active private profile subscription
        $this->user->subscriptions()->create([
            'plan_id' => $this->privatePlan->id,
            'status' => 'active',
            'ends_at' => now()->addMonth(),
        ]);

        $response = $this->actingAs($this->user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Perfil Privado');
        $response->assertSee('modo privado');
        $response->assertDontSee('Comprar Perfil Privado'); // Verification of correct spelling in view or absence of CTA
    }

    /**
     * Test clicking the CTA button initiates checkout
     */
    public function test_clicking_activate_button_initiates_checkout()
    {
        // Mock MercadoPagoService
        $this->mock(MercadoPagoService::class, function ($mock) {
            $mock->shouldReceive('createSubscriptionPreference')
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
            ->post("/subscription/checkout/{$this->privatePlan->id}");

        $response->assertSuccessful();
        $response->assertJson([
            'success' => true,
            'preference_id' => 'pref_123',
        ]);
    }

    /**
     * Test that feature allows updating is_private
     */
    public function test_user_with_feature_can_toggle_privacy()
    {
        // Give the user an active private profile subscription
        $this->user->subscriptions()->create([
            'plan_id' => $this->privatePlan->id,
            'status' => 'active',
            'ends_at' => now()->addMonth(),
        ]);

        // Mock profile update
        $response = $this->actingAs($this->user)
            ->put(route('profile.update'), [
                'is_private' => 1,
                'city' => 'Test City', // Required field maybe
            ]);

        $response->assertRedirect();

        $this->user->load('profileDetail');
        $this->assertEquals(1, $this->user->profileDetail->is_private);
    }

    /**
     * Test that user without feature cannot toggle privacy via request
     */
    public function test_user_without_feature_cannot_toggle_privacy()
    {
        // User starts with is_private = 0 (default)
        if (! $this->user->profileDetail) {
            $this->user->profileDetail()->create(['is_private' => 0]);
        }

        $response = $this->actingAs($this->user)
            ->put(route('profile.update'), [
                'is_private' => 1,
            ]);

        $this->user->load('profileDetail');
        // It should still be 0 because UpdateProfileRequest->prepareForValidation() merges it to false if no feature
        $this->assertEquals(0, $this->user->profileDetail->is_private);
    }
}
