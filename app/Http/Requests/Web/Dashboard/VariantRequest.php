<?php

namespace App\Http\Requests\Web\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class VariantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'type' => 'required|in:text,number,select,radio,checkbox',
            'is_required' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'options' => 'nullable|required_if:type,select|array',
            'options.*.id' => 'nullable|integer|exists:variant_options,id',
            'options.*.name.en' => 'required|string|max:255',
            'options.*.name.ar' => 'required|string|max:255',
            'options.*.code' => 'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'name.en.required' => 'The English name is required.',
            'name.ar.required' => 'The Arabic name is required.',
            'type.required' => 'The type is required.',
            'type.in' => 'The type must be one of the following: text, number, select, radio, checkbox.',
            'is_required.boolean' => 'The is required must be a boolean.',
            'is_active.boolean' => 'The is active must be a boolean.',
            'options.required_if' => 'The options are required when the type is select.',
            'options.array' => 'The options must be an array.',
            'options.*.name.en.required' => 'The English name is required for each option.',
            'options.*.name.ar.required' => 'The Arabic name is required for each option.',
            'options.*.code.required' => 'The code is required for each option.',
            'options.*.code.unique' => 'The code must be unique for each option.',
        ];
    }
}
