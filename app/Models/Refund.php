<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'type',
        'amount',
        'reason',
        'mercado_pago_refund_id',
        'mercado_pago_original_payment_id',
        'status',
        'requested_at',
        'processed_at',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'metadata' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELACIONES ====================

    /**
     * Pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pertenece a una transacción
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope: obtener reembolsos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'requested');
    }

    /**
     * Scope: obtener reembolsos aprobados
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: obtener reembolsos completados
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: obtener reembolsos rechazados
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: obtener reembolsos de usuario específico
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope: obtener reembolsos solicitados hace X días
     */
    public function scopeRequestedWithinDays($query, int $days = 7)
    {
        return $query->where('requested_at', '>=', now()->subDays($days));
    }

    // ==================== MÉTODOS HELPER ====================

    /**
     * Verificar si está pendiente
     */
    public function isPending(): bool
    {
        return $this->status === 'requested';
    }

    /**
     * Verificar si está aprobado
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Verificar si está completado
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Verificar si está rechazado
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Aprobación manual del reembolso
     */
    public function approve(string $notes = null): bool
    {
        return $this->update([
            'status' => 'approved',
            'notes' => $notes,
        ]);
    }

    /**
     * Rechazar reembolso
     */
    public function reject(string $reason): bool
    {
        return $this->update([
            'status' => 'rejected',
            'notes' => $reason,
        ]);
    }

    /**
     * Marcar como completado
     */
    public function complete(): bool
    {
        return $this->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);
    }

    /**
     * Obtener descripción del tipo de reembolso
     */
    public function getTypeDescription(): string
    {
        return match ($this->type) {
            'full' => 'Reembolso Completo',
            'partial' => 'Reembolso Parcial',
            default => 'Desconocido',
        };
    }

    /**
     * Obtener descripción del estado
     */
    public function getStatusDescription(): string
    {
        return match ($this->status) {
            'requested' => 'Solicitado',
            'approved' => 'Aprobado',
            'rejected' => 'Rechazado',
            'completed' => 'Completado',
            default => 'Desconocido',
        };
    }

    /**
     * Obtener días desde que se solicitó
     */
    public function getDaysSinceRequest(): int
    {
        return $this->requested_at->diffInDays(now());
    }

    /**
     * Verificar si puede ser procesado (está aprobado y se esperó suficiente tiempo)
     */
    public function canBeProcessed(): bool
    {
        return $this->isApproved() && $this->getDaysSinceRequest() >= 1;
    }
}
