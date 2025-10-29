<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ProfileDetail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        ];
    }

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
     * Obtener foto principal
     */
    public function primaryPhoto()
    {
        return $this->hasOne(ProfilePhoto::class)->where('is_primary', true);
    }

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
        // Usuarios a los que yo di like
        return $this->likes()
            // Que TAMBIÉN me dieron like a mí
            ->whereIn('users.id', function ($query) {
                $query->select('user_id')
                    ->from('likes')
                    ->where('liked_user_id', $this->id);
            });
    }

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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    public function isPremium(): bool
    {
        return $this->is_premium;
    }

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
     * Verificar si puede subir más fotos
     */
    public function canUploadMorePhotos()
    {
        return $this->photos()->count() < ProfilePhoto::MAX_PHOTOS;
    }

    /**
     * Obtener cantidad de fotos disponibles
     */
    public function remainingPhotosCount()
    {
        return ProfilePhoto::MAX_PHOTOS - $this->photos()->count();
    }

    /**
     * Accesor para foto principal
     */
    public function getPrimaryPhotoAttribute()
    {
        return $this->photos()->where('is_primary', true)->first();
    }

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
    
}
