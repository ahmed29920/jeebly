<?php

namespace App\Http\Requests\Concerns;

trait NormalizesPhoneInput
{
    protected function prepareForValidation(): void
    {
        if ($this->has('phone') && $this->input('phone') !== null && $this->input('phone') !== '') {
            $this->merge([
                'phone' => normalize_phone($this->input('phone')),
            ]);
        }

        if ($this->has('email') && $this->input('email') === '') {
            $this->merge([
                'email' => null,
            ]);
        }
    }
}
