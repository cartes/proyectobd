<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\MercadoPagoService::class, function ($app) {
            return new \App\Services\MercadoPagoService;
        });

        $this->app->singleton(\App\Services\SubscriptionService::class, function ($app) {
            return new \App\Services\SubscriptionService(
                $app->make(\App\Services\MercadoPagoService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Forzar HTTPS en producción Y en ngrok
        if (
            $this->app->environment(['production']) ||
            (request()->getHost() && str_contains(request()->getHost(), 'ngrok'))
        ) {
            URL::forceScheme('https');
        }

    }
}
