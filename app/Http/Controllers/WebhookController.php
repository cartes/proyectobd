<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MercadoPagoService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected MercadoPagoService $mpService;
    protected SubscriptionService $subscriptionService;

    public function __construct(
        MercadoPagoService $mpService,
        SubscriptionService $subscriptionService
    ) {
        $this->mpService = $mpService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Manejar webhooks de Mercado Pago
     */
    public function handleMercadoPagoWebhook(Request $request): JsonResponse
    {
        // Log de todos los webhooks
        Log::info('MP Webhook received', $request->all());

        // Mercado Pago envía el evento en el query string
        $type = $request->query('type');
        $id = $request->query('id');
        $topic = $request->query('topic');

        // Procesar según el tipo de evento
        if ($topic === 'payment' || $type === 'payment') {
            return $this->handlePaymentNotification($id);
        } elseif ($topic === 'plan') {
            return $this->handlePlanNotification($id);
        } elseif ($topic === 'preapproval') {
            return $this->handlePreApprovalNotification($id);
        }

        return response()->json(['success' => true, 'message' => 'Event type not handled']);
    }

    /**
     * Procesar notificación de pago
     */
    protected function handlePaymentNotification(string $paymentId): JsonResponse
    {
        try {
            // Obtener info del pago desde MP
            $paymentInfo = $this->mpService->getPaymentInfo($paymentId);

            if (!$paymentInfo['success']) {
                Log::error('Failed to get payment info', ['payment_id' => $paymentId]);
                return response()->json(['success' => false, 'error' => 'Failed to get payment info'], 400);
            }

            $status = $paymentInfo['status'];
            $externalReference = $paymentInfo['external_reference'];
            $metadata = $paymentInfo['metadata'] ?? [];

            Log::info('Processing payment', [
                'payment_id' => $paymentId,
                'status' => $status,
                'external_reference' => $externalReference,
                'metadata' => $metadata
            ]);

            // Parsear external_reference
            // Formato: {type}_{user_id}_{plan_id_or_timestamp}_{timestamp}_{random}
            $parts = explode('_', $externalReference);
            $transactionType = $parts[0] ?? null; // 'subscription' o 'purchase'
            $userId = $parts[1] ?? null;

            $user = User::find($userId);
            if (!$user) {
                Log::error('User not found', ['user_id' => $userId]);
                return response()->json(['success' => false, 'error' => 'User not found'], 400);
            }

            // ✅ VALIDACIÓN DE DUPLICADOS - Verificar si ya procesamos este pago
            $existingTransaction = Transaction::where('mp_payment_id', $paymentId)->first();
            if ($existingTransaction) {
                Log::info('Payment already processed', [
                    'payment_id' => $paymentId,
                    'transaction_id' => $existingTransaction->id
                ]);
                return response()->json(['success' => true, 'message' => 'Already processed']);
            }

            // Determinar si es suscripción o compra
            if ($transactionType === 'subscription') {
                $planId = $parts[2] ?? null;
                return $this->processSubscriptionPayment($user, $planId, $paymentInfo, $status);
            }

            if ($transactionType === 'purchase') {
                // Para compras, obtener product_type desde metadata
                $productType = $metadata['product_type'] ?? 'unknown';
                return $this->processPurchasePayment($user, $paymentInfo, $status, $productType);
            }

            Log::warning('Unknown transaction type', ['type' => $transactionType]);
            return response()->json(['success' => false, 'error' => 'Unknown transaction type'], 400);

        } catch (\Exception $e) {
            Log::error('Error handling payment notification', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'error' => 'Internal error'], 500);
        }
    }

    /**
     * Procesar pago de suscripción
     */
    protected function processSubscriptionPayment(User $user, $planId, array $paymentInfo, string $status): JsonResponse
    {
        try {
            $plan = SubscriptionPlan::find($planId);
            if (!$plan) {
                Log::error('Plan not found', ['plan_id' => $planId]);
                return response()->json(['success' => false, 'error' => 'Plan not found'], 400);
            }

            // Solo procesar si el pago fue aprobado
            if ($status === 'approved') {
                DB::transaction(function () use ($user, $plan, $paymentInfo) {
                    // Activar suscripción
                    $result = $this->subscriptionService->activateSubscription(
                        $user,
                        $plan,
                        $paymentInfo['payment_id'], // Usar payment_id como preapproval_id temporal
                        $paymentInfo['payment_id'],
                        $paymentInfo['amount']
                    );

                    Log::info('Subscription activated', [
                        'user_id' => $user->id,
                        'plan_id' => $plan->id,
                        'subscription_id' => $result['subscription']->id ?? null
                    ]);
                });

                return response()->json(['success' => true, 'message' => 'Subscription activated']);
            }

            // Registrar estados no aprobados
            Log::info('Payment not approved', [
                'status' => $status,
                'user_id' => $user->id,
                'plan_id' => $planId
            ]);

            return response()->json(['success' => true, 'message' => "Payment status: {$status}"]);

        } catch (\Exception $e) {
            Log::error('Error processing subscription payment', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'plan_id' => $planId
            ]);
            return response()->json(['success' => false, 'error' => 'Error processing subscription'], 500);
        }
    }

    /**
     * Procesar compra única (boost, super likes, etc.)
     */
    protected function processPurchasePayment(User $user, array $paymentInfo, string $status, string $productType): JsonResponse
    {
        try {
            // ✅ VALIDACIÓN DE DUPLICADOS para purchases
            $existingPurchase = Purchase::where('mp_payment_id', $paymentInfo['payment_id'])->first();
            if ($existingPurchase) {
                Log::info('Purchase already processed', [
                    'payment_id' => $paymentInfo['payment_id'],
                    'purchase_id' => $existingPurchase->id
                ]);
                return response()->json(['success' => true, 'message' => 'Already processed']);
            }

            if ($status === 'approved') {
                DB::transaction(function () use ($user, $paymentInfo, $productType) {
                    // Obtener cantidad desde metadata (para super likes)
                    $metadata = $paymentInfo['metadata'] ?? [];
                    $quantity = $metadata['quantity'] ?? 1;

                    // Crear registro de compra
                    $purchase = Purchase::create([
                        'user_id' => $user->id,
                        'product_type' => $productType,
                        'quantity' => $quantity,
                        'amount' => $paymentInfo['amount'],
                        'currency' => $paymentInfo['currency'],
                        'mp_payment_id' => $paymentInfo['payment_id'],
                        'status' => 'completed',
                        'metadata' => $metadata,
                    ]);

                    // Crear transacción
                    Transaction::create([
                        'user_id' => $user->id,
                        'subscription_id' => null,
                        'type' => 'purchase',
                        'mp_payment_id' => $paymentInfo['payment_id'],
                        'amount' => $paymentInfo['amount'],
                        'currency' => $paymentInfo['currency'],
                        'status' => 'approved',
                        'description' => "Compra: {$productType}",
                        'external_reference' => $paymentInfo['external_reference'],
                        'metadata' => $metadata,
                    ]);

                    // Aplicar el producto al usuario (según el tipo)
                    $this->applyPurchaseToUser($user, $productType, $quantity);

                    Log::info('Purchase completed', [
                        'user_id' => $user->id,
                        'product_type' => $productType,
                        'purchase_id' => $purchase->id
                    ]);
                });

                return response()->json(['success' => true, 'message' => 'Purchase completed']);
            }

            Log::info('Purchase payment not approved', [
                'status' => $status,
                'user_id' => $user->id,
                'product_type' => $productType
            ]);

            return response()->json(['success' => true, 'message' => "Payment status: {$status}"]);

        } catch (\Exception $e) {
            Log::error('Error processing purchase payment', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'product_type' => $productType
            ]);
            return response()->json(['success' => false, 'error' => 'Error processing purchase'], 500);
        }
    }

    /**
     * Aplicar compra al usuario (boost, super likes, verification)
     */
    protected function applyPurchaseToUser(User $user, string $productType, int $quantity): void
    {
        switch ($productType) {
            case 'boost':
                // Activar boost por 1 hora
                $user->profileDetail()->update([
                    'boost_until' => now()->addHour(),
                ]);
                Log::info('Boost activated', ['user_id' => $user->id]);
                break;

            case 'super_likes':
                // Agregar super likes
                $user->profileDetail()->increment('super_likes_remaining', $quantity);
                Log::info('Super likes added', ['user_id' => $user->id, 'quantity' => $quantity]);
                break;

            case 'verification':
                // Marcar como verificado
                $user->update(['is_verified' => true]);
                Log::info('User verified', ['user_id' => $user->id]);
                break;

            case 'gift':
                // Registrar regalo (implementar según lógica de negocio)
                Log::info('Gift received', ['user_id' => $user->id]);
                break;

            default:
                Log::warning('Unknown product type', ['product_type' => $productType]);
        }
    }

    /**
     * Manejar notificaciones de plan
     */
    protected function handlePlanNotification(string $planId): JsonResponse
    {
        Log::info('Plan notification received', ['plan_id' => $planId]);
        // Implementar según necesidad
        return response()->json(['success' => true]);
    }

    /**
     * Manejar notificaciones de preaprobación
     */
    protected function handlePreApprovalNotification(string $preApprovalId): JsonResponse
    {
        try {
            $preApprovalInfo = $this->mpService->getPreApprovalInfo($preApprovalId);

            if (!$preApprovalInfo['success']) {
                Log::error('Failed to get preapproval info', ['preapproval_id' => $preApprovalId]);
                return response()->json(['success' => false], 400);
            }

            $status = $preApprovalInfo['status'];

            Log::info('PreApproval notification', [
                'preapproval_id' => $preApprovalId,
                'status' => $status
            ]);

            // Actualizar estado de suscripción según el status
            $subscription = Subscription::where('mp_preapproval_id', $preApprovalId)->first();

            if ($subscription) {
                if ($status === 'cancelled') {
                    $subscription->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now()
                    ]);
                    Log::info('Subscription cancelled via webhook', ['subscription_id' => $subscription->id]);
                }
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Error handling preapproval notification', [
                'error' => $e->getMessage(),
                'preapproval_id' => $preApprovalId
            ]);
            return response()->json(['success' => false], 500);
        }
    }
}
