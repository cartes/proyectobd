<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAction extends Model
{
    protected $fillable = [
        'user_id',
        'action_type',
        'reason',
        'initiated_by',
        'expires_at',
        'is_active',
        'resolved_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'resolved_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function initiatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBans($query)
    {
        return $query->where('action_type', 'ban')->active();
    }

    public function scopeSuspensions($query)
    {
        return $query->where('action_type', 'suspension')->active();
    }

    // Verificar si la acción está expirada
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // Resolver acción
    public function resolve()
    {
        $this->update([
            'is_active' => false,
            'resolved_at' => now(),
        ]);
    }
}
