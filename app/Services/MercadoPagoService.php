<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use WandesCardoso\MercadoPago\Facades\MercadoPago;
use Illuminate\Support\Facades\Log;
use WandesCardoso\MercadoPago\DTO\Preference;
use WandesCardoso\MercadoPago\DTO\Item;
use WandesCardoso\MercadoPago\DTO\Payer;
use WandesCardoso\MercadoPago\DTO\BackUrls;
use WandesCardoso\MercadoPago\Enums\Currency;
use Exception;

class MercadoPagoService
{

    protected string $accessToken;
    protected string $publicKey;
    protected string $env;
    protected string $currency;

    public function __construct()
    {
        $this->accessToken = config('services.mercadopago.access_token');
        $this->publicKey = config('services.mercadopago.public_key');
        $this->env = config('services.mercadopago.env', 'sandbox');
        $this->currency = config('services.mercadopago.currency', 'ARS');
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
                        'category_id' => 'services'
                    ]
                ],
                'back_urls' => [
                    'success' => route('purchase.success'),
                    'pending' => route('purchase.pending'),
                    'failure' => route('purchase.failure'),
                ],
                'auto_return' => 'approved',
                'metadata' => $metadata, // ✅ INCLUIR METADATA
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 201) {
                \Log::error('MP createPaymentPreference failed', [
                    'http_code' => $httpCode,
                    'response' => $response
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

        } catch (\Exception $e) {
            \Log::error('Error creating payment preference', [
                'error' => $e->getMessage()
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
                ]
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
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$headerData) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) return $len;
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
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                \Log::error('MP getPreApprovalInfo failed', [
                    'preapproval_id' => $preApprovalId,
                    'http_code' => $httpCode,
                    'response' => $response
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
                'raw' => $data
            ];

        } catch (\Exception $e) {
            \Log::error('Error getting preapproval info', [
                'preapproval_id' => $preApprovalId,
                'error' => $e->getMessage()
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
            $response = MercadoPago::request()->put("/preapproval/{$preApprovalId}", [
                'status' => 'cancelled',
            ]);

            return [
                'success' => true,
                'message' => 'Suscripción cancelada exitosamente',
            ];
        } catch (Exception $e) {
            \Log::error('Error cancelling preapproval', [
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
}