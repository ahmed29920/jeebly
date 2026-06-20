<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function typeForKey(string $key): string
    {
        return match ($key) {
            'app_logo', 'app_icon' => 'image',
            default => 'text',
        };
    }

    public static function getValue($key)
    {
        return self::where('key', $key)->first()->value;
    }

    protected static function booted(): void
    {
        static::saved(function () {
            cache()->forget('settings.all');
        });

        static::deleted(function () {
            cache()->forget('settings.all');
        });
    }
}
