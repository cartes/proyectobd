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
}
