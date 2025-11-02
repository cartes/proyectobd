<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedWord extends Model
{
    protected $fillable = [
        'word',
        'severity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
