<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MercadoPagoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MercadoPagoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(MercadoPagoService::class);
    }

    /**
     * TEST 22: Service crea preferencia de pago correctamente
     */
    public function test_service_creates_payment_preference()
    {
        $user = User::factory()->create();

        Http::fake([
            'api.mercadopago.com/checkout/preferences' => Http::response([
                'id' => 'MP-123456',
                'init_point' => 'https://mercadopago.com/checkout/abc123',
                'sandbox_init_point' => 'https://mercadopago.com/checkout/sandbox/abc123',
            ], 201),
        ]);

        $preference = $this->service->createPaymentPreference($user, 'boost', 99.99);

        $this->assertEquals('MP-123456', $preference['preference_id']);
        $this->assertStringContainsString('mercadopago.com/checkout', $preference['init_point']);
    }

    /**
     * TEST 23: Service obtiene detalles de pago
     */
    public function test_service_retrieves_payment_details()
    {
        Http::fake([
            'api.mercadopago.com/v1/payments/123456' => Http::response([
                'id' => 123456,
                'status' => 'approved',
                'transaction_amount' => 99.99,
                'currency_id' => 'ARS',
            ], 200),
        ]);

        $payment = $this->service->getPaymentInfo(123456);

        $this->assertEquals('approved', $payment['status']);
        $this->assertEquals(99.99, $payment['amount']);
    }

    /**
     * TEST 24: Service maneja errores de API
     */
    public function test_service_handles_api_errors()
    {
        Http::fake([
            'api.mercadopago.com/v1/payments/*' => Http::response([
                'error' => 'Invalid request',
            ], 400),
        ]);

        $this->expectException(\Exception::class);
        $this->service->getPaymentInfo(999999);
    }
}
