<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

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
            ->andReturnUsing(fn() => [
                'success' => true,
                'preference_id' => 'pre_123',
                'init_point' => 'http://mp.com',
                'sandbox_init_point' => 'http://sandbox.mp.com',
            ]);

        for ($i = 0; $i < $limit; $i++) {
            $response = $this->actingAs($this->user)
                ->postJson('/api/v1/checkout', [
                    'product_type' => 'boost',
                    'amount' => 100,
                ]);

            $response->assertStatus(200);
            $response->assertHeader('X-RateLimit-Limit', $limit);
        }

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

        $this->mpService->shouldReceive('processWebhook')
            ->times($limit)
            ->andReturn(true);

        for ($i = 0; $i < $limit; $i++) {
            $response = $this->postJson('/api/v1/webhook/mercado-pago', [
                'type' => 'payment',
                'data' => ['id' => '123']
            ]);

            $response->assertStatus(200);
            $response->assertHeader('X-RateLimit-Limit', $limit);
        }

        $response = $this->postJson('/api/v1/webhook/mercado-pago', [
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
            $response = $this->post('/login', [
                'email' => $this->user->email,
                'password' => 'wrong-password',
            ]);

            $response->assertStatus(302);
            $response->assertHeader('X-RateLimit-Limit', $limit);
        }

        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
    }
}
