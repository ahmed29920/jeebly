<?php

use App\Models\Setting;
use ArPHP\I18N\Arabic;

if (! function_exists('setting')) {
    function setting($key, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            $settings = cache()->rememberForever('settings.all', function () {
                return Setting::pluck('value', 'key')->toArray();
            });
        }
        
        return $settings[$key] ?? $default;
    }
}

if (! function_exists('currency_code')) {
    function currency_code(): string
    {
        return config('currency.code', 'IQD');
    }
}

if (! function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        return config('currency.symbol', 'د.ع');
    }
}

if (! function_exists('format_currency')) {
    function format_currency($amount, ?int $decimals = null): string
    {
        $decimals ??= (int) config('currency.decimals', 2);

        return number_format((float) $amount, $decimals) . ' ' . currency_symbol();
    }
}

if (! function_exists('normalize_phone')) {
    /**
     * Normalize Iraqi phone numbers to local format: 07XXXXXXXXX
     */
    function normalize_phone(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return $phone;
        }

        $digits = preg_replace('/\D+/', '', trim($phone)) ?? '';

        if ($digits === '') {
            return trim($phone);
        }

        if (str_starts_with($digits, '964')) {
            $digits = substr($digits, 3);
        }

        if (str_starts_with($digits, '7') && strlen($digits) === 10) {
            $digits = '0' . $digits;
        }

        return $digits;
    }
}

if (! function_exists('phone_lookup_variants')) {
    /**
     * Possible stored formats for the same Iraqi phone number.
     *
     * @return array<int, string>
     */
    function phone_lookup_variants(?string $phone): array
    {
        $normalized = normalize_phone($phone);

        if ($normalized === null || $normalized === '') {
            return [];
        }

        $variants = [$normalized];

        if (str_starts_with($normalized, '0')) {
            $withoutLeadingZero = substr($normalized, 1);
            $international = '964' . $withoutLeadingZero;

            $variants[] = '+' . $international;
            $variants[] = $international;
            $variants[] = $withoutLeadingZero;
        }

        return array_values(array_unique($variants));
    }
}

if (! function_exists('arabic_pdf')) {
    /**
     * Shape Arabic text for Dompdf rendering (connect letters properly).
     */
    function arabic_pdf(?string $text): string
    {
        $text = (string) ($text ?? '');
        if ($text === '') {
            return '';
        }

        // Only shape if the text contains Arabic characters.
        if (! preg_match('/\p{Arabic}/u', $text)) {
            return $text;
        }

        if (! class_exists(Arabic::class)) {
            return $text;
        }

        $arabic = new Arabic('Glyphs');

        return $arabic->utf8Glyphs($text);
    }
}
