<?php

namespace App\Http\Controllers;

use App\Services\MercadoPagoService;
use App\Models\Transaction;
use App\Models\Refund;
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

            // Check for active subscription
            if ($request->product_type === 'subscription' && $user->hasActiveSubscription()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already has an active subscription'
                ], 403);
            }

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

            // Crear transacción en estado pendiente
            Transaction::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'amount' => $request->amount,
                'currency' => 'ARS',
                'type' => 'payment',
                'description' => $request->product_type,
                'external_reference' => $result['external_reference'] ?? null,
            ]);

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
            $transaction = Transaction::where('mp_payment_id', $request->payment_id)->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            // Check if already refunded
            if ($transaction->refunds()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction already refunded'
                ], 403);
            }

            // Check for 7-day policy
            if ($transaction->created_at->lt(now()->subDays(7))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Refund period expired'
                ], 403);
            }

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

            // Buscar transacción y crear record de reembolso
            $transaction = Transaction::where('mp_payment_id', $request->payment_id)->first();
            if ($transaction) {
                $refund = Refund::create([
                    'user_id' => $transaction->user_id, // Add this
                    'transaction_id' => $transaction->id,
                    'amount' => $request->amount ?? $transaction->amount,
                    'reason' => $request->reason ?? 'Requested by user',
                    'status' => 'requested',
                    'mp_refund_id' => $result['refund_id'] ?? null,
                ]);

                // Si es reembolso total, cancelar la suscripción si existe
                $subscription = \App\Models\Subscription::where('user_id', $transaction->user_id)->first();
                if ($subscription) {
                    $subscription->update(['status' => 'cancelled']);
                }
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
        $xSignature = $request->header('x-signature');
        $xRequestId = $request->header('x-request-id');
        $resourceId = $request->query('id'); // Mercado Pago a veces envía el ID aquí
        $payload = $request->all();

        if (!$xSignature || !$xRequestId || !$this->mpService->validateSignature($xSignature, $xRequestId, $resourceId, $payload)) {
            Log::warning('Unauthorized MP Webhook attempt', [
                'has_signature' => !empty($xSignature),
                'has_request_id' => !empty($xRequestId),
                'payload' => $payload
            ]);
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $processed = $this->mpService->processWebhook($payload);

        if ($processed) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'failed'], 400);
    }
}
