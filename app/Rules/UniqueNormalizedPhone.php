<?php

namespace App\Rules;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueNormalizedPhone implements ValidationRule
{
    public function __construct(protected ?int $ignoreUserId = null)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $existing = app(UserRepository::class)->findByPhone((string) $value);

        if ($existing && $existing->id !== $this->ignoreUserId) {
            $fail(__('messages.phone_already_registered'));
        }
    }
}
