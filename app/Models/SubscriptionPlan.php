<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'amount',
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
    ];

    // Relación: un plan puede tener muchas suscripciones
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
