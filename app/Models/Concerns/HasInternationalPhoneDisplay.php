<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasInternationalPhoneDisplay
{
    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => format_phone_international($value),
            set: fn (?string $value) => normalize_phone($value),
        );
    }
}
