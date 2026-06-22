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
            default => str_ends_with($key, '_en') || str_ends_with($key, '_ar')
                ? 'html'
                : 'text',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function policyDefinitions(): array
    {
        return [
            'terms_and_conditions' => 'Terms and Conditions',
            'privacy_policy' => 'Privacy Policy',
            'refund_policy' => 'Refund Policy',
            'about_the_app' => 'About the App',
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function policySettingKeys(): array
    {
        $keys = [];

        foreach (array_keys(self::policyDefinitions()) as $policy) {
            $keys[] = "{$policy}_en";
            $keys[] = "{$policy}_ar";
        }

        return $keys;
    }

    /**
     * @return array<string, array{en: ?string, ar: ?string}>
     */
    public static function policiesPayload(?iterable $settings = null): array
    {
        $settings = $settings ?? self::all();
        $collection = $settings instanceof \Illuminate\Support\Collection
            ? $settings
            : collect($settings);

        $payload = [];

        foreach (array_keys(self::policyDefinitions()) as $policy) {
            $payload[$policy] = [
                'en' => $collection->firstWhere('key', "{$policy}_en")?->value,
                'ar' => $collection->firstWhere('key', "{$policy}_ar")?->value,
            ];
        }

        return $payload;
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
