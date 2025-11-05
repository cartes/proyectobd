<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\MercadoPagoService::class, function ($app) {
            return new \App\Services\MercadoPagoService();
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
        //
    }
}
