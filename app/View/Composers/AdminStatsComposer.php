<?php

namespace App\View\Composers;

use App\Models\ProfilePhoto;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AdminStatsComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $stats = Cache::remember('admin.stats', 300, function () {
            return [
                'sugar_daddy_count' => User::where('user_type', 'sugar_daddy')->count(),
                'sugar_baby_count' => User::where('user_type', 'sugar_baby')->count(),
                'unverified_count' => User::where('is_verified', false)->count(),
                'pending_photos_count' => ProfilePhoto::where('moderation_status', 'pending')->count(),
                'active_subscriptions_count' => Subscription::where('status', 'active')->count(),
            ];
        });

        $view->with('adminStats', $stats);
    }
}
