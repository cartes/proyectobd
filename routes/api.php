<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\WebhookController;

// Autenticadas
Route::middleware('auth')->group(function () {

    // Subscriptions
    Route::prefix('subscriptions')->group(function () {
        Route::get('/', [SubscriptionController::class, 'apiIndex']);
        Route::get('/{id}', [SubscriptionController::class, 'apiShow']);
        Route::delete('/{id}', [SubscriptionController::class, 'apiDestroy']);
    });

    // Purchases
    Route::prefix('purchases')->group(function () {
        Route::post('/', [PurchaseController::class, 'apiStore']);
        Route::get('/', [PurchaseController::class, 'apiIndex']);
        Route::get('/{id}', [PurchaseController::class, 'apiShow']);
    });
});

// PÚBLICAS (webhook se maneja en routes/web.php)
// Las rutas de webhook están en web.php para evitar duplicación

