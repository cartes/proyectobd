<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use WandesCardoso\MercadoPago\Facades\MercadoPago;
use Exception;

class MercadoPagoService
{
    /**
     * Crear preferencia de pago para una compra única (boost, super likes, etc.)
     */
    public function createPaymentPreference(User $user, string $productType, float $amount, array $metadata = []): array
    {
        try {
            $externalReference = $this->generateExternalReference($user->id, $productType);

            $preference = [
                'items' => [
                    [
                        'title' => $this->getProductTitle($productType),
                        'description' => $this->getProductDescription($productType),
                        'picture_url' => config('app.url') . '/images/products/' . $productType . '.png',
                        'category_id' => 'services',
                        'quantity' => 1,
                        'unit_price' => $amount,
                    ]
                ],
                'payer' => [
                    'email' => $user->email,
                    'name' => $user->name,
                ],
                'back_urls' => [
                    'success' => route('purchase.success'),
                    'failure' => route('purchase.failure'),
                    'pending' => route('purchase.pending'),
                ],
                'auto_return' => 'approved',
                'external_reference' => $externalReference,
                'notification_url' => route('webhook.mercadopago'),
                'metadata' => array_merge([
                    'user_id' => $user->id,
                    'product_type' => $productType,
                ], $metadata),
            ];

            $response = MercadoPago::preference()->create($preference);

            return [
                'success' => true,
                'init_point' => $response['body']['init_point'],
                'external_reference' => $externalReference,
                'preference_id' => $response['body']['id'],
            ];
        } catch (Exception $e) {
            \Log::error('Error creating MP payment preference', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo crear la preferencia de pago',
            ];
        }
    }

    /**
     * Crear plan de suscripción en Mercado Pago
     */
    public function createPlan(SubscriptionPlan $plan): array
    {
        try {
            // Verificar si el plan ya existe en MP
            if ($plan->mp_plan_id) {
                return [
                    'success' => true,
                    'plan_id' => $plan->mp_plan_id,
                ];
            }

            $mpPlan = [
                'reason' => $plan->name,
                'auto_recurring' => [
                    'frequency' => $plan->frequency,
                    'frequency_type' => $plan->frequency_type,
                    'transaction_amount' => $plan->amount,
                    'currency_id' => 'ARS',
                ],
            ];

            $response = MercadoPago::plan()->create($mpPlan);

            $planId = $response['body']['id'];

            // Guardar ID del plan en la BD
            $plan->update(['mp_plan_id' => $planId]);

            return [
                'success' => true,
                'plan_id' => $planId,
            ];
        } catch (Exception $e) {
            \Log::error('Error creating MP plan', [
                'error' => $e->getMessage(),
                'plan' => $plan->id,
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo crear el plan en Mercado Pago',
            ];
        }
    }

    /**
     * Crear preferencia de suscripción (preapproval)
     */
    public function createSubscriptionPreference(User $user, SubscriptionPlan $plan): array
    {
        try {
            // Crear plan si no existe
            $planResult = $this->createPlan($plan);
            if (!$planResult['success']) {
                return $planResult;
            }

            $externalReference = $this->generateExternalReference($user->id, 'subscription', $plan->id);

            $preference = [
                'reason' => $plan->name,
                'auto_recurring' => [
                    'frequency' => $plan->frequency,
                    'frequency_type' => $plan->frequency_type,
                    'transaction_amount' => $plan->amount,
                    'currency_id' => 'ARS',
                    'start_date' => now()->toIso8601String(),
                ],
                'payer' => [
                    'email' => $user->email,
                    'name' => $user->name,
                ],
                'back_urls' => [
                    'success' => route('subscription.success'),
                    'failure' => route('subscription.failure'),
                ],
                'external_reference' => $externalReference,
                'notification_url' => route('webhook.mercadopago'),
            ];

            $response = MercadoPago::preference()->create($preference);

            return [
                'success' => true,
                'init_point' => $response['body']['init_point'],
                'external_reference' => $externalReference,
                'preference_id' => $response['body']['id'],
            ];
        } catch (Exception $e) {
            \Log::error('Error creating MP subscription preference', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'plan_id' => $plan->id,
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo crear la preferencia de suscripción',
            ];
        }
    }

    /**
     * Obtener información de un pago
     */
    public function getPaymentInfo(string $paymentId): array
    {
        try {
            $response = MercadoPago::payment()->get($paymentId);

            return [
                'success' => true,
                'payment_id' => $response['body']['id'],
                'status' => $response['body']['status'],
                'status_detail' => $response['body']['status_detail'],
                'amount' => $response['body']['transaction_amount'],
                'external_reference' => $response['body']['external_reference'],
                'payer_email' => $response['body']['payer']['email'] ?? null,
                'raw' => $response['body'],
            ];
        } catch (Exception $e) {
            \Log::error('Error getting MP payment info', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId,
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo obtener la información del pago',
            ];
        }
    }

    /**
     * Obtener información de una preaprobación (suscripción)
     */
    public function getPreApprovalInfo(string $preApprovalId): array
    {
        try {
            $response = MercadoPago::preapproval()->get($preApprovalId);

            return [
                'success' => true,
                'preapproval_id' => $response['body']['id'],
                'status' => $response['body']['status'],
                'payer_email' => $response['body']['payer']['email'] ?? null,
                'plan_id' => $response['body']['plan_id'] ?? null,
                'reason' => $response['body']['reason'] ?? null,
                'next_payment_date' => $response['body']['next_payment_date'] ?? null,
                'raw' => $response['body'],
            ];
        } catch (Exception $e) {
            \Log::error('Error getting MP preapproval info', [
                'error' => $e->getMessage(),
                'preapproval_id' => $preApprovalId,
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo obtener la información de la suscripción',
            ];
        }
    }

    /**
     * Cancelar una preaprobación (suscripción)
     */
    public function cancelPreApproval(string $preApprovalId): array
    {
        try {
            MercadoPago::preapproval()->update($preApprovalId, [
                'status' => 'cancelled',
            ]);

            return [
                'success' => true,
                'message' => 'Suscripción cancelada exitosamente',
            ];
        } catch (Exception $e) {
            \Log::error('Error cancelling MP preapproval', [
                'error' => $e->getMessage(),
                'preapproval_id' => $preApprovalId,
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo cancelar la suscripción',
            ];
        }
    }

    /**
     * Generar referencia externa única
     */
    protected function generateExternalReference(int $userId, string $type, ?int $planId = null): string
    {
        $timestamp = now()->timestamp;
        $random = Str::random(8);

        return "{$type}_{$userId}_{$planId}_{$timestamp}_{$random}";
    }

    /**
     * Obtener título del producto para mostrar en MP
     */
    protected function getProductTitle(string $productType): string
    {
        return match ($productType) {
            'boost' => 'Boost de Perfil - 1 Hora',
            'super_likes' => 'Pack 5 Super Likes',
            'verification' => 'Verificación Express',
            'gift' => 'Regalo Virtual',
            default => 'Producto Big-Dad',
        };
    }

    /**
     * Obtener descripción del producto
     */
    protected function getProductDescription(string $productType): string
    {
        return match ($productType) {
            'boost' => 'Destaca tu perfil durante 1 hora',
            'super_likes' => 'Envía 5 super likes con notificación especial',
            'verification' => 'Verifica tu identidad en 24 horas',
            'gift' => 'Envía un regalo virtual',
            default => 'Compra en Big-Dad',
        };
    }
}
