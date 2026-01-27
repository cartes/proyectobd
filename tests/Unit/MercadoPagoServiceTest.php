<?php

namespace Tests\Unit;

use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MercadoPagoServiceTest extends TestCase
{
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
        Http::fake([
            'api.mercadopago.com/checkout/preferences' => Http::response([
                'id' => 'MP-123456',
                'client_id' => 'test-client',
                'init_point' => 'https://mercadopago.com/checkout/abc123',
            ], 201),
        ]);

        $preference = $this->service->createPreference([
            'title' => 'Premium Plan',
            'price' => 99.99,
            'currency' => 'ARS',
            'user_id' => 1,
        ]);

        $this->assertEquals('MP-123456', $preference['id']);
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
                'amount' => 99.99,
                'currency' => 'ARS',
            ], 200),
        ]);

        $payment = $this->service->getPayment(123456);

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
        $this->service->getPayment(999999);
    }
}
