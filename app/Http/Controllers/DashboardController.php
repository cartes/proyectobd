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

        // Si es admin, redirigir al panel de administración oficial
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
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

        // Vistas de perfil (totales)
        $profileViews = $user->profileViews()->count();

        // Crecimiento vistas (esta semana vs anterior)
        $viewsThisWeek = $user->profileViews()->where('created_at', '>=', now()->startOfWeek())->count();
        $viewsLastWeek = $user->profileViews()
            ->where('created_at', '>=', now()->subWeek()->startOfWeek())
            ->where('created_at', '<', now()->startOfWeek())
            ->count();

        $viewsGrowth = $viewsLastWeek > 0
            ? round((($viewsThisWeek - $viewsLastWeek) / $viewsLastWeek) * 100)
            : ($viewsThisWeek > 0 ? 100 : 0);

        // Nuevos matches en los últimos 7 días
        $newMatches = $user->matches()
            ->where('likes.created_at', '>=', now()->subDays(7))
            ->count();

        // Mensajes
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->latest()
            ->take(10)
            ->get();

        $unreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Sugar Babies sugeridas
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
            'viewsGrowth' => $viewsGrowth,
            'newMatches' => $newMatches,
            'messageCount' => Message::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->count(),
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

        // Vistas de perfil (totales)
        $profileViews = $user->profileViews()->count();

        // Crecimiento vistas (esta semana vs anterior)
        $viewsThisWeek = $user->profileViews()->where('created_at', '>=', now()->startOfWeek())->count();
        $viewsLastWeek = $user->profileViews()
            ->where('created_at', '>=', now()->subWeek()->startOfWeek())
            ->where('created_at', '<', now()->startOfWeek())
            ->count();

        $viewsGrowth = $viewsLastWeek > 0
            ? round((($viewsThisWeek - $viewsLastWeek) / $viewsLastWeek) * 100)
            : ($viewsThisWeek > 0 ? 100 : 0);

        // Likes recibidos esta semana
        $newLikes = $user->likedBy()
            ->where('likes.created_at', '>=', now()->subDays(7))
            ->count();

        // Mensajes recibidos
        $messages = Message::where('receiver_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $unreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Sugar Daddies destacados
        $featuredDaddies = User::where('user_type', 'sugar_daddy')
            ->where('is_premium', true)
            ->where('is_active', true)
            ->latest()
            ->take(2)
            ->get();

        return [
            'type' => 'sugar_baby',
            'profileCompletion' => $profileCompletion,
            'profileViews' => $profileViews,
            'viewsGrowth' => $viewsGrowth,
            'newLikes' => $newLikes,
            'messageCount' => Message::where('receiver_id', $user->id)->count(),
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
}
