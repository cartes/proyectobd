<?php

namespace App\Providers;

use App\View\Composers\AdminStatsComposer;
use App\View\Composers\BlogSettingsComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Admin stats for navigation components
        View::composer([
            'components.navigation.admin',
            'layouts.admin',
        ], AdminStatsComposer::class);

        // Blog settings for blog layout
        View::composer('layouts.blog', BlogSettingsComposer::class);
    }
}
