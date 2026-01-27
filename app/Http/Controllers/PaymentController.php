<?php

namespace App\Http\Controllers;

use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentController extends Controller
{
    protected MercadoPagoService $mpService;

    public function __construct(MercadoPagoService $mpService)
    {
        $this->mpService = $mpService;
    }

    /**
     * Iniciar proceso de pago (Crear preferencia)
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'product_type' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'metadata' => 'nullable|array',
        ]);

        try {
            $user = auth()->user();
            $result = $this->mpService->createPaymentPreference(
                $user,
                $request->product_type,
                $request->amount,
                $request->metadata ?? []
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Error creating payment preference'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'preference_id' => $result['preference_id'],
                'init_point' => $result['init_point'],
                'sandbox_init_point' => $result['sandbox_init_point'],
            ]);

        } catch (Exception $e) {
            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An internal error occurred'
            ], 500);
        }
    }

    /**
     * Procesar reembolso
     */
    public function refund(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|string',
            'amount' => 'nullable|numeric|min:0.01',
        ]);

        try {
            // Solo administradores deberían poder reembolsar (esto debería manejarse por middleware adicional si es necesario)
            $result = $this->mpService->processRefund(
                $request->payment_id,
                $request->amount
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Refund failed'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'refund_id' => $result['refund_id'],
                'status' => $result['status'],
            ]);

        } catch (Exception $e) {
            Log::error('Refund failed', [
                'error' => $e->getMessage(),
                'payment_id' => $request->payment_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An internal error occurred'
            ], 500);
        }
    }

    /**
     * Webhook unificado de Mercado Pago
     */
    public function webhook(Request $request)
    {
        // El MercadoPagoService ya tiene lógica para procesar webhooks
        // Aquí podríamos agregar la validación de firma si queremos ser redundantes con WebhookController
        // o simplemente delegar al servicio que ya está configurado.

        $payload = $request->all();

        Log::info('Unified Webhook received', ['payload' => $payload]);

        $processed = $this->mpService->processWebhook($payload);

        if ($processed) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'failed'], 400);
    }
}
