<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Log;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected MockInterface $mpService;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure whitelist is empty so tests trigger rate limits
        config(['app.rate_limit_whitelist' => []]);

        $this->user = User::factory()->create();
        $this->mpService = $this->mock(MercadoPagoService::class);
    }

    #[Test]
    public function payment_checkout_route_is_rate_limited()
    {
        $limit = 5;

        $this->mpService->shouldReceive('createPaymentPreference')
            ->times($limit)
            ->andReturnUsing(function () {
                return [
                    'success' => true,
                    'preference_id' => 'pre_' . uniqid(),
                    'init_point' => 'http://mp.com',
                    'sandbox_init_point' => 'http://sandbox.mp.com',
                    'external_reference' => 'ref_' . uniqid(),
                ];
            });

        for ($i = 0; $i < $limit; $i++) {
            $response = $this->actingAs($this->user)
                ->postJson('/api/v1/checkout', [
                    'product_type' => 'boost',
                    'amount' => 100,
                ]);

            $response->assertStatus(200);
            $response->assertHeader('X-RateLimit-Limit', $limit);
        }

        // Expect warning for all rate limits
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Rate limit exceeded'
                    && isset($context['user_id'], $context['ip'], $context['route'], $context['path'], $context['retry_after'], $context['timestamp']);
            });

        // Expect alert for payment route
        Log::shouldReceive('alert')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Suspicious payment activity blocked'
                    && isset($context['user_id'], $context['ip'], $context['endpoint'], $context['retry_after']);
            });

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/checkout', [
                'product_type' => 'boost',
                'amount' => 100,
            ]);

        $response->assertStatus(429);
        $response->assertJson(['message' => 'Too many requests. Please try again later.']);
    }

    #[Test]
    public function public_webhook_route_is_rate_limited_by_ip()
    {
        $limit = 10;

        $this->mpService->shouldReceive('validateSignature')
            ->andReturn(true);

        $this->mpService->shouldReceive('processWebhook')
            ->times($limit)
            ->andReturn(true);

        for ($i = 0; $i < $limit; $i++) {
            $response = $this->withHeaders([
                'X-Signature' => 'ts=123,v1=123',
                'X-Request-ID' => '123',
            ])->postJson('/api/v1/webhook/mercado-pago', [
                        'type' => 'payment',
                        'data' => ['id' => '123']
                    ]);

            $response->assertStatus(200);
            $response->assertHeader('X-RateLimit-Limit', $limit);
        }

        // Expect warning
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Rate limit exceeded'
                    && isset($context['ip'], $context['retry_after'], $context['timestamp']);
            });

        // Expect alert for webhook (payment related)
        Log::shouldReceive('alert')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Suspicious payment activity blocked'
                    && isset($context['ip'], $context['endpoint'], $context['retry_after']);
            });

        $response = $this->withHeaders([
            'X-Signature' => 'ts=123,v1=123',
            'X-Request-ID' => '123',
        ])->postJson('/api/v1/webhook/mercado-pago', [
                    'type' => 'payment',
                    'data' => ['id' => '123']
                ]);

        $response->assertStatus(429);
    }

    #[Test]
    public function login_route_is_rate_limited_by_ip()
    {
        $limit = 5;

        for ($i = 0; $i < $limit; $i++) {
            $this->post('/login', [
                'email' => $this->user->email,
                'password' => 'wrong-password',
            ]);
        }

        // Expect warning only
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Rate limit exceeded'
                    && isset($context['ip'], $context['retry_after'], $context['timestamp']);
            });

        // Explicitly expect NO alert
        Log::shouldReceive('alert')->never();

        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
    }

    #[Test]
    public function rate_limited_user_is_blocked_after_three_violations()
    {
        $limit = 5;

        Log::shouldReceive('warning')
            ->times(3)
            ->withArgs(function ($message) {
                return $message === 'Rate limit exceeded';
            });

        Log::shouldReceive('alert')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'User blocked for 24 hours due to rate limit violations'
                    && isset($context['key'], $context['violations'])
                    && $context['violations'] === 3;
            });

        // 1st Violation Cycle
        for ($i = 0; $i < $limit; $i++) {
            $this->postJson('/login', ['email' => $this->user->email, 'password' => 'wrong']);
        }
        $this->postJson('/login', ['email' => $this->user->email, 'password' => 'wrong'])
            ->assertStatus(429);

        // 2nd Violation Cycle (hitting while limited)
        $this->postJson('/login', ['email' => $this->user->email, 'password' => 'wrong'])
            ->assertStatus(429); // Violation 2

        // 3rd Violation Cycle (hitting while limited)
        $this->postJson('/login', ['email' => $this->user->email, 'password' => 'wrong'])
            ->assertStatus(429); // Violation 3 -> Logs Alert, Sets Block

        // Check Block
        $this->postJson('/login', ['email' => $this->user->email, 'password' => 'wrong'])
            ->assertStatus(403)
            ->assertJson([
                'message' => 'Account temporarily blocked due to suspicious activity.',
            ]);
    }

    #[Test]
    public function premium_users_have_double_rate_limits()
    {
        $limit = 5;
        $doubledLimit = $limit * 2;

        // Ensure known limit for this test
        config(['app.rate_limits.payment.checkout' => "{$limit},1"]);

        // Create user
        $user = User::factory()->create();

        // Create active subscription for user
        \App\Models\Subscription::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'ends_at' => now()->addMonth(),
        ]);

        $this->mpService->shouldReceive('createPaymentPreference')
            ->times($doubledLimit)
            ->andReturnUsing(function () {
                return [
                    'success' => true,
                    'preference_id' => 'pre_' . uniqid(),
                    'init_point' => 'http://mp.com',
                    'sandbox_init_point' => 'http://sandbox.mp.com',
                    'external_reference' => 'ref_' . uniqid(),
                ];
            });

        // Hit allowed limit (double)
        for ($i = 0; $i < $doubledLimit; $i++) {
            $response = $this->actingAs($user)
                ->postJson('/api/v1/checkout', [
                    'product_type' => 'boost',
                    'amount' => 100,
                ]);

            $response->assertStatus(200);
            $response->assertHeader('X-RateLimit-Limit', $doubledLimit);
        }

        // Verify next hit is blocked
        Log::shouldReceive('warning')->once(); // One warning for the limit hit
        Log::shouldReceive('alert')->once(); // One alert because it's payment route

        $response = $this->actingAs($user)
            ->postJson('/api/v1/checkout', [
                'product_type' => 'boost',
                'amount' => 100,
            ]);

        $response->assertStatus(429);
    }

    #[Test]
    public function whitelisted_ip_is_not_rate_limited()
    {
        // Whitelist localhost
        config(['app.rate_limit_whitelist' => ['127.0.0.1']]);

        $attempts = 10; // Exceed normal limit of 5

        // Neither warning nor alert should be called
        Log::shouldReceive('warning')->never();
        Log::shouldReceive('alert')->never();

        for ($i = 0; $i < $attempts; $i++) {
            // Use postJson to login with wrong credentials
            // It might fail validation or auth but should NOT be 429.
            // 422 Unprocessable Entity or 401 Unauthorized is fine.
            $response = $this->postJson('/login', [
                'email' => $this->user->email,
                'password' => 'wrong',
            ]);

            $this->assertNotEquals(429, $response->status(), "Request $i was rate limited!");
        }
    }
}
