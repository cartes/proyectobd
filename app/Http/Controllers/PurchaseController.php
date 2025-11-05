<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
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
     * Comprar boost de perfil
     */
    public function buyBoost(Request $request)
    {
        $user = Auth::user();

        // Crear preferencia de pago
        $preference = $this->mpService->createPaymentPreference(
            $user,
            'boost',
            2.99, // precio del boost
            ['duration' => '1_hour']
        );

        if (!$preference['success']) {
            return back()->with('error', $preference['error']);
        }

        return redirect($preference['init_point']);
    }

    /**
     * Comprar super likes
     */
    public function buySuperLikes(Request $request)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity', 5); // cantidad de super likes

        // Calcular precio (ejemplo: $0.50 por super like)
        $amount = $quantity * 0.50;

        $preference = $this->mpService->createPaymentPreference(
            $user,
            'super_likes',
            $amount,
            ['quantity' => $quantity]
        );

        if (!$preference['success']) {
            return back()->with('error', $preference['error']);
        }

        return redirect($preference['init_point']);
    }

    /**
     * Comprar verificación express
     */
    public function buyVerification(Request $request)
    {
        $user = Auth::user();

        if ($user->is_verified) {
            return back()->with('info', 'Ya estás verificado');
        }

        $preference = $this->mpService->createPaymentPreference(
            $user,
            'verification',
            4.99
        );

        if (!$preference['success']) {
            return back()->with('error', $preference['error']);
        }

        return redirect($preference['init_point']);
    }

    /**
     * Enviar regalo virtual
     */
    public function buyGift(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'gift_type' => 'required|in:rose,champagne,diamond,love',
        ]);

        $user = Auth::user();
        $recipient = \App\Models\User::find($request->recipient_id);

        // Precios de regalos
        $giftPrices = [
            'rose' => 0.99,
            'champagne' => 2.99,
            'diamond' => 9.99,
            'love' => 4.99,
        ];

        $amount = $giftPrices[$request->gift_type];

        $preference = $this->mpService->createPaymentPreference(
            $user,
            'gift',
            $amount,
            [
                'recipient_id' => $recipient->id,
                'gift_type' => $request->gift_type,
                'message' => $request->input('message', ''),
            ]
        );

        if (!$preference['success']) {
            return back()->with('error', $preference['error']);
        }

        return redirect($preference['init_point']);
    }

    /**
     * Callback de éxito en compra
     */
    public function returnSuccess(Request $request)
    {
        return redirect()->route('dashboard')->with('success', '¡Compra realizada exitosamente!');
    }

    /**
     * Callback de fallo en compra
     */
    public function returnFailure(Request $request)
    {
        return redirect()->route('dashboard')->with('error', 'La compra fue rechazada');
    }

    /**
     * Callback de compra pendiente
     */
    public function returnPending(Request $request)
    {
        return redirect()->route('dashboard')->with('info', 'Tu compra está siendo procesada');
    }
}
