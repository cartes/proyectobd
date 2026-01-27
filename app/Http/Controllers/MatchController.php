<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    /**
     * Mostrar todos los matches del usuario
     */
    public function index()
    {
        $user = Auth::user();

        // Obtener matches paginados
        $matches = $user->matches()
            ->with('profileDetail', 'photos')
            ->paginate(12);

        // Opciones de intereses
        $interestsOptions = \App\Models\ProfileDetail::interestsOptions();

        return view('discover.matches', compact('matches', 'interestsOptions'));  // ← matches no index
    }

    /**
     * Deshacer match con un usuario
     */
    public function unmatch(User $user)
    {
        $authUser = Auth::user();

        // Verificar que hay match
        if (! $authUser->hasMatchWith($user)) {
            return back()->with('error', 'No hay match con este usuario');
        }

        // Eliminar el like del usuario autenticado
        $authUser->likes()->detach($user->id);

        return back()->with('success', "Has deshecho el match con {$user->name}");
    }
}
