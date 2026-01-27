<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MercadoPagoService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected MercadoPagoService $mpService;

    protected SubscriptionService $subscriptionService;

    public function __construct(MercadoPagoService $mpService, SubscriptionService $subscriptionService)
    {
        $this->mpService = $mpService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Webhook de Mercado Pago
     * POST /webhook/mercadopago
     *
     * Mercado Pago envía notificaciones a este endpoint cuando:
     * - Se aprueba un pago
     * - Se rechaza un pago
     * - Hay cambios de estado
     */
    public function handleMercadoPagoWebhook(Request $request)
    {
        try {
            // ✅ VERIFICAR FIRMA DE MERCADO PAGO
            $xSignature = $request->header('x-signature');
            $xRequestId = $request->header('x-request-id');
            $webhookSecret = config('services.mercadopago.webhook_secret');

            if (! $xSignature || ! $xRequestId || ! $webhookSecret) {
                Log::warning('Mercado Pago webhook missing signature header or secret not configured', [
                    'has_signature' => ! empty($xSignature),
                    'has_request_id' => ! empty($xRequestId),
                    'has_secret' => ! empty($webhookSecret),
                ]);

                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            // Parsear x-signature (formato: ts=...,v1=...)
            $parts = explode(',', $xSignature);
            $ts = null;
            $v1 = null;

            foreach ($parts as $part) {
                $kv = explode('=', $part);
                if (count($kv) === 2) {
                    if (trim($kv[0]) === 'ts') {
                        $ts = trim($kv[1]);
                    }
                    if (trim($kv[0]) === 'v1') {
                        $v1 = trim($kv[1]);
                    }
                }
            }

            if (! $ts || ! $v1) {
                Log::warning('Invalid x-signature format', ['signature' => $xSignature]);

                return response()->json(['status' => 'error', 'message' => 'Invalid signature format'], 401);
            }

            // El ID del recurso está en data.id o en el query param id
            $resourceId = $request->input('data.id') ?? $request->query('id');

            if ($resourceId) {
                // Manifest string: id:[id];ts:[ts];
                $manifest = "id:{$resourceId};ts:{$ts};";
                $sha256 = hash_hmac('sha256', $manifest, $webhookSecret);

                if (! hash_equals($sha256, $v1)) {
                    Log::error('Mercado Pago webhook signature mismatch!', [
                        'expected' => $sha256,
                        'received' => $v1,
                        'manifest' => $manifest,
                    ]);

                    return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
                }
            }

            Log::info('Mercado Pago webhook received and verified', [
                'type' => $request->input('type'),
                'data' => $request->input('data'),
            ]);

            $type = $request->input('type');
            $data = $request->input('data');

            // Procesar según el tipo de evento
            if ($type === 'subscription_preapproval') {
                $status = $this->mpService->handleSubscriptionWebhook($data['id']);

                return response()->json(['status' => $status ? 'success' : 'failed'], 200);
            }

            // Solo procesar webhooks de pagos a partir de aquí
            if ($type !== 'payment') {
                Log::info('Webhook type not payment or subscription, ignoring', ['type' => $type]);

                return response()->json(['status' => 'ignored'], 200);
            }

            $paymentId = $data['id'] ?? null;

            if (! $paymentId) {
                Log::warning('Payment webhook without payment_id');

                return response()->json(['status' => 'error', 'message' => 'No payment_id'], 400);
            }

            // Obtener detalles del pago desde Mercado Pago
            $paymentInfo = $this->mpService->getPaymentInfo($paymentId);

            if (! $paymentInfo['success']) {
                Log::error('Failed to get payment info', [
                    'payment_id' => $paymentId,
                    'error' => $paymentInfo['error'] ?? 'Unknown error',
                ]);

                return response()->json(['status' => 'error', 'message' => 'Failed to get payment info'], 400);
            }

            $payment = $paymentInfo['payment_data'];

            Log::info('Payment info retrieved', [
                'payment_id' => $paymentId,
                'status' => $payment['status'],
                'external_reference' => $payment['external_reference'] ?? 'N/A',
            ]);

            // Procesar según el estado del pago
            if ($payment['status'] === 'approved') {
                $this->handleApprovedPayment($payment);
            } elseif ($payment['status'] === 'rejected') {
                $this->handleRejectedPayment($payment);
            } elseif ($payment['status'] === 'pending') {
                $this->handlePendingPayment($payment);
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Error processing webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Procesar pago APROBADO
     */
    private function handleApprovedPayment($payment)
    {
        $externalReference = $payment['external_reference'] ?? null;
        $paymentId = $payment['id'];
        $amount = $payment['transaction_amount'];

        if (! $externalReference) {
            Log::warning('Approved payment without external_reference', [
                'payment_id' => $paymentId,
            ]);

            return;
        }

        Log::info('Processing approved payment', [
            'payment_id' => $paymentId,
            'external_reference' => $externalReference,
            'amount' => $amount,
        ]);

        // Determinar si es suscripción o compra por el external_reference
        // Formato: "USER_<user_id>_PLAN_<plan_id>_<timestamp>" para suscripciones
        // Formato: "purchase_<id>" para compras (si usas createPaymentPreference)

        if (strpos($externalReference, 'USER_') === 0) {
            $this->handleApprovedSubscription($payment, $externalReference);
        } else {
            $this->handleApprovedPurchase($payment, $externalReference);
        }
    }

    /**
     * Procesar suscripción APROBADA
     */
    private function handleApprovedSubscription($payment, $externalReference)
    {
        $paymentId = $payment['id'];

        // Parsear external_reference: USER_<user_id>_PLAN_<plan_id>_<timestamp>
        preg_match('/USER_(\d+)_PLAN_(\d+)/', $externalReference, $matches);

        if (! isset($matches[1]) || ! isset($matches[2])) {
            Log::warning('Could not parse subscription external_reference', [
                'external_reference' => $externalReference,
            ]);

            return;
        }

        $userId = (int) $matches[1];
        $planId = (int) $matches[2];

        $user = User::find($userId);
        $plan = SubscriptionPlan::find($planId);

        if (! $user || ! $plan) {
            Log::error('User or Plan not found for subscription webhook', [
                'user_id' => $userId,
                'plan_id' => $planId,
            ]);

            return;
        }

        // Usar el servicio para activar la suscripción (maneja creación, transacciones y flags)
        $this->subscriptionService->activateSubscription(
            $user,
            $plan,
            $payment['preapproval_id'] ?? 'N/A', // O pref_id si es checkout pro
            $paymentId,
            (float) $payment['transaction_amount']
        );

        Log::info('✅ Subscription processed via Service', [
            'user_id' => $userId,
            'plan_id' => $planId,
            'payment_id' => $paymentId,
        ]);
    }

    /**
     * Procesar compra APROBADA
     */
    private function handleApprovedPurchase($payment, $externalReference)
    {
        $paymentId = $payment['id'];

        // Buscar compra pendiente por mp_preference_id
        $purchase = Purchase::where('mp_preference_id', $externalReference)
            ->where('status', 'pending')
            ->first();

        if (! $purchase) {
            // Intentar buscar por external_reference en metadata
            Log::warning('Purchase not found by preference_id, searching by external_reference', [
                'external_reference' => $externalReference,
                'payment_id' => $paymentId,
            ]);

            return;
        }

        // Actualizar compra a COMPLETADA
        $purchase->update([
            'status' => 'completed',
            'mp_payment_id' => $paymentId,
            'completed_at' => now(),
        ]);

        // Registrar transacción
        Transaction::create([
            'user_id' => $purchase->user_id,
            'purchase_id' => $purchase->id,
            'type' => 'purchase',
            'mp_payment_id' => $paymentId,
            'amount' => $payment['transaction_amount'],
            'currency' => $payment['currency_id'] ?? 'ARS',
            'status' => 'approved',
            'description' => "{$purchase->quantity}x {$purchase->product_type}",
            'external_reference' => $payment['external_reference'],
        ]);

        // Otorgar el producto/beneficio al usuario
        $user = $purchase->user;
        $productType = $purchase->product_type;
        $quantity = $purchase->quantity ?? 1;

        Log::info('Granting product benefits', [
            'purchase_id' => $purchase->id,
            'user_id' => $user->id,
            'product_type' => $productType,
            'quantity' => $quantity,
        ]);

        $applied = false;

        switch ($productType) {
            case 'boost':
                // Por defecto 7 días si no se especifica en metadata
                $days = $purchase->metadata['days'] ?? 7;
                $applied = $this->mpService->grantBoost($user, $days);
                break;

            case 'super_likes':
                // Si es un pack, la cantidad multiplicada por 5 (según el título del producto anterior)
                // O simplemente usar la cantidad definida en la compra
                $likesToAdd = $purchase->metadata['likes_count'] ?? ($quantity * 5);
                $applied = $this->mpService->grantSuperLikes($user, $likesToAdd);
                break;

            case 'verification':
                $applied = $this->mpService->grantVerification($user);
                break;

            case 'gift':
                // Lógica de regalo (por ahora solo marcar como enviado)
                $applied = true; // El regalo se maneja vía notificaciones/modelo específico
                Log::info('Gift purchase processed', [
                    'purchase_id' => $purchase->id,
                    'recipient_id' => $purchase->recipient_id ?? $purchase->metadata['recipient_id'] ?? null,
                ]);
                break;

            default:
                Log::warning('Unknown product type in webhook', [
                    'product_type' => $productType,
                    'purchase_id' => $purchase->id,
                ]);
                break;
        }

        if ($applied) {
            Log::info('✅ Benefits applied successfully', [
                'purchase_id' => $purchase->id,
                'user_id' => $user->id,
            ]);
        } else {
            Log::error('❌ Failed to apply benefits', [
                'purchase_id' => $purchase->id,
                'user_id' => $user->id,
            ]);
        }

        Log::info('✅ Purchase completed', [
            'purchase_id' => $purchase->id,
            'user_id' => $purchase->user_id,
            'product_type' => $purchase->product_type,
            'payment_id' => $paymentId,
        ]);
    }

    /**
     * Procesar pago RECHAZADO
     */
    private function handleRejectedPayment($payment)
    {
        $paymentId = $payment['id'];
        $externalReference = $payment['external_reference'] ?? 'unknown';

        Log::warning('Payment rejected', [
            'payment_id' => $paymentId,
            'external_reference' => $externalReference,
            'reason' => $payment['status_detail'] ?? 'Unknown reason',
        ]);

        // Actualizar estado de suscripción o compra a failed
        if (strpos($externalReference, 'USER_') === 0) {
            preg_match('/USER_(\d+)_PLAN_(\d+)/', $externalReference, $matches);
            if (isset($matches) && isset($matches)) {
                $subscription = Subscription::where('user_id', $matches)
                    ->where('plan_id', $matches)
                    ->where('status', 'pending')
                    ->first();

                if ($subscription) {
                    $subscription->update(['status' => 'failed']);
                    Log::info('Subscription marked as failed', [
                        'subscription_id' => $subscription->id,
                    ]);
                }
            }
        } else {
            $purchase = Purchase::where('mp_preference_id', $externalReference)
                ->where('status', 'pending')
                ->first();

            if ($purchase) {
                $purchase->update(['status' => 'failed']);
                Log::info('Purchase marked as failed', [
                    'purchase_id' => $purchase->id,
                ]);
            }
        }
    }

    /**
     * Procesar pago PENDIENTE
     */
    private function handlePendingPayment($payment)
    {
        $paymentId = $payment['id'];
        $externalReference = $payment['external_reference'] ?? 'unknown';

        Log::info('Payment pending', [
            'payment_id' => $paymentId,
            'external_reference' => $externalReference,
            'status_detail' => $payment['status_detail'] ?? 'Unknown',
        ]);

        // Los pagos pendientes se revisan manualmente o se espera confirmación
        // No hacer cambios hasta que se apruebe o rechace
    }
}
