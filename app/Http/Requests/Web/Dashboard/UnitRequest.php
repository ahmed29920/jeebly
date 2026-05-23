<?php

namespace App\Http\Requests\Web\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $unit = $this->route('unit');

        return [
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'code.en' => 'required|string|max:50',
            'code.ar' => 'required|string|max:50',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [
            'name.en.required' => 'English name is required.',
            'name.ar.required' => 'Arabic name is required.',
            'code.en.required' => 'English code is required.',
            'code.ar.required' => 'Arabic code is required.',
        ];
    }
}

