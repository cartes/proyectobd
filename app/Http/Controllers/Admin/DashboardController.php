<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use App\Models\UserAction;
use App\Models\Message;
use App\Models\ProfilePhoto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats principales
        $stats = [
            'total_users' => User::count(),
            'premium_active' => User::where('is_premium', true)->count(),
            'pending_reports' => Report::pending()->count(),
            'monthly_revenue' => $this->calculateMonthlyRevenue(),
        ];

        // Crecimiento
        $growth = [
            'users_growth' => $this->calculateGrowth(User::class),
            'premium_growth' => $this->calculateGrowth(User::class, 'is_premium'),
            'revenue_growth' => $this->calculateRevenueGrowth(),
        ];

        // Actividad Reciente
        $recentActivity = [
            'new_users' => User::latest()->take(3)->get(),
            'pending_reports' => Report::pending()->with('reporter', 'reportedUser')->latest()->take(3)->get(),
            'recent_messages' => Message::latest()->take(5)->get(),
            'recent_photos' => ProfilePhoto::with('user')
                ->orderBy('potential_nudity', 'desc')
                ->latest()
                ->take(12)
                ->get(),
        ];

        // Datos para gráficos (últimos 30 días)
        $messagesTrend = $this->getMessagesTrend();
        $usersTrend = $this->getUsersTrend();

        return view('dashboard', compact(
            'stats',
            'growth',
            'recentActivity',
            'messagesTrend',
            'usersTrend'
        ));
    }

    /**
     * Calcular crecimiento porcentual
     */
    private function calculateGrowth($model, $column = null)
    {
        $today = $model::where('created_at', '>=', now()->startOfDay());
        $lastMonth = $model::where('created_at', '>=', now()->subMonth()->startOfDay())
            ->where('created_at', '<', now()->startOfMonth());

        if ($column) {
            $today = $today->where($column, true);
            $lastMonth = $lastMonth->where($column, true);
        }

        $todayCount = $today->count();
        $lastMonthCount = $lastMonth->count();

        if ($lastMonthCount == 0)
            return 0;

        return round((($todayCount - $lastMonthCount) / $lastMonthCount) * 100, 1);
    }

    /**
     * Calcular ingresos mensuales
     */
    private function calculateMonthlyRevenue()
    {
        // Por ahora retorna un valor estático
        // Se actualizará cuando esté implementado el HITO 5 (Pagos)
        return User::where('is_premium', true)
            ->where('updated_at', '>=', now()->startOfMonth())
            ->count() * 29.99; // Precio base de suscripción
    }

    /**
     * Crecimiento de ingresos
     */
    private function calculateRevenueGrowth()
    {
        $thisMonth = User::where('is_premium', true)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        $lastMonth = User::where('is_premium', true)
            ->where('created_at', '>=', now()->subMonth()->startOfMonth())
            ->where('created_at', '<', now()->startOfMonth())
            ->count();

        if ($lastMonth == 0)
            return 0;

        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Tendencia de mensajes (últimos 7 días)
     */
    private function getMessagesTrend()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = Message::where('created_at', '>=', $date)
                ->where('created_at', '<', $date->copy()->endOfDay())
                ->count();

            $data[] = [
                'date' => $date->format('d/m'),
                'count' => $count,
            ];
        }
        return $data;
    }

    /**
     * Tendencia de usuarios (últimos 7 días)
     */
    private function getUsersTrend()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = User::where('created_at', '>=', $date)
                ->where('created_at', '<', $date->copy()->endOfDay())
                ->count();

            $data[] = [
                'date' => $date->format('d/m'),
                'count' => $count,
            ];
        }
        return $data;
    }
}
