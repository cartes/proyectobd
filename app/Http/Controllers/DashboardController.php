<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $activeSubscription = $user->activeSubscription()->first();

        // Si es admin, mostrar dashboard principal del admin
        if ($user->isAdmin()) {
            return view('dashboard', [
                'stats' => $this->getAdminStats(),
                'recentActivity' => $this->getAdminActivity(),
                'dashboardData' => null,
            ]);
        }

        // Si es Sugar Daddy
        if ($user->isSugarDaddy()) {
            return view('dashboard', [
                'stats' => null, // ← Para evitar conflictos
                'recentActivity' => null,
                'dashboardData' => $this->getSugarDaddyData(),
                'activeSubscription' => $activeSubscription,
            ]);
        }

        // Si es Sugar Baby
        return view('dashboard', [
            'stats' => null, // ← Para evitar conflictos
            'recentActivity' => null,
            'dashboardData' => $this->getSugarBabyData(),
            'activeSubscription' => $activeSubscription,
        ]);
    }

    /**
     * Datos para Sugar Daddy
     */
    private function getSugarDaddyData()
    {
        $user = auth()->user();

        // Vistas de perfil (contar mediante una tabla intermedia si existe)
        $profileViews = 127; // Placeholder - se implementará con tabla de analytics

        // Nuevos matches en los últimos 7 días
        $newMatches = $user->matches()
            ->where('likes.created_at', '>=', now()->subDays(7))  // Usa 'likes' no 'matches'
            ->count();

        // Mensajes totales
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->latest()
            ->take(10)
            ->get();

        $unreadMessages = $messages->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Sugar Babies sugeridas (últimas que ha visto o no ha interactuado)
        $suggestedBabies = User::where('user_type', 'sugar_baby')
            ->where('is_active', true)
            ->whereNotIn('id', function ($query) use ($user) {
                $query->select('liked_user_id')
                    ->from('likes')
                    ->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return [
            'type' => 'sugar_daddy',
            'profileViews' => $profileViews,
            'newMatches' => $newMatches,
            'messageCount' => $messages->count(),
            'unreadCount' => $unreadMessages,
            'suggestedBabies' => $suggestedBabies,
            'recentMessages' => $messages,
        ];
    }

    /**
     * Datos para Sugar Baby
     */
    private function getSugarBabyData()
    {
        $user = auth()->user();

        // Perfil completado (%)
        $profileCompletion = $this->calculateProfileCompletion($user);

        // Vistas hoy
        $todayViews = 24; // Placeholder - se implementará con tabla de analytics

        // Likes recibidos esta semana
        $newLikes = $user->likedBy()
            ->where('likes.created_at', '>=', now()->subDays(7))  // ← Especifica la tabla
            ->count();

        // Mensajes recibidos
        $messages = Message::where('receiver_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $unreadMessages = $messages->where('is_read', false)->count();

        // Sugar Daddies destacados (premium)
        $featuredDaddies = User::where('user_type', 'sugar_daddy')
            ->where('is_premium', true)
            ->where('is_active', true)
            ->latest()
            ->take(2)
            ->get();

        return [
            'type' => 'sugar_baby',
            'profileCompletion' => $profileCompletion,
            'todayViews' => $todayViews,
            'newLikes' => $newLikes,
            'messageCount' => $messages->count(),
            'unreadCount' => $unreadMessages,
            'featuredDaddies' => $featuredDaddies,
            'recentMessages' => $messages,
        ];
    }

    /**
     * Calcular porcentaje de perfil completado
     */
    private function calculateProfileCompletion(User $user): int
    {
        $fields = [
            'name' => !empty($user->name),
            'bio' => !empty($user->bio),
            'city' => !empty($user->city),
            'birth_date' => !empty($user->birth_date),
            'photos' => $user->photos()->count() > 0,
            'is_verified' => $user->is_verified,
        ];

        $completed = array_sum(array_values($fields));
        $total = count($fields);

        return (int) (($completed / $total) * 100);
    }

    /**
     * Admin stats
     */
    private function getAdminStats()
    {
        return [
            'total_users' => User::count(),
            'premium_active' => User::where('is_premium', true)->count(),
            'pending_reports' => \App\Models\Report::pending()->count(),
            'monthly_revenue' => User::where('is_premium', true)->count() * 29.99,
        ];
    }

    /**
     * Admin activity
     */
    private function getAdminActivity()
    {
        return [
            'new_users' => User::latest()->take(3)->get(),
            'pending_reports' => \App\Models\Report::pending()
                ->with('reporter', 'reportedUser')
                ->latest()
                ->take(3)
                ->get(),
        ];
    }
}
