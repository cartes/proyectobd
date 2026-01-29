<?php

namespace App\Http\Controllers;

use App\Models\ProfileDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscoveryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Determinar qué tipo de usuarios mostrar según el tipo actual
        $targetUserType = $user->user_type === 'sugar_daddy' ? 'sugar_baby' : 'sugar_daddy';

        // Query base: usuarios del tipo opuesto, activos y verificados
        $query = User::where('user_type', $targetUserType)
            ->where('is_active', true)
            ->where('id', '!=', $user->id)
            ->where('role', '!=', 'admin')
            ->whereHas('profileDetail', function ($q) {
                $q->where('is_private', false);
            })
            ->with(['profileDetail', 'photos']);

        // Filtro por ciudad (updated)
        if ($request->filled('city')) {
            $query->where('city', 'LIKE', "%{$request->city}%");
        }

        // Filtro por intereses
        if ($request->filled('interests')) {
            $interests = is_array($request->interests) ? $request->interests : [$request->interests];
            $query->whereHas('profileDetail', function ($q) use ($interests) {
                foreach ($interests as $interest) {
                    $q->whereJsonContains('interests', $interest);
                }
            });
        }

        // Filtro por rango de edad
        if ($request->filled('age_min')) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= ?', [$request->age_min]);
        }

        if ($request->filled('age_max')) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= ?', [$request->age_max]);
        }

        // Obtener usuarios y paginar
        $users = $query->latest()->paginate(12);

        // Opciones para filtros
        $cities = User::where('user_type', $targetUserType)
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        $interestsOptions = ProfileDetail::interestsOptions();

        return view('discover.index', compact('users', 'cities', 'interestsOptions', 'targetUserType'));
    }

    public function like(User $user, \App\Services\NotificationService $notificationService)
    {
        $currentUser = Auth::user();

        // Verificar que no se esté dando like a sí mismo
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'No puedes dar like a tu propio perfil');
        }

        // Verificar si ya dio like
        if ($currentUser->hasLiked($user)) {
            return back()->with('info', '¡Ya le habías dado like a este perfil!');
        }

        // Agregar like
        $currentUser->likes()->attach($user->id);

        // Notificar al usuario (con lógica de prioridad/engagement)
        $notificationService->notifyNewLike($user, $currentUser);

        // Verificar si hay match mutuo
        if ($currentUser->hasMatchWith($user)) {
            return back()->with('success', '¡Es un Match! 🎉💕');
        }

        return back()->with('success', 'Like enviado ❤️');
    }

    public function unlike(User $targetUser)
    {
        Auth::user()->likes()->detach($targetUser->id);

        return back()->with('success', 'Like eliminado');
    }

    public function favorites()
    {
        $user = Auth::user();

        // Obtener usuarios a los que dio like
        $favorites = $user->likes()
            ->where('is_active', true)
            ->with(['profileDetail', 'photos'])
            ->paginate(12);

        $interestsOptions = ProfileDetail::interestsOptions();

        return view('discover.favorites', compact('favorites', 'interestsOptions'));
    }

    /**
     * Metodo para mostrar matches
     */
    public function matches()
    {
        $user = Auth::user();

        // Obtener matches mutuos con paginación
        $matches = $user->matches()
            ->with(['profileDetail', 'photos'])
            ->latest('likes.created_at')
            ->paginate(12);

        $interestsOptions = ProfileDetail::interestsOptions();

        return view('discover.matches', compact('matches', 'interestsOptions'));
    }
}