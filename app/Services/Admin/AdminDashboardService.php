<?php

namespace App\Services\Admin;

use App\Models\Report;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;

class AdminDashboardService
{
    /**
     * Get all data required for the admin dashboard.
     */
    public function getDashboardData(): array
    {
        return [
            'stats' => $this->getStats(),
            'recentTransactions' => $this->getRecentTransactions(),
            'recentUsers' => $this->getRecentUsers(),
        ];
    }

    /**
     * Calculate general statistics.
     */
    protected function getStats(): array
    {
        // 1. User Stats
        $totalUsers = User::count();
        $sugarDaddies = User::where('user_type', 'sugar_daddy')->count();
        $sugarBabies = User::where('user_type', 'sugar_baby')->count();

        // 2. Financial Stats (Mercado Pago approved transactions)
        $totalRevenue = Transaction::where('status', 'approved')->sum('amount');
        $monthlyRevenue = Transaction::where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // 3. Subscription Stats
        $activeSubscriptions = Subscription::where('status', 'active')->count();

        // 4. Moderation Stats
        $pendingReports = Report::where('status', 'pending')->count();

        return [
            'total_users' => $totalUsers,
            'sd_count' => $sugarDaddies,
            'sb_count' => $sugarBabies,
            'sd_percentage' => $totalUsers > 0 ? round(($sugarDaddies / $totalUsers) * 100) : 0,
            'sb_percentage' => $totalUsers > 0 ? round(($sugarBabies / $totalUsers) * 100) : 0,

            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'active_subscriptions' => $activeSubscriptions,
            'pending_reports' => $pendingReports,
        ];
    }

    /**
     * Get recent transactions with user data.
     */
    protected function getRecentTransactions()
    {
        return Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Get recently registered users.
     */
    protected function getRecentUsers()
    {
        return User::latest()
            ->take(5)
            ->get();
    }
}
