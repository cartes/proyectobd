<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ModerationController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');
});

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

    /**
     * Prefijo chats
     */
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/with/{user}', [ChatController::class, 'createOrFind'])->name('create');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::post('/{conversation}/read/{message}', [ChatController::class, 'markAsRead'])->name('read');
        Route::post('/{conversation}/block', [ChatController::class, 'blockConversation'])->name('block');
    });

    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');

    /**
     * Palabras bloqueadas
     */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::post('/message/{message}', [ReportController::class, 'reportMessage'])->name('reports.message');
        Route::post('/conversation/{conversation}', [ReportController::class, 'reportConversation'])->name('reports.conversation');
        Route::post('/user', [ReportController::class, 'reportUser'])->name('reports.user');
    });

});


/**
 * Rutas de administración
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    /**
     * Dashboard principal
     */


    // Dashboard
    Route::get('/moderation', [ModerationController::class, 'dashboard'])->name('moderation.dashboard');

    // Reportes
    Route::get('/moderation/reports', [ModerationController::class, 'reports'])->name('moderation.reports');
    Route::get('/moderation/reports/{report}', [ModerationController::class, 'showReport'])->name('moderation.reports.show');
    Route::post('/moderation/reports/{report}/process', [ModerationController::class, 'processReport'])->name('moderation.reports.process');

    // Palabras bloqueadas
    Route::get('/moderation/blocked-words', [ModerationController::class, 'blockedWords'])->name('moderation.blocked-words');
    Route::post('/moderation/blocked-words', [ModerationController::class, 'storeBlockedWord'])->name('moderation.blocked-words.store');
    Route::delete('/moderation/blocked-words/{word}', [ModerationController::class, 'destroyBlockedWord'])->name('moderation.blocked-words.destroy');

    // Usuarios
    Route::get('/moderation/users', [ModerationController::class, 'users'])->name('moderation.users');
    Route::get('/moderation/users/{user}', [ModerationController::class, 'showUser'])->name('moderation.users.show');
    Route::post('/moderation/users/{user}/action', [ModerationController::class, 'userAction'])->name('moderation.users.action');
});


require __DIR__ . '/auth.php';
