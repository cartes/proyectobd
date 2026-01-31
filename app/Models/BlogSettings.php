<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogSettings extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get multiple settings at once
     */
    public static function getMultiple(array $keys)
    {
        return static::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Set multiple settings at once
     */
    public static function setMultiple(array $settings)
    {
        foreach ($settings as $key => $value) {
            static::set($key, $value);
        }
    }
}
