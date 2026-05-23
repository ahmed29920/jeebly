<?php

namespace App\Rules;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneExists implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! app(UserRepository::class)->findByPhone((string) $value)) {
            $fail(__('validation.exists', ['attribute' => $attribute]));
        }
    }
}
