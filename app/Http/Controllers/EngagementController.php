<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EngagementController extends Controller
{
    /**
     * Handle email interaction tracking
     * Route: /e/{token}
     */
    public function track(Request $request, string $token)
    {
        try {
            // El token contiene el ID del usuario encriptado
            $userId = Crypt::decryptString($token);
            $user = User::find($userId);

            if ($user) {
                // Actualizar métricas de engagement
                $user->update([
                    'last_email_interaction_at' => now(),
                    'engagement_score' => $user->engagement_score + 10,
                ]);

                // Registrar evento si es necesario (ej: en logs)
                logger()->info("User engagement tracked: #{$user->id} via email.");
            }

            // Redirigir al dashboard (o ruta específica si el token incluyera un destino)
            return redirect()->route('dashboard')->with('success', '¡Bienvenido de vuelta!');
        } catch (\Exception $e) {
            logger()->error('Engagement tracking error: '.$e->getMessage());

            return redirect()->route('login');
        }
    }
}
