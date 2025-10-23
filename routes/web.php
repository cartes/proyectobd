<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoController;
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
});


require __DIR__ . '/auth.php';
