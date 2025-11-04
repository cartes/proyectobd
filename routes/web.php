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

    /**
     * Rutas de perfil con prefijo /profile
     */
    Route::prefix('profile')->name('profile.')->group(function () {
        // Editar perfil
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');

        // Gestión de fotos
        Route::prefix('photos')->name('photos.')->group(function () {
            Route::get('/', function () {
                return view('profile.photos');
            })->name('index');

            Route::post('/', [ProfilePhotoController::class, 'store'])->name('store');
            Route::post('/reorder', [ProfilePhotoController::class, 'reorder'])->name('reorder');
            Route::put('/{photo}/set-primary', [ProfilePhotoController::class, 'setPrimary'])->name('setPrimary');
            Route::delete('/{photo}', [ProfilePhotoController::class, 'destroy'])->name('destroy');
        });

        // Ver perfil (propio u otro con match)
        Route::get('/{user?}', [ProfileController::class, 'show'])->name('show');
    });

    /**
     * Discovery system
     */
    Route::get('/discover', [DiscoveryController::class, 'index'])->name('discover.index');
    Route::post('/like/{user}', [DiscoveryController::class, 'like'])->name('discover.like');
    Route::delete('/unlike/{user}', [DiscoveryController::class, 'unlike'])->name('discover.unlike');
    Route::get('/favoritos', [DiscoveryController::class, 'favorites'])->name('discover.favorites');

    /**
     * Matches management
     */
    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
    Route::delete('/matches/{user}', [MatchController::class, 'unmatch'])->name('matches.unmatch');

    /**
     * Prefijo chats
     */
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', action: [ChatController::class, 'index'])->name('index');
        Route::get('/with/{user}', [ChatController::class, 'createOrFind'])->name('create');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::post('/{conversation}/read/{message}', [ChatController::class, 'markAsRead'])->name('read');
        Route::post('/{conversation}/block', [ChatController::class, 'blockConversation'])->name('block');
    });

    /**
     * Reports
     */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::post('/message/{message}', [ReportController::class, 'reportMessage'])->name('message');
        Route::post('/conversation/{conversation}', [ReportController::class, 'reportConversation'])->name('conversation');
        Route::post('/user', [ReportController::class, 'reportUser'])->name('user');
    });
});

/**
 * Rutas de administración
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
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
