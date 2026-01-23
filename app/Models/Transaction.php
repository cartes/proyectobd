<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'payment_method_id', // ⭐ NUEVA
        'type',
        'mp_payment_id',
        'amount',
        'currency',
        'status',
        'description',
        'external_reference',
        'metadata',
        'approved_at',      // ⭐ NUEVA
        'failed_reason',    // ⭐ NUEVA
        'ip_address',       // ⭐ NUEVA
        'user_agent',       // ⭐ NUEVA
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'json',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELACIONES ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * ⭐ NUEVA: Pertenece a un método de pago
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * ⭐ NUEVA: Puede tener muchos reembolsos
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    // ==================== SCOPES ====================

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ==================== MÉTODOS HELPER ====================

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * ⭐ NUEVA: Obtener monto reembolsado total
     */
    public function getTotalRefunded(): float
    {
        return $this->refunds()
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');
    }

    /**
     * ⭐ NUEVA: Obtener monto reembolsable (monto - reembolsado)
     */
    public function getRefundableAmount(): float
    {
        return $this->amount - $this->getTotalRefunded();
    }

    /**
     * ⭐ NUEVA: Verificar si puede ser reembolsado
     */
    public function canBeRefunded(): bool
    {
        return $this->isApproved() && $this->getRefundableAmount() > 0;
    }
}
