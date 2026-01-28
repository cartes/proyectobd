<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
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

        // 5. Recent Activity Data
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()
            ->take(5)
            ->get();

        $stats = [
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

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'recentUsers'));
    }

    public function config()
    {
        return view('admin.config.index');
    }
}
