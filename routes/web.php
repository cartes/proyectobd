<?php

use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// SEO Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
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

    // En routes/web.php - dentro del grupo auth

    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/plans', [SubscriptionController::class, 'index'])->name('plans');
        Route::post('/checkout/{plan}', [SubscriptionController::class, 'createCheckout'])->name('checkout');
        Route::get('/success', [SubscriptionController::class, 'returnSuccess'])->name('success');
        Route::get('/failure', [SubscriptionController::class, 'returnFailure'])->name('failure');
        Route::get('/pending', [SubscriptionController::class, 'returnPending'])->name('pending');
        Route::post('/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('cancel');
        Route::get('/{subscription}', [SubscriptionController::class, 'show'])->name('show');
    });

    // Compras
    Route::post('/purchase/boost', [PurchaseController::class, 'buyBoost'])->name('purchase.boost');
    Route::post('/purchase/super-likes', [PurchaseController::class, 'buySuperLikes'])->name('purchase.super-likes');
    Route::post('/purchase/verification', [PurchaseController::class, 'buyVerification'])->name('purchase.verification');
    Route::post('/purchase/gift/{recipient}', [PurchaseController::class, 'buyGift'])->name('purchase.gift');
    Route::get('/purchase/success', [PurchaseController::class, 'returnSuccess'])->name('purchase.success');
    Route::get('/purchase/failure', [PurchaseController::class, 'returnFailure'])->name('purchase.failure');
    Route::get('/purchase/pending', [PurchaseController::class, 'returnPending'])->name('purchase.pending');
});

// Webhook de Mercado Pago (sin auth)
Route::post('/webhook/mercadopago', [WebhookController::class, 'handleMercadoPagoWebhook'])->name('webhook.mercadopago');

/**
 * Rutas de administración
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Super Admin Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

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
    Route::post('/moderation/users/{user}/verify', [ModerationController::class, 'toggleVerification'])->name('moderation.users.verify');
    Route::post('/moderation/users/{user}/change-role', [ModerationController::class, 'changeRole'])->name('moderation.users.change-role');
    Route::post('/moderation/users/{user}/toggle-premium', [ModerationController::class, 'togglePremium'])->name('moderation.users.toggle-premium');

    // Gestión de Planes (Precios y Ofertas)
    Route::get('/plans', [App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans.index');
    Route::get('/plans/{plan}/edit', [App\Http\Controllers\Admin\PlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{plan}', [App\Http\Controllers\Admin\PlanController::class, 'update'])->name('plans.update');

    // Finanzas
    Route::get('/transactions', [App\Http\Controllers\Admin\FinanceController::class, 'transactions'])->name('finance.transactions');

    // Moderación de Fotos
    Route::get('/moderation/photos', [App\Http\Controllers\Admin\PhotoModerationController::class, 'index'])->name('moderation.photos.index');
    Route::post('/moderation/photos/{photo}/approve', [App\Http\Controllers\Admin\PhotoModerationController::class, 'approve'])->name('moderation.photos.approve');
    Route::post('/moderation/photos/{photo}/reject', [App\Http\Controllers\Admin\PhotoModerationController::class, 'reject'])->name('moderation.photos.reject');

    // Moderación de Propuestas (Perfil)
    Route::get('/moderation/proposals', [App\Http\Controllers\Admin\ContentModerationController::class, 'index'])->name('moderation.proposals.index');
    Route::post('/moderation/proposals/{user}/approve', [App\Http\Controllers\Admin\ContentModerationController::class, 'approve'])->name('moderation.proposals.approve');
    Route::post('/moderation/proposals/{user}/reject', [App\Http\Controllers\Admin\ContentModerationController::class, 'reject'])->name('moderation.proposals.reject');

    // Módulos en desarrollo (Placeholders)
    Route::get('/finance/reports', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('finance.reports')->defaults('title', 'Reportes Financieros');
    Route::get('/system/config', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('system.config')->defaults('title', 'Configuración Global');
    Route::get('/system/logs', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('system.logs')->defaults('title', 'Logs del Sistema');
    Route::get('/system/stats', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('system.stats')->defaults('title', 'Estadísticas Generales');
    Route::get('/marketing/promotions', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('marketing.promotions')->defaults('title', 'Promociones');
    Route::get('/marketing/notifications', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('marketing.notifications')->defaults('title', 'Notificaciones Push');
});

// Tracking de Engagement desde Email
Route::get('/e/{token}', [App\Http\Controllers\EngagementController::class, 'track'])->name('engagement.track');

// Páginas Legales
Route::get('/terminos-y-condiciones', [App\Http\Controllers\LegalController::class, 'terms'])->name('legal.terms');
Route::get('/politica-de-privacidad', [App\Http\Controllers\LegalController::class, 'privacy'])->name('legal.privacy');

require __DIR__.'/auth.php';
