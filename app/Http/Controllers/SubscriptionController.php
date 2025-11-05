<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

        // Si ya tiene suscripción activa, pasar info
        $activeSubscription = $user->activeSubscription();

        return view('subscription.plans', [
            'plans' => $plans,
            'activeSubscription' => $activeSubscription,
            'userFeatures' => app(SubscriptionService::class)->getUserFeatures($user),
        ]);
    }

    /**
     * Crear checkout de Mercado Pago
     */
    public function createCheckout(SubscriptionPlan $plan)
    {
        $user = Auth::user();

        // Validar que no tenga suscripción activa (opcional, permitir upgrade)
        if ($user->activeSubscription() && $user->activeSubscription()->plan_id !== $plan->id) {
            return back()->with('warning', 'Ya tienes una suscripción activa. Cancélala primero.');
        }

        // Crear preferencia en Mercado Pago
        $preference = $this->mpService->createSubscriptionPreference($user, $plan);

        if (!$preference['success']) {
            return back()->with('error', $preference['error']);
        }

        // Redirigir a Mercado Pago
        return redirect($preference['init_point']);
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

}
