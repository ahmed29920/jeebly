<?php

use App\Models\Setting;
use ArPHP\I18N\Arabic;

if (! function_exists('setting')) {
    function setting($key, $default = null)
    {
        static $settings = null;

        if ($settings == null) {
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
        if ($phone == null || trim($phone) == '') {
            return $phone;
        }

        $digits = preg_replace('/\D+/', '', trim($phone)) ?? '';

        if ($digits == '') {
            return trim($phone);
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '964')) {
            $digits = substr($digits, 3);
        }

        if (str_starts_with($digits, '7') && strlen($digits) == 10) {
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
        $original = $phone !== null ? trim($phone) : '';
        $normalized = normalize_phone($phone);

        if ($normalized == null || $normalized == '') {
            return $original !== '' ? [$original] : [];
        }

        $variants = [$normalized];

        if ($original !== '' && $original !== $normalized) {
            $variants[] = $original;
        }

        if (str_starts_with($normalized, '0')) {
            $withoutLeadingZero = substr($normalized, 1);
            $international = '964' . $withoutLeadingZero;

            $variants[] = '+' . $international;
            $variants[] = $international;
            $variants[] = $withoutLeadingZero;

            // Legacy formats where the leading 0 was kept after the country code.
            $variants[] = '964' . $normalized;
            $variants[] = '+964' . $normalized;
            $variants[] = '00964' . $normalized;
        }

        return array_values(array_unique($variants));
    }
}

if (! function_exists('format_phone_international')) {
    /**
     * Format stored local phone for display/API: 07XXXXXXXXX → +9647XXXXXXXXX
     */
    function format_phone_international(?string $phone): ?string
    {
        if ($phone == null || trim($phone) == '') {
            return $phone;
        }

        $trimmed = trim($phone);

        if (str_starts_with($trimmed, '+964')) {
            return $trimmed;
        }

        $normalized = normalize_phone($phone);

        if ($normalized == null || $normalized == '') {
            return $trimmed;
        }

        if (str_starts_with($normalized, '0')) {
            return '+964' . substr($normalized, 1);
        }

        if (str_starts_with($normalized, '964')) {
            return '+' . $normalized;
        }

        return $trimmed;
    }
}

if (! function_exists('arabic_pdf')) {
    /**
     * Shape Arabic text for Dompdf rendering (connect letters properly).
     */
    function arabic_pdf(?string $text): string
    {
        $text = (string) ($text ?? '');
        if ($text == '') {
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

if (! function_exists('unique_among_active')) {
    /**
     * Unique rule that ignores soft-deleted rows.
     */
    function unique_among_active(string $table, string $column, mixed $ignoreId = null): \Illuminate\Validation\Rules\Unique
    {
        $rule = \Illuminate\Validation\Rule::unique($table, $column)->whereNull('deleted_at');

        if ($ignoreId !== null && $ignoreId !== '') {
            $rule->ignore($ignoreId);
        }

        return $rule;
    }
}

if (! function_exists('release_soft_deleted_unique')) {
    /**
     * Rename slug/sku on trashed records so the value can be reused.
     */
    function release_soft_deleted_unique(string $modelClass, string $column, ?string $value, mixed $ignoreId = null): void
    {
        if ($value == null || $value == '') {
            return;
        }

        $query = $modelClass::withTrashed()->where($column, $value);

        if ($ignoreId !== null && $ignoreId !== '') {
            $query->where('id', '!=', $ignoreId);
        }

        foreach ($query->get() as $model) {
            if ($model->trashed()) {
                $suffix = '-deleted-' . $model->getKey();
                $current = $model->getAttribute($column);

                if ($current !== null && $current !== '' && ! str_ends_with($current, $suffix)) {
                    $model->{$column} = $current . $suffix;
                    $model->saveQuietly();
                }
            }
        }
    }
}
