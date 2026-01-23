<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\PaymentAuditLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class MercadoPagoService
{
    protected ?string $accessToken = null;
    protected ?string $publicKey = null;
    protected ?string $env = null;
    protected ?string $currency = null;
    protected ?string $apiUrl = null;

    public function __construct()
    {
        // Diagnóstico: versión v2 - forzando strings
        $this->accessToken = (string) (config('services.mercadopago.access_token') ?? '');
        $this->publicKey = (string) (config('services.mercadopago.public_key') ?? '');
        $this->env = (string) (config('services.mercadopago.env') ?? 'sandbox');
        $this->currency = config('services.mercadopago.currency', 'ARS');
        $this->apiUrl = config('services.mercadopago.api_url', 'https://api.mercadopago.com');

        if (empty($this->accessToken)) {
            Log::error('MercadoPagoService: Access Token is NULL or EMPTY', [
                'config_val' => config('services.mercadopago.access_token'),
                'all_mp_config' => config('services.mercadopago'),
            ]);
        }
    }

    /**
     * Convertir stdClass a array recursivamente
     */
    private function objectToArray($obj)
    {
        if (is_object($obj) || is_array($obj)) {
            return json_decode(json_encode($obj), true);
        }
        return $obj;
    }

    /**
     * Obtener valor seguro de respuesta - busca en múltiples ubicaciones
     */
    private function getResponseValue($response, $keys)
    {
        $arr = $this->objectToArray($response);

        foreach ($keys as $key) {
            // Buscar directo
            if (isset($arr[$key])) {
                return $arr[$key];
            }

            // Buscar en body
            if (isset($arr['body'][$key])) {
                return $arr['body'][$key];
            }
        }
        return null;
    }

    /**
     * Crear preferencia de pago para compra única (boost, super likes, etc.)
     */
    public function createPaymentPreference(User $user, string $productType, float $amount, array $metadata = []): array
    {
        try {
            $externalReference = $this->generateExternalReference($user->id, $productType);

            // ✅ AGREGAR product_type a metadata para identificar en webhook
            $metadata['product_type'] = $productType;
            $metadata['user_id'] = $user->id;

            // Usar cURL directo para evitar PolicyAgent
            $url = 'https://api.mercadopago.com/checkout/preferences';

            $preferenceData = [
                'external_reference' => $externalReference,
                'payer' => [
                    'email' => $user->email,
                    'name' => $user->name,
                ],
                'items' => [
                    [
                        'title' => $this->getProductTitle($productType),
                        'description' => $this->getProductDescription($productType),
                        'quantity' => 1,
                        'unit_price' => $amount,
                        'currency_id' => $this->currency,
                        'category_id' => 'services',
                    ],
                ],
                'back_urls' => [
                    'success' => route('purchase.success'),
                    'pending' => route('purchase.pending'),
                    'failure' => route('purchase.failure'),
                ],
                'auto_return' => 'approved',
                'metadata' => $metadata, // ✅ INCLUIR METADATA
                'notification_url' => route('webhook.mercadopago'),
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 201) {
                Log::error('MP createPaymentPreference failed', [
                    'http_code' => $httpCode,
                    'response' => $response,
                ]);
                return ['success' => false, 'error' => 'Failed to create preference'];
            }

            $data = json_decode($response, true);

            return [
                'success' => true,
                'preference_id' => $data['id'] ?? null,
                'init_point' => $data['init_point'] ?? null,
                'sandbox_init_point' => $data['sandbox_init_point'] ?? null,
            ];
        } catch (Exception $e) {
            Log::error('Error creating payment preference', [
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Crear preferencia de pago para suscripción
     */
    public function createSubscriptionPreference($user, SubscriptionPlan $plan)
    {
        Log::info('Creating MP preference', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'plan_amount' => $plan->amount,
        ]);

        $payload = [
            'items' => [
                [
                    'title' => $plan->name . ' - Suscripción Big-Dad',
                    'quantity' => 1,
                    'unit_price' => (float) $plan->amount,
                ],
            ],
            'back_urls' => [
                'success' => route('subscription.success'),
                'failure' => route('subscription.failure'),
                'pending' => route('subscription.pending'),
            ],
            'auto_return' => 'approved',
            'external_reference' => 'USER_' . $user->id . '_PLAN_' . $plan->id . '_' . time(),
            'notification_url' => route('webhook.mercadopago'),
        ];

        // ✅ LOGUEAR EL PAYLOAD COMPLETO
        Log::debug('MP Request Payload', [
            'payload' => json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        ]);

        // Realizar request a Mercado Pago
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/checkout/preferences');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        // ✅ CAPTURAR HEADERS DE LA RESPUESTA
        $headerData = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$headerData) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) {
                return $len;
            }
            $name = strtolower(trim($header[0]));
            $value = trim($header[1]);
            $headerData[$name] = $value;
            return $len;
        });

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // ✅ LOGUEAR HEADERS COMPLETOS
        Log::debug('MP Response Headers', [
            'x-request-id' => $headerData['x-request-id'] ?? 'N/A',
            'x-idempotency-key' => $headerData['x-idempotency-key'] ?? 'N/A',
            'date' => $headerData['date'] ?? 'N/A',
            'all-headers' => $headerData,
        ]);

        Log::debug('MP Preference Response', [
            'status' => $httpCode,
            'response' => $response,
            'curl_error' => $curlError,
        ]);

        $responseArray = json_decode($response, true);

        if ($httpCode !== 201) {
            // ✅ LOGUEAR ERROR CON X-REQUEST-ID Y PAYLOAD
            Log::error('Preference creation failed', [
                'status' => $httpCode,
                'x-request-id' => $headerData['x-request-id'] ?? 'N/A',
                'payload_sent' => json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                'response' => $responseArray,
            ]);

            return [
                'success' => false,
                'error' => $responseArray['message'] ?? 'Error desconocido',
                'status' => $httpCode,
                'x-request-id' => $headerData['x-request-id'] ?? 'N/A',
            ];
        }

        Log::info('Preference created successfully', [
            'preference_id' => $responseArray['id'] ?? null,
            'x-request-id' => $headerData['x-request-id'] ?? 'N/A',
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ]);

        return [
            'success' => true,
            'preference_id' => $responseArray['id'],
            'init_point' => $responseArray['init_point'],
            'sandbox_init_point' => $responseArray['sandbox_init_point'] ?? $responseArray['init_point'],
            'x-request-id' => $headerData['x-request-id'] ?? 'N/A',
        ];
    }

    /**
     * Obtener información de un pago
     */
    public function getPaymentInfo($paymentId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/v1/payments/' . $paymentId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseArray = json_decode($response, true);

        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => $responseArray['message'] ?? 'Error al obtener pago',
            ];
        }

        return [
            'success' => true,
            'status' => $responseArray['status'],
            'payment_data' => $responseArray,
        ];
    }

    /**
     * Obtener información de una pre-aprobación (suscripción)
     */
    public function getPreApprovalInfo(string $preApprovalId): array
    {
        try {
            $url = "https://api.mercadopago.com/preapproval/{$preApprovalId}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::error('MP getPreApprovalInfo failed', [
                    'preapproval_id' => $preApprovalId,
                    'http_code' => $httpCode,
                    'response' => $response,
                ]);
                return ['success' => false, 'error' => 'Failed to get preapproval info'];
            }

            $data = json_decode($response, true);

            return [
                'success' => true,
                'preapproval_id' => $data['id'] ?? null,
                'status' => $data['status'] ?? 'unknown',
                'external_reference' => $data['external_reference'] ?? null,
                'auto_recurring' => $data['auto_recurring'] ?? [],
                'payer_email' => $data['payer_email'] ?? null,
                'raw' => $data,
            ];
        } catch (Exception $e) {
            Log::error('Error getting preapproval info', [
                'preapproval_id' => $preApprovalId,
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Cancelar una preaprobación (suscripción)
     */
    public function cancelPreApproval(string $preApprovalId): array
    {
        try {
            $url = $this->apiUrl . "/preapproval/{$preApprovalId}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['status' => 'cancelled']));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Revisar si fue exitoso
            if ($httpCode !== 200) {
                Log::error('MP cancelPreApproval failed', [
                    'preapproval_id' => $preApprovalId,
                    'http_code' => $httpCode,
                    'response' => $response,
                    'curl_error' => $curlError,
                ]);

                return [
                    'success' => false,
                    'error' => 'No se pudo cancelar la suscripción',
                    'http_code' => $httpCode,
                ];
            }

            Log::info('Preapproval cancelled successfully', [
                'preapproval_id' => $preApprovalId,
                'http_code' => $httpCode,
            ]);

            return [
                'success' => true,
                'message' => 'Suscripción cancelada exitosamente',
                'preapproval_id' => $preApprovalId,
            ];
        } catch (Exception $e) {
            Log::error('Error cancelling preapproval', [
                'error' => $e->getMessage(),
                'preapproval_id' => $preApprovalId,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
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

        if ($planId) {
            return "{$type}_{$userId}_{$planId}_{$timestamp}_{$random}";
        }

        return "{$type}_{$userId}_{$timestamp}_{$random}";
    }

    /**
     * Obtener título del producto
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

    /* ============================================================
     * =======================  WEBHOOKS  ==========================
     * ============================================================
     */

    /**
     * Procesar webhook de Mercado Pago (entry point desde el controller)
     */
    public function processWebhook(array $payload): bool
    {
        try {
            $type = $payload['type'] ?? null;
            $dataId = $payload['data']['id'] ?? null;

            Log::info('Processing MP webhook', [
                'type' => $type,
                'data_id' => $dataId,
            ]);

            PaymentAuditLog::create([
                'action' => 'webhook_received',
                'status' => 'success',
                'mercado_pago_event_id' => $payload['id'] ?? null,
                'payload' => $payload,
            ]);

            return match ($type) {
                'payment' => $this->handlePaymentWebhook($dataId),
                'subscription_preapproval' => $this->handleSubscriptionWebhook($dataId),
                default => $this->handleUnknownWebhook($type, $payload),
            };
        } catch (Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            PaymentAuditLog::create([
                'action' => 'webhook_received',
                'status' => 'error',
                'error_message' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return false;
        }
    }

    /**
     * Manejar webhook de pago
     */
    protected function handlePaymentWebhook(string $paymentId): bool
    {
        $paymentInfo = $this->getPaymentInfo($paymentId);

        if (!$paymentInfo['success']) {
            Log::error('Failed to get payment info from MP', ['payment_id' => $paymentId]);
            return false;
        }

        $paymentData = $paymentInfo['payment_data'];
        $status = $paymentData['status'];
        $externalReference = $paymentData['external_reference'] ?? null;
        $metadata = $paymentData['metadata'] ?? [];

        Log::info('Payment webhook data', [
            'payment_id' => $paymentId,
            'status' => $status,
            'external_reference' => $externalReference,
            'metadata' => $metadata,
        ]);

        $transaction = Transaction::where('mp_payment_id', $paymentId)->first();

        if (!$transaction) {
            $userId = $metadata['user_id'] ?? $this->extractUserIdFromReference($externalReference);
            $productType = $metadata['product_type'] ?? $this->extractProductTypeFromReference($externalReference);

            if (!$userId) {
                Log::warning('Could not identify user from webhook', [
                    'payment_id' => $paymentId,
                    'external_reference' => $externalReference,
                ]);
                return false;
            }

            $transaction = Transaction::create([
                'user_id' => $userId,
                'mp_payment_id' => $paymentId,
                'type' => 'payment',
                'amount' => $paymentData['transaction_amount'] ?? 0,
                'currency' => $paymentData['currency_id'] ?? 'CLP',
                'status' => $status,
                'description' => $productType,
                'external_reference' => $externalReference,
                'metadata' => $metadata,
            ]);
        } else {
            $transaction->update(['status' => $status]);
        }

        if ($status === 'approved') {
            $transaction->update(['approved_at' => now()]);
            return $this->fulfillPayment($transaction);
        }

        return true;
    }

    /**
     * Otorgar beneficio al usuario según el producto
     */
    protected function fulfillPayment(Transaction $transaction): bool
    {
        $user = $transaction->user;
        $metadata = $transaction->metadata ?? [];
        $productType = $transaction->description ?? ($metadata['product_type'] ?? null);

        Log::info('Fulfilling payment', [
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'product_type' => $productType,
        ]);

        try {
            $fulfilled = match ($productType) {
                'boost', 'profile_boost_7days' => $this->grantBoost($user, 7),
                'profile_boost_30days' => $this->grantBoost($user, 30),
                'super_like', 'super_likes' => $this->grantSuperLikes($user, 5),
                'super_like_pack_10' => $this->grantSuperLikes($user, 10),
                'verification' => $this->grantVerification($user),
                default => $this->handleUnknownProduct($user, $productType),
            };

            if ($fulfilled) {
                PaymentAuditLog::create([
                    'user_id' => $user->id,
                    'transaction_id' => $transaction->id,
                    'action' => 'payment_fulfilled',
                    'status' => 'success',
                    'payload' => ['product_type' => $productType],
                ]);
            }

            return $fulfilled;
        } catch (Exception $e) {
            Log::error('Failed to fulfill payment', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Otorgar boost de perfil
     */
    public function grantBoost(User $user, int $days): bool
    {
        return $user->update([
            'profile_boost_active' => true,
            'boost_until' => now()->addDays($days),
            'boost_count' => $user->boost_count + 1,
        ]);
    }

    /**
     * Otorgar super likes
     */
    public function grantSuperLikes(User $user, int $quantity): bool
    {
        return $user->increment('super_likes_remaining', $quantity) > 0;
    }

    /**
     * Otorgar verificación express
     */
    public function grantVerification(User $user): bool
    {
        if (!\Schema::hasColumn('users', 'verified_at')) {
            // Por si no existe esa columna aún
            return $user->update(['is_verified' => true]);
        }

        return $user->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }

    /**
     * Manejar producto desconocido
     */
    protected function handleUnknownProduct(User $user, ?string $productType): bool
    {
        Log::warning('Unknown product type in fulfillment', [
            'user_id' => $user->id,
            'product_type' => $productType,
        ]);
        return false;
    }

    /**
     * Manejar webhook de suscripción
     */
    public function handleSubscriptionWebhook(string $preapprovalId): bool
    {
        $info = $this->getPreApprovalInfo($preapprovalId);

        if (!$info['success']) {
            return false;
        }

        $status = $info['status'];
        $externalReference = $info['external_reference'];

        $subscription = Subscription::where('mp_preapproval_id', $preapprovalId)->first();

        if (!$subscription) {
            Log::warning('Subscription not found for preapproval', [
                'preapproval_id' => $preapprovalId,
            ]);
            return false;
        }

        $subscription->update(['status' => $status]);

        if (in_array($status, ['authorized', 'active'])) {
            $user = $subscription->user;

            $user->update([
                'is_premium' => true,
                'premium_until' => now()->addMonth(),
            ]);

            $subscription->update([
                'ends_at' => now()->addMonth(),
                'next_billing_date' => now()->addMonth(),
            ]);

            Log::info('Subscription renewed', [
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
            ]);
        }

        return true;
    }

    /**
     * Manejar webhook desconocido
     */
    protected function handleUnknownWebhook(?string $type, array $payload): bool
    {
        Log::info('Unknown webhook type received', [
            'type' => $type,
            'payload' => $payload,
        ]);
        return true;
    }

    /**
     * Extraer user_id de external_reference
     * Formatos:
     * - {type}_{userId}_{timestamp}_{random}
     * - USER_{userId}_PLAN_{planId}_{timestamp}
     */
    protected function extractUserIdFromReference(?string $reference): ?int
    {
        if (!$reference) {
            return null;
        }

        if (str_starts_with($reference, 'USER_')) {
            $parts = explode('_', $reference);
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                return (int) $parts[1];
            }
        }

        $parts = explode('_', $reference);
        if (count($parts) >= 2 && is_numeric($parts[1])) {
            return (int) $parts[1];
        }

        return null;
    }

    /**
     * Extraer product_type de external_reference
     */
    protected function extractProductTypeFromReference(?string $reference): ?string
    {
        if (!$reference) {
            return null;
        }

        $parts = explode('_', $reference);
        return $parts[0] ?? null;
    }

    /* ============================================================
     * =======================  REEMBOLSOS  ========================
     * ============================================================
     */

    /**
     * Procesar reembolso en Mercado Pago
     */
    public function processRefund(string $mpPaymentId, ?float $amount = null): array
    {
        try {
            $url = "https://api.mercadopago.com/v1/payments/{$mpPaymentId}/refunds";

            $payload = $amount ? ['amount' => $amount] : [];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 201 && $httpCode !== 200) {
                Log::error('MP refund failed', [
                    'payment_id' => $mpPaymentId,
                    'http_code' => $httpCode,
                    'response' => $response,
                ]);
                return ['success' => false, 'error' => 'Refund failed'];
            }

            $data = json_decode($response, true);

            Log::info('Refund processed', [
                'payment_id' => $mpPaymentId,
                'refund_id' => $data['id'] ?? null,
                'amount' => $data['amount'] ?? $amount,
            ]);

            return [
                'success' => true,
                'refund_id' => $data['id'] ?? null,
                'amount' => $data['amount'] ?? $amount,
                'status' => $data['status'] ?? 'approved',
            ];
        } catch (Exception $e) {
            Log::error('Error processing refund', [
                'payment_id' => $mpPaymentId,
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
