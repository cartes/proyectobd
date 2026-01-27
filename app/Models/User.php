<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'gender',
        'role',
        'birth_date',
        'city',
        'bio',
        'avatar',
        'is_premium',
        'is_verified',
        'is_active',
        'premium_until',
        'premium_plan_type',
        'super_likes_remaining',
        'profile_boost_active',
        'boost_until',
        'boost_count',
        'mercado_pago_customer_id',
        'primary_payment_method_id',
        'auto_renew',
        'payment_preferences',
        'last_login_at',
        'last_email_interaction_at',
        'engagement_score',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'is_premium' => 'boolean',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',

            'premium_until' => 'datetime',
            'last_login_at' => 'datetime',
            'last_email_interaction_at' => 'datetime',
            'boost_until' => 'datetime',
            'profile_boost_active' => 'boolean',
            'auto_renew' => 'boolean',
            'payment_preferences' => 'json',
        ];
    }

    // ==================== RELACIONES PERFIL ====================

    public function profileDetail(): HasOne
    {
        return $this->hasOne(ProfileDetail::class);
    }

    /**
     * Obtener fotos del usuario
     */
    public function photos()
    {
        return $this->hasMany(ProfilePhoto::class)->ordered();
    }

    /**
     * Verificar si tiene una suscripción activa
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->exists();
    }

    /**
     * Obtener foto principal
     */
    public function primaryPhoto()
    {
        return $this->hasOne(ProfilePhoto::class)->where('is_primary', true);
    }

    /**
     * Accesor para atributo is_admin
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    // ==================== RELACIONES MATCHING ====================

    /**
     * Usuarios que este usuario ha dado like
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'user_id', 'liked_user_id')
            ->withTimestamps();
    }

    /**
     * Usuarios que han dado like a este usuario
     */
    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes', 'liked_user_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Obtener todos los matches mutuos del usuario
     * (Usuarios que yo di like Y que me dieron like)
     */
    public function matches()
    {
        return $this->likes()
            ->whereIn('users.id', function ($query) {
                $query->select('user_id')
                    ->from('likes')
                    ->where('liked_user_id', $this->id);
            });
    }

    // ==================== RELACIONES MODERACIÓN ====================

    /**
     * Obtener acciones de moderación del usuario
     */
    public function actions()
    {
        return $this->hasMany(UserAction::class);
    }

    /**
     * Reportes contra este usuario
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    /**
     * Reportes que este usuario ha hecho
     */
    public function reportsSubmitted()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    // ==================== RELACIONES ANALYTICS ====================
    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class, 'viewed_id');
    }

    // ==================== RELACIONES MENSAJERÍA ====================

    /**
     * Obtener mensajes enviados por el usuario
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Obtener mensajes recibidos por el usuario
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Obtener conversaciones del usuario
     */
    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }

    // ==================== RELACIONES PAGOS ====================

    /**
     * Relación: un usuario puede tener muchas suscripciones
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Relación: un usuario puede tener muchas transacciones
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * ⭐ NUEVA: Relación con reembolsos
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    /**
     * ⭐ NUEVA: Relación con métodos de pago guardados
     */
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Relación: un usuario puede tener muchas compras
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * ⭐ NUEVA: Relación con logs de auditoría de pagos
     */
    public function paymentAuditLogs()
    {
        return $this->hasMany(PaymentAuditLog::class);
    }

    /**
     * ⭐ NUEVA: Obtener método de pago por defecto
     */
    public function defaultPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::class)
            ->where('is_active', true)
            ->where('is_default', true);
    }

    // ==================== MÉTODOS HELPER - MATCHING ====================

    /**
     * Contar matches totales
     */
    public function matchesCount(): int
    {
        return $this->matches()->count();
    }

    /**
     * Verificar si tiene al menos un match
     */
    public function hasMatches(): bool
    {
        return $this->matchesCount() > 0;
    }

    /**
     * Verificar si hay match mutuo con otro usuario
     */
    public function hasMatchWith(User $user): bool
    {
        return $this->likes()->where('liked_user_id', $user->id)->exists()
            && $this->likedBy()->where('user_id', $user->id)->exists();
    }

    /**
     * Verificar si le dio like a un usuario
     */
    public function hasLiked(User $user): bool
    {
        return $this->likes()->where('liked_user_id', $user->id)->exists();
    }

    // ==================== MÉTODOS HELPER - TIPO USUARIO ====================

    public function isSugarDaddy(): bool
    {
        return $this->user_type === 'sugar_daddy';
    }

    public function isSugarBaby(): bool
    {
        return $this->user_type === 'sugar_baby';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ==================== MÉTODOS HELPER - PREMIUM & SUSCRIPCIÓN ====================

    /**
     * Obtener suscripción activa actual
     * Prioriza verificar BD primero (más rápido), luego verifica en Mercado Pago
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }

    /**
     * ⭐ NUEVA: Obtener método de pago activo para usar
     */
    public function getActivePaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethods()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->first();
    }

    /**
     * ⭐ NUEVA: Verificar si tiene métodos de pago guardados
     */
    public function hasPaymentMethods(): bool
    {
        return $this->paymentMethods()
            ->where('is_active', true)
            ->exists();
    }

    /**
     * ⭐ NUEVA: Obtener últimas N transacciones
     */
    public function recentTransactions(int $limit = 10)
    {
        return $this->transactions()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * ⭐ NUEVA: Contar super likes restantes
     */
    public function hasSuperLikesAvailable(): bool
    {
        return $this->super_likes_remaining > 0;
    }

    /**
     * ⭐ NUEVA: Usar un super like
     */
    public function useSuperLike(): bool
    {
        if ($this->super_likes_remaining > 0) {
            return $this->decrement('super_likes_remaining');
        }

        return false;
    }

    /**
     * ⭐ NUEVA: Verificar si boost está activo
     */
    public function hasActiveBoost(): bool
    {
        return $this->profile_boost_active && $this->boost_until?->isFuture();
    }

    /**
     * ⭐ NUEVA: Desactivar boost
     */
    public function deactivateBoost(): bool
    {
        return $this->update([
            'profile_boost_active' => false,
        ]);
    }

    /**
     * ⭐ NUEVA: Renovar boost
     */
    public function renewBoost(int $days = 7): bool
    {
        return $this->update([
            'profile_boost_active' => true,
            'boost_until' => now()->addDays($days),
            'boost_count' => $this->boost_count + 1,
        ]);
    }

    /**
     * Verificar si usuario es premium
     * Usa la suscripción activa si existe, sino verifica el atributo legacy is_premium
     */
    public function isPremium(): bool
    {
        // ✅ Obtener la suscripción activa
        $activeSubscription = $this->activeSubscription()->first();

        // ✅ Si tiene suscripción activa, es premium
        if ($activeSubscription) {
            return true;
        }

        // ✅ Si NO tiene suscripción activa pero is_premium=true, resetear a false
        if ($this->is_premium) {
            $this->update(['is_premium' => false]);
        }

        return false;
    }

    // ==================== MÉTODOS HELPER - PERFIL ====================

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? now()->diffInYears($this->birth_date) : null;
    }

    public function hasCompleteProfile(): bool
    {
        return $this->profileDetail !== null
            && $this->bio !== null
            && $this->city !== null;
    }

    /**
     * Obtener fotos aprobadas
     */
    public function approvedPhotos(): HasMany
    {
        return $this->hasMany(ProfilePhoto::class)
            ->where('moderation_status', 'approved')
            ->ordered();
    }

    /**
     * Helper para obtener URL de foto principal
     */
    public function getPrimaryPhotoUrlAttribute(): ?string
    {
        return $this->primaryPhoto?->url ?? null;
    }

    /**
     * Obtener el límite máximo de fotos según el plan
     */
    public function getMaxPhotosCount(): int
    {
        $features = app(\App\Services\SubscriptionService::class)->getUserFeatures($this);

        if ($features['extended_photos'] ?? false) {
            return 12; // Límite para PRO
        }

        // Daddies premium también podrían tener más
        if ($this->isPremium()) {
            return 10;
        }

        return ProfilePhoto::MAX_PHOTOS; // Límite por defecto (8)
    }

    /**
     * Verificar si puede subir más fotos
     */
    public function canUploadMorePhotos()
    {
        return $this->photos()->count() < $this->getMaxPhotosCount();
    }

    /**
     * Obtener cantidad de fotos disponibles
     */
    public function remainingPhotosCount()
    {
        return $this->getMaxPhotosCount() - $this->photos()->count();
    }

    /**
     * Accesor para foto principal
     */
    public function getPrimaryPhotoAttribute()
    {
        return $this->photos()->where('is_primary', true)->first();
    }

    // ==================== MÉTODOS HELPER - MODERACIÓN ====================

    /**
     * Verificar si el usuario está suspendido
     */
    public function isSuspended(): bool
    {
        return $this->actions()
            ->where('action_type', 'suspension')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Verificar si el usuario está baneado
     */
    public function isBanned(): bool
    {
        return $this->actions()
            ->where('action_type', 'ban')
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Obtener una ruta de almacenamiento ofuscada para archivos del usuario
     */
    public function getStoragePath(): string
    {
        // Usar un hash simple basado en el ID y la app_key para que no sea predecible
        $hash = substr(hash('sha256', $this->id.config('app.key')), 0, 12);

        return "profiles/{$hash}";
    }

    /**
     * Actualiza el perfil de forma coordinada (User + ProfileDetail)
     */
    public function updateProfile(array $data): bool
    {
        // 1. Actualizar datos base en la tabla 'users'
        $this->update([
            'city' => $data['city'] ?? $this->city,
            'birth_date' => $data['birth_date'] ?? $this->birth_date,
            'bio' => $data['bio'] ?? $this->bio,
        ]);

        // 2. Preparar datos para 'profile_details'
        $profileData = [
            'height' => $data['height'] ?? null,
            'body_type' => $data['body_type'] ?? null,
            'relationship_status' => $data['relationship_status'] ?? null,
            'children' => $data['children'] ?? null,
            'education' => $data['education'] ?? null,
            'occupation' => $data['occupation'] ?? null,
            'interests' => $data['interests'] ?? [],
            'languages' => $data['languages'] ?? [],
            'lifestyle' => $data['lifestyle'] ?? [],
            'looking_for' => $data['looking_for'] ?? null,
            'availability' => $data['availability'] ?? null,
            'is_private' => isset($data['is_private']) ? (bool) $data['is_private'] : false,
            'social_instagram' => $data['social_instagram'] ?? null,
            'social_whatsapp' => $data['social_whatsapp'] ?? null,
        ];

        // 3. Campos específicos
        if ($this->isSugarDaddy()) {
            $profileData = array_merge($profileData, [
                'income_range' => $data['income_range'] ?? null,
                'net_worth' => $data['net_worth'] ?? null,
                'industry' => $data['industry'] ?? null,
                'company_size' => $data['company_size'] ?? null,
                'travel_frequency' => $data['travel_frequency'] ?? null,
                'what_i_offer' => $data['what_i_offer'] ?? null,
                'mentorship_areas' => $data['mentorship_areas'] ?? [],
            ]);
        }

        if ($this->isSugarBaby()) {
            $profileData = array_merge($profileData, [
                'appearance_details' => $data['appearance_details'] ?? null,
                'personal_style' => $data['personal_style'] ?? null,
                'fitness_level' => $data['fitness_level'] ?? null,
                'aspirations' => $data['aspirations'] ?? null,
                'ideal_daddy' => $data['ideal_daddy'] ?? null,
            ]);
        }

        return (bool) $this->profileDetail()->updateOrCreate(
            ['user_id' => $this->id],
            $profileData
        );
    }

    /**
     * Accesor para suscripción activa
     */
    public function getActiveSubscriptionAttribute()
    {
        return $this->activeSubscription()->first();
    }
}
