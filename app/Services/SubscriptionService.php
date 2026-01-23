<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    protected MercadoPagoService $mpService;

    public function __construct(MercadoPagoService $mpService)
    {
        $this->mpService = $mpService;
    }

    /**
     * Activar una suscripción después de pago aprobado
     */
    public function activateSubscription(
        User $user,
        SubscriptionPlan $plan,
        string $mpPreapprovalId,
        string $mpPaymentId,
        float $amount
    ): array {
        try {
            return DB::transaction(function () use ($user, $plan, $mpPreapprovalId, $mpPaymentId, $amount) {
                // Cancelar suscripciones previas activas
                $this->deactivatePreviousSubscriptions($user);

                // Crear suscripción
                $subscription = Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'mp_preapproval_id' => $mpPreapprovalId,
                    'status' => 'active',
                    'payment_method' => 'mercadopago',
                    'starts_at' => now(),
                    'ends_at' => $this->calculateEndDate($plan),
                    'next_billing_date' => $this->calculateNextBillingDate($plan),
                ]);

                // Crear transacción
                Transaction::create([
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                    'type' => 'subscription',
                    'mp_payment_id' => $mpPaymentId,
                    'amount' => $amount,
                    'currency' => $plan->currency,
                    'status' => 'approved',
                    'description' => "Suscripción: {$plan->name}",
                    'external_reference' => "SUB_{$subscription->id}_{$mpPaymentId}",
                ]);

                // Actualizar flag is_premium en User
                $user->update(['is_premium' => true]);

                \Log::info('Subscription activated', [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'subscription_id' => $subscription->id,
                ]);

                return [
                    'success' => true,
                    'subscription' => $subscription,
                    'message' => 'Suscripción activada exitosamente',
                ];
            });
        } catch (\Exception $e) {
            \Log::error('Error activating subscription', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'plan_id' => $plan->id,
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo activar la suscripción',
            ];
        }
    }

    /**
     * Registrar compra de producto único (boost, super likes, etc.)
     */
    public function recordPurchase(
        User $user,
        string $productType,
        int $quantity,
        float $amount,
        string $mpPaymentId,
        array $metadata = []
    ): array {
        try {
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'product_type' => $productType,
                'quantity' => $quantity,
                'amount' => $amount,
                'currency' => config('services.mercadopago.currency', 'ARS'),
                'mp_payment_id' => $mpPaymentId,
                'status' => 'completed',
                'metadata' => $metadata,
            ]);

            // Procesar el producto comprado
            $this->processPurchaseProduct($user, $productType, $quantity, $metadata);

            \Log::info('Purchase recorded', [
                'user_id' => $user->id,
                'product_type' => $productType,
                'purchase_id' => $purchase->id,
            ]);

            return [
                'success' => true,
                'purchase' => $purchase,
                'message' => 'Compra registrada exitosamente',
            ];
        } catch (\Exception $e) {
            \Log::error('Error recording purchase', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo registrar la compra',
            ];
        }
    }

    /**
     * Procesar producto comprado (darle los beneficios al usuario)
     */
    protected function processPurchaseProduct(User $user, string $productType, int $quantity, array $metadata): void
    {
        match ($productType) {
            'boost' => $this->applyBoost($user, $metadata),
            'super_likes' => $this->addSuperLikes($user, $quantity),
            'verification' => $this->fastTrackVerification($user),
            'gift' => $this->sendGift($user, $metadata),
            default => null,
        };
    }

    /**
     * Aplicar boost al perfil del usuario
     */
    protected function applyBoost(User $user, array $metadata): void
    {
        $days = $metadata['days'] ?? 7;
        $this->mpService->grantBoost($user, $days);
    }

    /**
     * Agregar super likes al usuario
     */
    protected function addSuperLikes(User $user, int $quantity): void
    {
        $this->mpService->grantSuperLikes($user, $quantity);
    }

    /**
     * Fast track verification
     */
    protected function fastTrackVerification(User $user): void
    {
        $this->mpService->grantVerification($user);
    }

    /**
     * Enviar regalo virtual
     */
    protected function sendGift(User $user, array $metadata): void
    {
        // TODO: Implementar envío de regalo
        $recipientId = $metadata['recipient_user_id'] ?? null;
        \Log::info('Gift sent', ['from' => $user->id, 'to' => $recipientId]);
    }

    /**
     * Deactivar suscripciones activas previas
     */
    public function deactivatePreviousSubscriptions(User $user): void
    {
        $user->subscriptions()
            ->where('status', 'active')
            ->each(function (Subscription $subscription) {
                $subscription->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
            });
    }

    /**
     * Cancelar suscripción del usuario
     */
    public function cancelSubscription(User $user): array
    {
        try {
            $subscription = $user->activeSubscription();

            if (!$subscription) {
                return [
                    'success' => false,
                    'error' => 'No hay suscripción activa para cancelar',
                ];
            }

            // Cancelar en Mercado Pago
            $result = $this->mpService->cancelPreApproval($subscription->mp_preapproval_id);

            if (!$result['success']) {
                return $result;
            }

            // Actualizar en BD
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Actualizar flag is_premium
            $user->update(['is_premium' => false]);

            \Log::info('Subscription cancelled', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
            ]);

            return [
                'success' => true,
                'message' => 'Suscripción cancelada exitosamente',
            ];
        } catch (\Exception $e) {
            \Log::error('Error cancelling subscription', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo cancelar la suscripción',
            ];
        }
    }

    /**
     * Verificar y procesar suscripciones expiradas
     */
    public function checkExpiredSubscriptions(): array
    {
        $expired = Subscription::where('status', 'active')
            ->where('ends_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expired as $subscription) {
            $subscription->update(['status' => 'expired']);
            $subscription->user->update(['is_premium' => false]);
            $count++;
        }

        \Log::info('Expired subscriptions processed', ['count' => $count]);

        return [
            'success' => true,
            'expired_count' => $count,
        ];
    }

    /**
     * Obtener features disponibles para el usuario
     */
    public function getUserFeatures(User $user): array
    {
        // ✅ AGREGAR ->first() para obtener el modelo, no la relación
        $subscription = $user->activeSubscription()->first();

        // Si no tiene suscripción activa, retornar features gratuitas
        if (!$subscription) {
            return [
                'unlimited_likes' => false,
                'super_likes' => false,
                'rewind' => false,
                'boost_monthly' => false,
                'advanced_filters' => false,
                'no_ads' => false,
                'priority_verification' => false,
                'private_profiles' => false,
                'extended_photos' => false,
                'share_data' => false,
                'plan_name' => 'Gratis',
                'expires_at' => null,
            ];
        }

        // Features según el plan (puedes personalizarlas por plan)
        $features = $subscription->plan->features ?? [];

        return [
            'unlimited_likes' => in_array('unlimited_likes', $features),
            'super_likes' => in_array('super_likes', $features),
            'rewind' => in_array('rewind', $features),
            'boost_monthly' => in_array('boost_monthly', $features),
            'advanced_filters' => in_array('advanced_filters', $features),
            'no_ads' => in_array('no_ads', $features),
            'priority_verification' => in_array('priority_verification', $features),
            'private_profiles' => in_array('private_profiles', $features),
            'extended_photos' => in_array('extended_photos', $features),
            'share_data' => in_array('share_data', $features),
            'plan_name' => $subscription->plan->name,
            'expires_at' => $subscription->ends_at,
        ];
    }

    /**
     * Calcular fecha de expiración según el plan
     */
    protected function calculateEndDate(SubscriptionPlan $plan): Carbon
    {
        return now()->add($plan->frequency, $plan->frequency_type);
    }

    /**
     * Calcular próxima fecha de cobro
     */
    protected function calculateNextBillingDate(SubscriptionPlan $plan): Carbon
    {
        return now()->add($plan->frequency, $plan->frequency_type);
    }
}
