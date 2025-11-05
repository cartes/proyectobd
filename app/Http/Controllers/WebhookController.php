<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Transaction;
use App\Services\SubscriptionService;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected SubscriptionService $subscriptionService;
    protected MercadoPagoService $mpService;

    public function __construct(
        SubscriptionService $subscriptionService,
        MercadoPagoService $mpService
    ) {
        $this->subscriptionService = $subscriptionService;
        $this->mpService = $mpService;
    }

    /**
     * Manejar webhooks de Mercado Pago
     */
    public function handleMercadoPagoWebhook(Request $request)
    {
        // Log de todos los webhooks
        Log::info('MP Webhook received', $request->all());

        // Mercado Pago envía el evento en el query string
        $type = $request->query('type');
        $id = $request->query('id');
        $topic = $request->query('topic');

        // Procesar según el tipo de evento
        if ($topic === 'payment') {
            return $this->handlePaymentNotification($id);
        } elseif ($topic === 'plan') {
            return $this->handlePlanNotification($id);
        } elseif ($topic === 'preapproval') {
            return $this->handlePreApprovalNotification($id);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Procesar notificación de pago
     */
    protected function handlePaymentNotification(string $paymentId): \Illuminate\Http\JsonResponse
    {
        try {
            // Obtener info del pago desde MP
            $paymentInfo = $this->mpService->getPaymentInfo($paymentId);

            if (!$paymentInfo['success']) {
                Log::error('Failed to get payment info', ['payment_id' => $paymentId]);
                return response()->json(['success' => false], 400);
            }

            $status = $paymentInfo['status'];
            $externalReference = $paymentInfo['external_reference'];

            // Parsear la referencia para identificar tipo de transacción
            // Formato: {type}_{user_id}_{plan_id}_{timestamp}_{random}
            $parts = explode('_', $externalReference);
            $transactionType = $parts[0] ?? null; // 'subscription' o 'purchase'
            $userId = $parts[1] ?? null;
            $planId = $parts[2] ?? null;

            $user = User::find($userId);
            if (!$user) {
                Log::error('User not found', ['user_id' => $userId]);
                return response()->json(['success' => false], 400);
            }

            // Si es una suscripción
            if ($transactionType === 'subscription') {
                return $this->processSubscriptionPayment($user, $planId, $paymentInfo, $status);
            }

            // Si es una compra única
            if ($transactionType === 'purchase') {
                return $this->processPurchasePayment($user, $paymentInfo, $status);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error handling payment notification', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId,
            ]);

            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Procesar pago de suscripción
     */
    protected function processSubscriptionPayment(User $user, $planId, array $paymentInfo, string $status)
    {
        $plan = SubscriptionPlan::find($planId);

        if (!$plan) {
            Log::error('Plan not found', ['plan_id' => $planId]);
            return response()->json(['success' => false], 400);
        }

        if ($status === 'approved') {
            // Activar suscripción
            $result = $this->subscriptionService->activateSubscription(
                $user,
                $plan,
                $paymentInfo['payment_id'], // usar como preapproval_id temporalmente
                $paymentInfo['payment_id'],
                $paymentInfo['amount']
            );

            if ($result['success']) {
                Log::info('Subscription activated', [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                ]);

                // Enviar email de confirmación
                \Mail::queue(new \App\Mail\SubscriptionActivatedMail($user, $result['subscription']));

                return response()->json(['success' => true]);
            }
        } elseif ($status === 'rejected' || $status === 'cancelled') {
            // Crear transacción fallida
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'subscription',
                'mp_payment_id' => $paymentInfo['payment_id'],
                'amount' => $paymentInfo['amount'],
                'currency' => 'ARS',
                'status' => $status === 'rejected' ? 'rejected' : 'cancelled',
                'description' => "Pago de suscripción: {$plan->name}",
                'external_reference' => $paymentInfo['external_reference'],
            ]);

            Log::warning('Subscription payment failed', [
                'user_id' => $user->id,
                'status' => $status,
            ]);
        } elseif ($status === 'pending') {
            // Crear transacción pendiente
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'subscription',
                'mp_payment_id' => $paymentInfo['payment_id'],
                'amount' => $paymentInfo['amount'],
                'currency' => 'ARS',
                'status' => 'pending',
                'description' => "Pago de suscripción: {$plan->name} (pendiente)",
                'external_reference' => $paymentInfo['external_reference'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Procesar pago de compra única
     */
    protected function processPurchasePayment(User $user, array $paymentInfo, string $status)
    {
        if ($status === 'approved') {
            // Extraer tipo de producto de los metadatos
            $metadata = $paymentInfo['raw']['metadata'] ?? [];
            $productType = $metadata['product_type'] ?? 'unknown';

            // Registrar compra
            $result = $this->subscriptionService->recordPurchase(
                $user,
                $productType,
                1,
                $paymentInfo['amount'],
                $paymentInfo['payment_id'],
                $metadata
            );

            if ($result['success']) {
                Log::info('Purchase completed', [
                    'user_id' => $user->id,
                    'product_type' => $productType,
                ]);

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Procesar notificación de plan
     */
    protected function handlePlanNotification(string $planId): \Illuminate\Http\JsonResponse
    {
        Log::info('Plan notification received', ['plan_id' => $planId]);
        return response()->json(['success' => true]);
    }

    /**
     * Procesar notificación de preaprobación (suscripción recurrente)
     */
    protected function handlePreApprovalNotification(string $preApprovalId): \Illuminate\Http\JsonResponse
    {
        try {
            $preApprovalInfo = $this->mpService->getPreApprovalInfo($preApprovalId);

            if (!$preApprovalInfo['success']) {
                Log::error('Failed to get preapproval info', ['preapproval_id' => $preApprovalId]);
                return response()->json(['success' => false], 400);
            }

            $status = $preApprovalInfo['status'];

            // Procesar según el estado
            if ($status === 'active') {
                Log::info('Preapproval activated', ['preapproval_id' => $preApprovalId]);
            } elseif ($status === 'cancelled') {
                Log::info('Preapproval cancelled', ['preapproval_id' => $preApprovalId]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error handling preapproval notification', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false], 500);
        }
    }
}
