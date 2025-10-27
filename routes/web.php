<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\DiscoveryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /**
     * Rutas manejo de fotos de perfil
     */
    Route::get('/profile/photos', function () {
        return view('profile.photos');
    })->name('profile.photos.index');

    Route::prefix('profile/photos')->name('profile.photos.')->group(function () {
        Route::post('/', [ProfilePhotoController::class, 'store'])->name('store');
        Route::put('/{photo}/set-primary', [ProfilePhotoController::class, 'setPrimary'])->name('setPrimary');
        Route::post('/reorder', [ProfilePhotoController::class, 'reorder'])->name('reorder');
        Route::delete('/{photo}', [ProfilePhotoController::class, 'destroy'])->name('destroy');
    });

    /**
     * Discovery system
     */
    Route::get('/discover', [DiscoveryController::class, 'index'])->name('discover.index');
    Route::post('/like/{user}', [DiscoveryController::class, 'like'])->name('discover.like');
    Route::delete('/unlike/{user}', [DiscoveryController::class, 'unlike'])->name('discover.unlike');

    Route::get('/favoritos', [DiscoveryController::class, 'favorites'])->name('discover.favorites');


    // PLACEHOLDERS TEMPORALES
    Route::get('/matches', function () {
        return view('coming-soon', ['feature' => 'Matches', 'icon' => '❤️']);
    })->name('matches.index');

    Route::get('/messages', function () {
        return view('coming-soon', ['feature' => 'Mensajería', 'icon' => '💬']);
    })->name('messages.index');


    Route::get('/matches', [App\Http\Controllers\DiscoveryController::class, 'matches'])
        ->name('matches.index');
});


require __DIR__ . '/auth.php';
