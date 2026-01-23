<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Transaction;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    protected MercadoPagoService $mpService;

    // Precios de productos (en ARS)
    private $productPrices = [
        'boost' => 4.99,
        'super_likes' => 1.99,
        'verification' => 9.99,
        'gift' => 2.99,
    ];

    public function __construct(MercadoPagoService $mpService)
    {
        $this->mpService = $mpService;
    }

    /**
     * Comprar Boost de perfil
     * POST /purchase/boost
     */
    public function buyBoost(Request $request)
    {
        return $this->processPurchase($request, 'boost', 1);
    }

    /**
     * Comprar Super Likes
     * POST /purchase/super-likes
     */
    public function buySuperLikes(Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        return $this->processPurchase($request, 'super_likes', $validated['quantity']);
    }

    /**
     * Comprar Verificación express
     * POST /purchase/verification
     */
    public function buyVerification(Request $request)
    {
        return $this->processPurchase($request, 'verification', 1);
    }

    /**
     * Comprar regalo para otro usuario
     * POST /purchase/gift/{recipient}
     */
    public function buyGift(Request $request, $recipientId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        return $this->processPurchase($request, 'gift', $request->quantity, $recipientId);
    }

    /**
     * Procesar compra genérica
     */
    private function processPurchase(Request $request, string $productType, int $quantity, ?int $recipientId = null)
    {
        try {
            $user = Auth::user();

            // Validar que el producto existe
            if (!isset($this->productPrices[$productType])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Producto no válido',
                ], 400);
            }

            // Calcular monto
            $unitPrice = $this->productPrices[$productType];
            $amount = $unitPrice * $quantity;

            Log::info('Processing purchase', [
                'user_id' => $user->id,
                'product_type' => $productType,
                'quantity' => $quantity,
                'amount' => $amount,
                'recipient_id' => $recipientId,
            ]);

            // Crear compra con status "pending"
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'product_type' => $productType,
                'quantity' => $quantity,
                'amount' => $amount,
                'currency' => config('services.mercadopago.currency', 'ARS'),
                'status' => 'pending',
                'recipient_id' => $recipientId,
                'metadata' => [
                    'recipient_id' => $recipientId,
                    'days' => ($productType === 'boost' ? 7 : null),
                    'likes_count' => ($productType === 'super_likes' ? $quantity * 5 : null),
                ]
            ]);

            // Integrar con Mercado Pago
            $mpResponse = $this->mpService->createPaymentPreference(
                $user,
                $productType,
                $amount,
                [
                    'purchase_id' => $purchase->id,
                    'quantity' => $quantity,
                    'recipient_id' => $recipientId,
                ]
            );

            if (!$mpResponse['success']) {
                Log::error('Failed to create MP preference for purchase', [
                    'purchase_id' => $purchase->id,
                    'error' => $mpResponse['error'] ?? 'Unknown error',
                ]);

                $purchase->delete();

                return response()->json([
                    'success' => false,
                    'error' => 'Error al integrar con Mercado Pago: ' . ($mpResponse['error'] ?? 'Error desconocido')
                ], 400);
            }

            // Guardar preference_id y init_point
            $purchase->update([
                'mp_preference_id' => $mpResponse['preference_id'],
            ]);

            Log::info('Purchase created successfully', [
                'purchase_id' => $purchase->id,
                'preference_id' => $mpResponse['preference_id'],
            ]);

            return response()->json([
                'success' => true,
                'data' => $purchase,
                'checkout_url' => $mpResponse['sandbox_init_point'] ?? $mpResponse['init_point'],
                'message' => 'Compra iniciada. Completa el pago en Mercado Pago.',
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error processing purchase', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al procesar la compra',
            ], 500);
        }
    }

    /**
     * Callback de éxito en Mercado Pago
     */
    public function returnSuccess(Request $request)
    {
        $user = Auth::user();
        $paymentId = $request->query('payment_id');

        if (!$paymentId) {
            Log::warning('Purchase returnSuccess called without payment_id', [
                'user_id' => $user->id,
            ]);
            return redirect()->route('dashboard')->with('error', 'No se recibió información del pago');
        }

        Log::info('User returned from MP purchase success', [
            'user_id' => $user->id,
            'payment_id' => $paymentId,
        ]);

        return redirect()->route('dashboard')->with('success', '¡Compra procesada! El pago está siendo verificado.');
    }

    /**
     * Callback de fallo en Mercado Pago
     */
    public function returnFailure(Request $request)
    {
        $user = Auth::user();

        Log::warning('Purchase payment failed', [
            'user_id' => $user->id,
            'query_params' => $request->query()
        ]);

        return redirect()->route('discover.index')
            ->with('error', 'El pago fue rechazado. Por favor intenta nuevamente.');
    }

    /**
     * Callback de pago pendiente
     */
    public function returnPending(Request $request)
    {
        $user = Auth::user();

        Log::info('Purchase payment pending', [
            'user_id' => $user->id,
        ]);

        return redirect()->route('dashboard')
            ->with('info', 'Tu pago está pendiente de aprobación. Te notificaremos pronto.');
    }

    // ============= API ENDPOINTS =============

    /**
     * API: Crear compra
     * POST /api/purchases
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_type' => 'required|in:boost,super_likes,verification,gift',
                'quantity' => 'required|integer|min:1|max:100',
                'recipient_id' => 'nullable|exists:users,id',
            ]);

            $user = Auth::guard('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 401);
            }

            $productType = $validated['product_type'];
            $quantity = $validated['quantity'];
            $recipientId = $validated['recipient_id'] ?? null;

            // Validar que el producto existe
            if (!isset($this->productPrices[$productType])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no válido',
                ], 400);
            }

            // Calcular monto
            $unitPrice = $this->productPrices[$productType];
            $amount = $unitPrice * $quantity;

            // Crear compra
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'product_type' => $productType,
                'quantity' => $quantity,
                'amount' => $amount,
                'currency' => config('services.mercadopago.currency', 'ARS'),
                'status' => 'pending',
                'recipient_id' => $recipientId,
            ]);

            // Integrar con Mercado Pago
            $mpResponse = $this->mpService->createPaymentPreference(
                $user,
                $productType,
                $amount,
                [
                    'purchase_id' => $purchase->id,
                    'quantity' => $quantity,
                ]
            );

            if (!$mpResponse['success']) {
                $purchase->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Error al integrar con Mercado Pago'
                ], 400);
            }

            $purchase->update([
                'mp_preference_id' => $mpResponse['preference_id'],
            ]);

            return response()->json([
                'success' => true,
                'data' => $purchase,
                'checkout_url' => $mpResponse['sandbox_init_point'] ?? $mpResponse['init_point'],
                'message' => 'Compra iniciada. Completa el pago en Mercado Pago.',
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error in apiStore', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la compra'
            ], 500);
        }
    }

    /**
     * API: Listar mis compras
     * GET /api/purchases
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

            $purchases = $user->purchases()
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $purchases->items(),
                'pagination' => [
                    'total' => $purchases->total(),
                    'current_page' => $purchases->currentPage(),
                    'last_page' => $purchases->lastPage(),
                    'per_page' => $purchases->perPage(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in apiIndex', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener compras'
            ], 500);
        }
    }

    /**
     * API: Ver detalle de compra
     * GET /api/purchases/{id}
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

            $purchase = Purchase::find($id);

            if (!$purchase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Compra no encontrada'
                ], 404);
            }

            if ($purchase->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $purchase,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in apiShow', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la compra'
            ], 500);
        }
    }
}