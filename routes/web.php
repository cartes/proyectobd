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
use App\Http\Controllers\StorageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $latestPosts = \App\Models\BlogPost::published()
        ->with('category')
        ->latest('published_at')
        ->take(3)
        ->get();

    return view('welcome', compact('latestPosts'));
})->name('welcome');

Route::get('/como-funciona', function () {
    return view('como-funciona');
})->name('como-funciona');

Route::get('/quienes-somos', function () {
    return view('quienes-somos');
})->name('about.index');

// Controlled Media Routes (Bypassing reserved /storage path)
Route::get('/app-media/profiles/{hash}/{file}', [StorageController::class, 'showProfilePhoto']);
Route::get('/app-media/{path}', [StorageController::class, 'showPublicFile'])->where('path', '.*');

// SEO Sitemap (static file served directly)
// Generate with: php artisan sitemap:generate
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');

    if (! file_exists($path)) {
        abort(404, 'Sitemap not found. Run: php artisan sitemap:generate');
    }

    return response()->file($path, [
        'Content-Type' => 'application/xml',
    ]);
})->name('sitemap');

Route::middleware(['auth', 'verified', 'has_photo'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

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
     * Discovery system (requiere al menos 1 foto)
     */
    Route::middleware('has_photo')->group(function () {
        Route::get('/discover', [DiscoveryController::class, 'index'])->name('discover.index');
        Route::post('/discover/ai-search', [DiscoveryController::class, 'aiSearch'])->name('discover.ai-search');
        Route::post('/like/{user}', [DiscoveryController::class, 'like'])->name('discover.like');
        Route::delete('/unlike/{user}', [DiscoveryController::class, 'unlike'])->name('discover.unlike');
        Route::get('/favoritos', [DiscoveryController::class, 'favorites'])->name('discover.favorites');
    });

    /**
     * Matches management (requiere al menos 1 foto)
     */
    Route::middleware('has_photo')->group(function () {
        Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
        Route::delete('/matches/{user}', [MatchController::class, 'unmatch'])->name('matches.unmatch');
    });

    /**
     * Prefijo chats (requiere al menos 1 foto)
     */
    Route::middleware('has_photo')->prefix('chat')->name('chat.')->group(function () {
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
        // ⚡ COMENTADO: Modo Lanzamiento - Webhooks MP en reparación
        Route::post('/checkout/{plan}', [SubscriptionController::class, 'createCheckout'])->name('checkout');
        Route::get('/success', [SubscriptionController::class, 'returnSuccess'])->name('success');
        Route::get('/failure', [SubscriptionController::class, 'returnFailure'])->name('failure');
        Route::get('/pending', [SubscriptionController::class, 'returnPending'])->name('pending');
        // Route::post('/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('cancel');
        Route::get('/{subscription}', [SubscriptionController::class, 'show'])->name('show');
    });

    // Compras - ⚡ COMENTADO: Modo Lanzamiento - Webhooks MP en reparación
    // Route::post('/purchase/boost', [PurchaseController::class, 'buyBoost'])->name('purchase.boost');
    // Route::post('/purchase/super-likes', [PurchaseController::class, 'buySuperLikes'])->name('purchase.super-likes');
    // Route::post('/purchase/verification', [PurchaseController::class, 'buyVerification'])->name('purchase.verification');
    // Route::post('/purchase/gift/{recipient}', [PurchaseController::class, 'buyGift'])->name('purchase.gift');
    Route::get('/purchase/success', [PurchaseController::class, 'returnSuccess'])->name('purchase.success');
    Route::get('/purchase/failure', [PurchaseController::class, 'returnFailure'])->name('purchase.failure');
    Route::get('/purchase/pending', [PurchaseController::class, 'returnPending'])->name('purchase.pending');
});

