<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
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
     * Mostrar planes disponibles
     */
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();

        // Formatear features de cada plan
        $plans = $plans->map(function ($plan) {
            $plan->features = $this->formatFeatureNames($plan->features);
            return $plan;
        });

        $user = Auth::user();

        // Si ya tiene suscripción activa, pasar info
        $activeSubscription = $user->activeSubscription()->first();

        return view('subscription.plans', [
            'plans' => $plans,
            'activeSubscription' => $activeSubscription,
            'userFeatures' => app(SubscriptionService::class)->getUserFeatures($user),
        ]);
    }

    /**
     * ✅ CREAR CHECKOUT DE MERCADO PAGO - MEJORADO
     * 
     * Ahora con mejor manejo de errores y validaciones
     */
    public function createCheckout(SubscriptionPlan $plan)
    {
        try {
            $user = Auth::user();

            // Validar que el plan existe
            if (!$plan->is_active) {
                return response()->json([
                    'success' => false,
                    'error' => 'Este plan no está disponible'
                ], 400);
            }

            $activeSubscription = $user->activeSubscription()->first();

            // Validar que no tenga suscripción activa de otro plan
            if ($activeSubscription && $activeSubscription->plan_id !== $plan->id) {
                Log::warning('User tried to subscribe with active subscription', [
                    'user_id' => $user->id,
                    'active_plan_id' => $activeSubscription->plan_id,
                    'requested_plan_id' => $plan->id,
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Ya tienes una suscripción activa. Cancélala primero antes de cambiar de plan.'
                ], 400);
            }

            // Evitar duplicados del mismo plan
            if ($activeSubscription && $activeSubscription->plan_id === $plan->id) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ya tienes este plan activo.'
                ], 400);
            }

            // Crear preferencia en Mercado Pago
            Log::info('Creating MP preference', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'plan_amount' => $plan->amount,
            ]);

            $preference = $this->mpService->createSubscriptionPreference($user, $plan);

            if (!$preference['success']) {
                Log::error('Failed to create MP preference', [
                    'error' => $preference['error'] ?? 'Unknown error',
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                ]);

                return response()->json([
                    'success' => false,
                    'error' => $preference['error'] ?? 'Error al crear la preferencia de pago',
                    'code' => $preference['status'] ?? 'unknown'
                ], 400);
            }

            Log::info('Preference created successfully', [
                'preference_id' => $preference['preference_id'] ?? null,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
            ]);

            // Retornar JSON con preference_id
            return response()->json([
                'success' => true,
                'preference_id' => $preference['preference_id'],
                'init_point' => $preference['init_point'] ?? $preference['sandbox_init_point'],
                'plan' => [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'amount' => $plan->amount,
                    'currency' => config('services.mercadopago.currency', 'ARS'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in createCheckout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error interno al procesar el pago'
            ], 500);
        }
    }

    /**
     * Callback de éxito en Mercado Pago
     */
    public function returnSuccess(Request $request)
    {
        $user = Auth::user();

        // Mercado Pago retorna el payment_id en la query string
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');

        if (!$paymentId) {
            Log::warning('returnSuccess called without payment_id', [
                'user_id' => $user->id,
                'query_params' => $request->query()
            ]);
            return redirect()->route('dashboard')->with('error', 'No se recibió información del pago');
        }

        Log::info('User returned from MP success', [
            'user_id' => $user->id,
            'payment_id' => $paymentId,
            'status' => $status,
        ]);

        // Obtener info del pago
        $paymentInfo = $this->mpService->getPaymentInfo($paymentId);

        if (!$paymentInfo['success']) {
            Log::error('Failed to get payment info', [
                'user_id' => $user->id,
                'payment_id' => $paymentId,
            ]);
            return redirect()->route('dashboard')->with('error', 'No se pudo verificar el pago');
        }

        // El webhook debería procesar esto, pero por si acaso lo procesamos acá también
        if ($paymentInfo['status'] === 'approved') {
            return redirect()->route('dashboard')->with('success', '¡Suscripción activada exitosamente!');
        }

        return redirect()->route('dashboard')->with('info', 'Tu pago está siendo procesado. Te notificaremos pronto.');
    }

    /**
     * Callback de fallo en Mercado Pago
     */
    public function returnFailure(Request $request)
    {
        $user = Auth::user();

        Log::warning('Payment failed', [
            'user_id' => $user->id,
            'query_params' => $request->query()
        ]);

        return redirect()->route('subscription.plans')
            ->with('error', 'El pago fue rechazado. Por favor intenta nuevamente.');
    }

    /**
     * Callback de pago pendiente
     */
    public function returnPending(Request $request)
    {
        $user = Auth::user();

        Log::info('Payment pending', [
            'user_id' => $user->id,
            'query_params' => $request->query()
        ]);

        return redirect()->route('dashboard')
            ->with('info', 'Tu pago está pendiente de aprobación. Te notificaremos en breve.');
    }

    /**
     * Cancelar suscripción del usuario
     */
    public function cancelSubscription(Request $request)
    {
        try {
            $user = Auth::user();

            Log::info('Attempting to cancel subscription', [
                'user_id' => $user->id,
            ]);

            $result = $this->subscriptionService->cancelSubscription($user);

            if ($result['success']) {
                Log::info('Subscription cancelled', [
                    'user_id' => $user->id,
                ]);
                return redirect()->route('dashboard')->with('success', $result['message']);
            }

            Log::error('Failed to cancel subscription', [
                'user_id' => $user->id,
                'error' => $result['error'],
            ]);
            return back()->with('error', $result['error']);

        } catch (\Exception $e) {
            Log::error('Error in cancelSubscription', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Error al cancelar la suscripción');
        }
    }

    /**
     * Ver detalles de una suscripción
     */
    public function show(Subscription $subscription)
    {
        // Verificar que el usuario es el dueño
        if ($subscription->user_id !== Auth::id()) {
            Log::warning('User tried to view subscription they don\'t own', [
                'user_id' => Auth::id(),
                'subscription_id' => $subscription->id,
                'subscription_user_id' => $subscription->user_id,
            ]);
            abort(403, 'No tienes permiso para ver esta suscripción');
        }

        return view('subscription.show', [
            'subscription' => $subscription,
        ]);
    }

    /**
     * API: Listar suscripciones del usuario
     * GET /api/subscriptions
     */
    public function apiIndex()
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 401);
            }

            $subscriptions = $user->subscriptions()
                ->with('plan')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $subscriptions,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in apiIndex', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener suscripciones'
            ], 500);
        }
    }

    /**
     * API: Ver detalle de suscripción
     * GET /api/subscriptions/{id}
     */
    public function apiShow($id)
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 401);
            }

            $subscription = Subscription::with('plan')
                ->find($id);

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Suscripción no encontrada'
                ], 404);
            }

            if ($subscription->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $subscription,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in apiShow', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la suscripción'
            ], 500);
        }
    }

    /**
     * API: Cancelar suscripción
     * DELETE /api/subscriptions/{id}
     */
    public function apiDestroy($id)
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 401);
            }

            $subscription = Subscription::find($id);

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Suscripción no encontrada'
                ], 404);
            }

            if ($subscription->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }

            // Si tiene preference_id en MP, intentar cancelar
            if ($subscription->mp_preference_id) {
                $mpResponse = $this->mpService->cancelPreApproval($subscription->mp_preference_id);

                if ($mpResponse['success']) {
                    Log::info('Subscription cancelled in MP', [
                        'subscription_id' => $subscription->id,
                    ]);
                }
            }

            // Cancelar en BD
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            Log::info('Subscription cancelled via API', [
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Suscripción cancelada exitosamente',
                'data' => $subscription,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in apiDestroy', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la suscripción'
            ], 500);
        }
    }

    /**
     * Formatear nombres de features
     */
    private function formatFeatureNames(array $features): array
    {
        return array_map(function ($feature) {
            // Convertir snake_case a Title Case
            return str_replace('_', ' ', ucwords(str_replace('_', ' ', $feature)));
        }, $features);
    }
}
