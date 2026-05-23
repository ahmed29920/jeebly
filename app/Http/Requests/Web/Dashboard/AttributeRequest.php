<?php

namespace App\Http\Requests\Web\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
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
        return [
            'code'            => 'required|string|max:255|unique:attributes,code,' . $this->route('attribute')?->id,
            'name'            => 'required|array',
            'name.en'         => 'required|string|max:255',
            'name.ar'         => 'required|string|max:255',
            'type'            => 'required|in:text,textarea,select,multiselect,boolean,number,date',
            'is_active'       => 'nullable|boolean',
            'is_required'     => 'nullable|boolean',
            'is_filterable'   => 'nullable|boolean',

            // for select - multi select
            'options'         => 'nullable|array',
            'options.*.value' => 'required_with:options|string|max:255',
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
            'slug.required'    => 'Slug is required.',
            'slug.unique'      => 'Slug must be unique.',
        ];
    }
}
