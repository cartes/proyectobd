<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/payment.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        if (!app()->environment('testing')) {
            $middleware->trustProxies(at: '*');
        }
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'rate_limit' => \App\Http\Middleware\RateLimitMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'webhook/mercadopago',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
