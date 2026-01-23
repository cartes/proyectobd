<?php

namespace App\Http\Controllers\Api;

use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class WebhookController extends Controller
{
    public function handle(Request $request, MercadoPagoService $mercadopagoService): JsonResponse
    {
        try {
            $payload = $request->all();

            Log::info('Webhook received from Mercado Pago', [
                'type' => $payload['type'] ?? null,
                'id' => $payload['id'] ?? null,
                'data_id' => $payload['data']['id'] ?? null,
            ]);

            $success = $mercadopagoService->processWebhook($payload);

            return response()->json([
                'success' => $success,
                'message' => $success ? 'Webhook procesado exitosamente' : 'Webhook recibido pero no procesado',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error processing webhook', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function test(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Webhook endpoint is working',
            'timestamp' => now(),
        ]);
    }

}
