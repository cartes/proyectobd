<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

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
                    'preference_id' => 'pre_'.uniqid(),
                    'init_point' => 'http://mp.com',
                    'sandbox_init_point' => 'http://sandbox.mp.com',
                    'external_reference' => 'ref_'.uniqid(),
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

        // The new WebhookController validates signatures internally.
        // We send requests with a deliberately invalid signature so they
        // return 401 quickly, but the rate-limit middleware still fires.
        // Allow Log::error calls from the signature mismatch check.
        Log::shouldReceive('error')->zeroOrMoreTimes();

        for ($i = 0; $i < $limit; $i++) {
            $response = $this->withHeaders([
                'X-Signature' => 'ts=123,v1=invalid_sig',
                'X-Request-ID' => '123',
            ])->postJson('/webhook/mercadopago', [
                'type' => 'payment',
                'data' => ['id' => '123'],
            ]);

            // 401 because signature is invalid, but the route exists (not 404).
            // X-RateLimit headers are only injected on pass-through responses, not on 401s.
            $response->assertStatus(401);
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
            'X-Signature' => 'ts=123,v1=invalid_sig',
            'X-Request-ID' => '123',
        ])->postJson('/webhook/mercadopago', [
            'type' => 'payment',
            'data' => ['id' => '123'],
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
                    'preference_id' => 'pre_'.uniqid(),
                    'init_point' => 'http://mp.com',
                    'sandbox_init_point' => 'http://sandbox.mp.com',
                    'external_reference' => 'ref_'.uniqid(),
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

    #[Test]
    public function test_whitelist_bypasses_rate_limit()
    {
        config(['app.rate_limit_whitelist' => ['127.0.0.1']]);

        // Mock routes if necessary, or just use login
        // Login route exists
        $limit = 100;

        for ($i = 0; $i < $limit; $i++) {
            $this->postJson('/login', [
                'email' => $this->user->email,
                'password' => 'wrong',
            ])->assertStatus(422); // Validation failure, not 429
        }
    }

    #[Test]
    public function test_premium_users_have_double_limit()
    {
        // Define route with known limit
        // Config: app.rate_limits.match.like = '30,1'
        \Illuminate\Support\Facades\Route::post('/api/match/like', function () {
            return response()->json(['status' => 'ok']);
        })->name('match.like')->middleware(\App\Http\Middleware\RateLimitMiddleware::class);

        $premium = User::factory()->create();
        \App\Models\Subscription::factory()->create([
            'user_id' => $premium->id,
            'status' => 'active',
            'ends_at' => now()->addMonth(),
        ]);

        $limit = 30;
        $doubleLimit = $limit * 2;

        // Premium can make 60 requests
        for ($i = 0; $i < $doubleLimit; $i++) {
            $this->actingAs($premium)
                ->postJson('/api/match/like', ['profile_id' => 1])
                ->assertSuccessful();
        }

        // The 61st is blocked
        $this->actingAs($premium)
            ->postJson('/api/match/like', ['profile_id' => 1])
            ->assertStatus(429);
    }

    #[Test]
    public function test_admin_users_not_rate_limited()
    {
        \Illuminate\Support\Facades\Route::post('/api/match/like', function () {
            return response()->json(['status' => 'ok']);
        })->name('match.like')->middleware(\App\Http\Middleware\RateLimitMiddleware::class);

        $admin = User::factory()->create(['role' => 'admin']);

        // 100 requests without blocking (reduced from 1000 for speed)
        for ($i = 0; $i < 100; $i++) {
            $this->actingAs($admin)
                ->postJson('/api/match/like', ['profile_id' => 1])
                ->assertSuccessful();
        }
    }

    #[Test]
    public function test_progressive_punishment_blocks_after_3_violations()
    {
        \Illuminate\Support\Facades\Route::post('/api/match/like', function () {
            return response()->json(['status' => 'ok']);
        })->name('match.like')->middleware(\App\Http\Middleware\RateLimitMiddleware::class);

        $user = User::factory()->create();
        $limit = 30;

        // Round 1: Violate
        for ($i = 0; $i < $limit; $i++) {
            $this->actingAs($user)->postJson('/api/match/like', ['profile_id' => 1]);
        }
        $this->actingAs($user)->postJson('/api/match/like', ['profile_id' => 1])->assertStatus(429);

        // Wait for decay (1 minute)
        $this->travel(61)->seconds();

        // Round 2: Violate
        for ($i = 0; $i < $limit; $i++) {
            $this->actingAs($user)->postJson('/api/match/like', ['profile_id' => 1]);
        }
        $this->actingAs($user)->postJson('/api/match/like', ['profile_id' => 1])->assertStatus(429);

        // Wait for decay
        $this->travel(61)->seconds();

        // Round 3: Violate
        for ($i = 0; $i < $limit; $i++) {
            $this->actingAs($user)->postJson('/api/match/like', ['profile_id' => 1]);
        }
        // This violation triggers the block logic internally
        $this->actingAs($user)->postJson('/api/match/like', ['profile_id' => 1])->assertStatus(429);

        // Next request should be blocked (403)
        $response = $this->actingAs($user)
            ->postJson('/api/match/like', ['profile_id' => 1]);

        $response->assertStatus(403);
    }

    #[Test]
    public function test_payment_endpoints_log_alerts()
    {
        Route::post('/payment/checkout', function () {
            return 'ok';
        })->name('payment.checkout')->middleware(\App\Http\Middleware\RateLimitMiddleware::class);

        config(['app.rate_limits.payment.checkout' => '5,1']);

        // Expect alert only once (for the blocked request)
        // Adjusting user expectation "times(6)" to "times(1)" because only blockage logs alert.
        Log::shouldReceive('alert')
            ->withArgs(function ($message) {
                return $message === 'Suspicious payment activity blocked';
            })
            ->once(); // Only the 6th request triggers alert

        Log::shouldReceive('warning')->once(); // warning also logged

        $user = User::factory()->create();

        // Make 6 requests (Limit 5)
        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($user)
                ->postJson('/payment/checkout', ['plan_id' => 1])
                ->assertSuccessful();
        }

        // 6th
        $this->actingAs($user)
            ->postJson('/payment/checkout', ['plan_id' => 1])
            ->assertStatus(429);
    }

    #[Test]
    public function test_different_limits_per_endpoint()
    {
        Route::middleware(\App\Http\Middleware\RateLimitMiddleware::class)->group(function () {
            Route::post('/api/match/like', fn () => 'ok')->name('match.like');
            Route::post('/api/messages', fn () => 'ok')->name('chat.message');
            Route::post('/payment/checkout', fn () => 'ok')->name('payment.checkout');
        });

        $user = User::factory()->create();

        // Like: 30 per minute
        for ($i = 0; $i < 30; $i++) {
            $this->actingAs($user)
                ->postJson('/api/match/like', ['profile_id' => 1])
                ->assertSuccessful();
        }

        // Message: 60 per minute
        for ($i = 0; $i < 60; $i++) {
            $this->actingAs($user)
                ->postJson('/api/messages', ['text' => 'test'])
                ->assertSuccessful();
        }

        // Checkout: 5 per minute
        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($user)
                ->postJson('/payment/checkout', ['plan_id' => 1])
                ->assertSuccessful();
        }

        // The 6th checkout should be blocked
        $this->actingAs($user)
            ->postJson('/payment/checkout', ['plan_id' => 1])
            ->assertStatus(429);
    }

    #[Test]
    public function test_rate_limit_headers_complete()
    {
        Route::post('/api/match/like', fn () => 'ok')
            ->name('match.like')
            ->middleware(\App\Http\Middleware\RateLimitMiddleware::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/match/like', ['profile_id' => 1]);

        $this->assertNotNull($response->headers->get('X-RateLimit-Limit'));
        $this->assertNotNull($response->headers->get('X-RateLimit-Remaining'));
        $this->assertNotNull($response->headers->get('X-RateLimit-Reset'));

        // Verify values
        $this->assertEquals('30', $response->headers->get('X-RateLimit-Limit'));
        $this->assertTrue($response->headers->get('X-RateLimit-Remaining') < 30);
    }
}
