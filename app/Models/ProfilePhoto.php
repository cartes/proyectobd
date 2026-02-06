<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ProfilePhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'photo_path',
        'thumbnail_path',
        'medium_path',
        'large_path',
        'file_size',
        'is_primary',
        'is_verified',
        'moderation_status',
        'order',
        'rejection_reason',
        'potential_nudity',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'is_verified' => 'boolean',
            'potential_nudity' => 'boolean',
        ];
    }

    /**
     * Relación con User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener URL completa de la foto
     */
    public function getUrlAttribute(): string
    {
        // Si la ruta ya es una URL, devolverla directamente
        if (filter_var($this->photo_path, FILTER_VALIDATE_URL)) {
            return $this->photo_path;
        }

        // Si no, usar Storage
        return Storage::url($this->photo_path);
    }

    /**
     * Get URL of thumbnail (150x150)
     */
    public function getThumbnailAttribute(): string
    {
        // Return optimized thumbnail if exists
        if ($this->thumbnail_path && Storage::disk('public')->exists($this->thumbnail_path)) {
            return Storage::url($this->thumbnail_path);
        }

        // Fallback to original
        return $this->url;
    }

    /**
     * Get URL of medium size (600x600)
     */
    public function getMediumAttribute(): string
    {
        // Return optimized medium if exists
        if ($this->medium_path && Storage::disk('public')->exists($this->medium_path)) {
            return Storage::url($this->medium_path);
        }

        // Fallback to original
        return $this->url;
    }

    /**
     * Get URL of large size (1200x1200)
     */
    public function getLargeAttribute(): string
    {
        // Return optimized large if exists
        if ($this->large_path && Storage::disk('public')->exists($this->large_path)) {
            return Storage::url($this->large_path);
        }

        // Fallback to original
        return $this->url;
    }

    /**
     * Scope para fotos aprobadas
     */
    public function scopeApproved($query)
    {
        return $query->where('moderation_status', 'approved');
    }

    /**
     * Scope para fotos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('moderation_status', 'pending');
    }

    /**
     * Scope ordenadas
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Establecer como foto principal
     */
    public function setAsPrimary(): void
    {
        // Quitar primary de todas las fotos del usuario
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Establecer esta como primary
        $this->update(['is_primary' => true]);
    }

    /**
     * Constantes
     */
    public const MAX_PHOTOS = 8;

    public const MAX_FILE_SIZE = 20480; // 20MB en KB

    public const ALLOWED_TYPES = ['jpg', 'jpeg', 'png', 'webp'];
}