// Webhook de Mercado Pago (sin auth)
Route::post('/webhook/mercadopago', [WebhookController::class, 'handleMercadoPagoWebhook'])
    ->middleware('rate_limit')
    ->name('webhook.mercadopago');

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
    Route::post('/moderation/users/{user}/change-country', [ModerationController::class, 'changeCountry'])->name('moderation.users.change-country');
    Route::post('/moderation/users/{user}/toggle-premium', [ModerationController::class, 'togglePremium'])->name('moderation.users.toggle-premium');
    Route::post('/moderation/users/{user}/toggle-private', [ModerationController::class, 'togglePrivateProfile'])->name('moderation.users.toggle-private');
    Route::post('/moderation/users/{user}/update-profile', [ModerationController::class, 'updateUserProfile'])->name('moderation.users.update-profile');

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
    Route::get('/system/config', [App\Http\Controllers\Admin\AdminController::class, 'config'])->name('system.config')->defaults('title', 'Configuración Global');
    Route::get('/system/logs', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('system.logs')->defaults('title', 'Logs del Sistema');
    Route::get('/system/stats', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('system.stats')->defaults('title', 'Estadísticas Generales');
    Route::get('/marketing/promotions', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('marketing.promotions')->defaults('title', 'Promociones');
    Route::get('/marketing/notifications', [App\Http\Controllers\Admin\AdminPlaceholderController::class, 'index'])->name('marketing.notifications')->defaults('title', 'Notificaciones Push');

    // Perfil del Super-Admin
    Route::get('/profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // Notificaciones de Admin (campana)
    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Notificaciones marcadas como leídas.');
    })->name('notifications.mark-all-read');

    Route::post('/notifications/{id}/mark-read', function (string $id) {
        auth()->user()->notifications()->where('id', $id)->update(['read_at' => now()]);

        return back();
    })->name('notifications.mark-read');

    // Gestión de Países
    Route::prefix('countries')->name('countries.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CountryController::class, 'index'])->name('index');
        Route::post('/{country}/toggle', [\App\Http\Controllers\Admin\CountryController::class, 'toggleStatus'])->name('toggle');
    });

    // Blog Management
    Route::prefix('blog')->name('blog.')->group(function () {
        // Dashboard del Blog
        Route::get('/', [\App\Http\Controllers\Admin\BlogController::class, 'dashboard'])->name('dashboard');

        // Gestión de Posts
        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BlogPostController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\BlogPostController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\BlogPostController::class, 'store'])->name('store');
            Route::get('/{post}/edit', [\App\Http\Controllers\Admin\BlogPostController::class, 'edit'])->name('edit');
            Route::put('/{post}', [\App\Http\Controllers\Admin\BlogPostController::class, 'update'])->name('update');
            Route::delete('/{post}', [\App\Http\Controllers\Admin\BlogPostController::class, 'destroy'])->name('destroy');
            Route::post('/upload-image', [\App\Http\Controllers\Admin\BlogPostController::class, 'uploadImage'])->name('upload-image');
            Route::post('/{post}/duplicate', [\App\Http\Controllers\Admin\BlogPostController::class, 'duplicate'])->name('duplicate');
        });

        // Gestión de Categorías
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'store'])->name('store');
            Route::put('/{category}', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'destroy'])->name('destroy');
        });

        // Configuración y SEO
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BlogSettingsController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\BlogSettingsController::class, 'update'])->name('update');
            Route::get('/seo', [\App\Http\Controllers\Admin\BlogSettingsController::class, 'seo'])->name('seo');
            Route::get('/analytics', [\App\Http\Controllers\Admin\BlogSettingsController::class, 'analytics'])->name('analytics');
            Route::get('/scripts', [\App\Http\Controllers\Admin\BlogSettingsController::class, 'scripts'])->name('scripts');
            Route::post('/sitemap/regenerate', [\App\Http\Controllers\Admin\BlogSettingsController::class, 'regenerateSitemap'])->name('sitemap.regenerate');
        });
    });
});

// Blog Público - Accesible para todos los visitantes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [\App\Http\Controllers\BlogController::class, 'index'])->name('index');
    Route::get('/categoria/{slug}', [\App\Http\Controllers\BlogController::class, 'category'])->name('category');
    Route::get('/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('show');
});

// Redirecciones 301: URLs antiguas con ISO code (ej: /sugar-babies/VE → /sugar-babies/venezuela)
// DEBEN ir antes que las rutas de slug para que Laravel las evalúe primero.
Route::get('/sugar-babies/{iso}', function (string $iso) {
    $country = \App\Models\Country::where('iso_code', strtoupper($iso))->firstOrFail();

    return redirect()->route('archive.country', $country->slug, 301);
})->where('iso', '[A-Za-z]{2}');

Route::get('/sugar-babies/{iso}/{city}', function (string $iso, string $city) {
    $country = \App\Models\Country::where('iso_code', strtoupper($iso))->firstOrFail();
    $cityModel = \App\Models\City::where('country_id', $country->id)
        ->where('slug', $city)
        ->firstOrFail();

    return redirect()->route('archive.city', [$country->slug, $cityModel->slug], 301);
})->where('iso', '[A-Za-z]{2}');

// Archives Públicos por País (slug amigable, ej: /sugar-babies/venezuela)
Route::get('/sugar-babies/{country:slug}', [\App\Http\Controllers\CountryArchiveController::class, 'index'])->name('archive.country');
Route::get('/sugar-babies/{country:slug}/{city:slug}', [\App\Http\Controllers\CityArchiveController::class, 'index'])->name('archive.city');

// Tracking de Engagement desde Email
Route::get('/e/{token}', [App\Http\Controllers\EngagementController::class, 'track'])->name('engagement.track');

// Páginas Legales
Route::get('/terminos-y-condiciones', [App\Http\Controllers\LegalController::class, 'terms'])->name('legal.terms');
Route::get('/politica-de-privacidad', [App\Http\Controllers\LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/reglas-de-la-comunidad', [App\Http\Controllers\LegalController::class, 'rules'])->name('legal.rules');
Route::get('/seguridad', [App\Http\Controllers\LegalController::class, 'safety'])->name('legal.safety');
Route::get('/planes', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('plans.public');

// Ruta temporal de prueba
Route::get('/test-payment', function () {
    $service = app(App\Services\MercadoPagoService::class);
    $user = Auth::user(); // Asegúrate de estar logueado

    // Simula la compra de un 'boost'
    $result = $service->createPaymentPreference($user, 'boost', 5000.00);

    if ($result['success']) {
        return redirect($result['sandbox_init_point']); // USA EL LINK DE SANDBOX
    }

    return $result['error'];
});

require __DIR__.'/auth.php';
