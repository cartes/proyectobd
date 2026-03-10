<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SubscriptionController;

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

// Públicas
Route::get('/countries/{country}/cities', [\App\Http\Controllers\Api\CityController::class, 'index']);
