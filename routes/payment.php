<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rate_limit'])->group(function () {
    Route::post('/checkout', [PaymentController::class, 'checkout'])
        ->name('payment.checkout');

    Route::post('/refund', [PaymentController::class, 'refund'])
        ->name('payment.refund');
});


