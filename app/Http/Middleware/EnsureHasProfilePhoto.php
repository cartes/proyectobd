<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasProfilePhoto
{
    /**
     * Rutas protegidas: el usuario DEBE tener al menos 1 foto para acceder.
     * Si no tiene fotos, se redirige a la página de onboarding de fotos.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admins no se bloquean
        if (! $user || $user->isAdmin()) {
            return $next($request);
        }

        // Si el usuario no tiene ninguna foto subida, redirigir
        if ($user->photos()->count() === 0) {
            // Si ya está en la ruta de fotos, no redirigir (evitar loop)
            if ($request->routeIs('profile.photos.*') || $request->routeIs('profile.photos.index')) {
                return $next($request);
            }

            return redirect()
                ->route('profile.photos.index')
                ->with('photo_required', true);
        }

        return $next($request);
    }
}
