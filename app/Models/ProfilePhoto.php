<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProfilePhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'photo_path',
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
     * Obtener URL del thumbnail (puedes implementar thumbnails después)
     */
    public function getThumbnailAttribute(): string
    {
        return $this->url; // Por ahora retorna la misma foto
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
    public const MAX_FILE_SIZE = 5120; // 5MB en KB
    public const ALLOWED_TYPES = ['jpg', 'jpeg', 'png', 'webp'];
}
