<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'amount',
        'sale_amount',
        'sale_expires_at',
        'currency',
        'frequency',
        'frequency_type',
        'description',
        'features',
        'is_active',
        'target_user_type',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
        'sale_amount' => 'decimal:2',
        'sale_expires_at' => 'datetime',
    ];

    /**
     * Get the current effective price (sale price if active, otherwise base amount)
     */
    public function getCurrentPrice(): float
    {
        if ($this->isOnSale()) {
            return (float) $this->sale_amount;
        }

        return (float) $this->amount;
    }

    /**
     * Check if the plan is currently on sale
     */
    public function isOnSale(): bool
    {
        return !is_null($this->sale_amount) &&
            ($this->sale_expires_at === null || $this->sale_expires_at->isFuture());
    }

    // Relación: un plan puede tener muchas suscripciones
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
