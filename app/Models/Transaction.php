<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'type',
        'mp_payment_id',
        'amount',
        'currency',
        'status',
        'description',
        'external_reference',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Relación: una transacción pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación: una transacción pertenece a una suscripción (opcional)
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
