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
     * ✅ CREAR CHECKOUT DE MERCADO PAGO - DEVUELVE JSON
     */
    public function createCheckout(SubscriptionPlan $plan)
    {
        $user = Auth::user();
        $activeSubscription = $user->activeSubscription()->first();

        // Validar que no tenga suscripción activa de otro plan
        if ($activeSubscription && $activeSubscription->plan_id !== $plan->id) {
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

        // ✅ Crear preferencia en Mercado Pago
        Log::info('Creating MP preference', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'plan_amount' => $plan->amount,
        ]);

        $preference = $this->mpService->createSubscriptionPreference($user, $plan);

        if (!$preference['success']) {
            Log::error('Failed to create MP preference', [
                'error' => $preference['error'],
                'user_id' => $user->id,
                'plan_id' => $plan->id,
            ]);

            return response()->json([
                'success' => false,
                'error' => $preference['error'] ?? 'Error al crear la preferencia de pago'
            ], 400);
        }

        Log::info('Preference created successfully', [
            'preference_id' => $preference['preference_id'] ?? null,
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ]);

        // ✅ DEVOLVER JSON CON PREFERENCE_ID
        return response()->json([
            'success' => true,
            'preference_id' => $preference['preference_id'],
            'init_point' => $preference['init_point'] ?? $preference['sandbox_init_point'],
        ]);
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
            return redirect()->route('dashboard')->with('error', 'No se recibió información del pago');
        }

        // Obtener info del pago
        $paymentInfo = $this->mpService->getPaymentInfo($paymentId);

        if (!$paymentInfo['success']) {
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
        return redirect()->route('subscription.plans')
            ->with('error', 'El pago fue rechazado. Por favor intenta nuevamente.');
    }

    /**
     * Callback de pago pendiente
     */
    public function returnPending(Request $request)
    {
        return redirect()->route('dashboard')
            ->with('info', 'Tu pago está pendiente de aprobación. Te notificaremos en breve.');
    }

    /**
     * Cancelar suscripción del usuario
     */
    public function cancelSubscription(Request $request)
    {
        $user = Auth::user();

        $result = $this->subscriptionService->cancelSubscription($user);

        if ($result['success']) {
            return redirect()->route('dashboard')->with('success', $result['message']);
        }

        return back()->with('error', $result['error']);
    }

    /**
     * Ver detalles de una suscripción
     */
    public function show(Subscription $subscription)
    {
        // Verificar que el usuario es el dueño
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta suscripción');
        }

        return view('subscription.show', [
            'subscription' => $subscription,
        ]);
    }

    private function formatFeatureNames(array $features): array
    {
        return array_map(function ($feature) {
            // Convertir snake_case a Title Case
            return str_replace('_', ' ', ucwords(str_replace('_', ' ', $feature)));
        }, $features);
    }


}