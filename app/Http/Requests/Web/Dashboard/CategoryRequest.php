<?php

namespace App\Http\Requests\Web\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $category = $this->route('category');

        return [
            'name.en'           => 'required|string|max:255',
            'name.ar'           => 'required|string|max:255',
            'slug'              => 'required|string|max:255|unique:categories,slug,' . $category?->id,
            'description.en'    => 'nullable|string',
            'description.ar'    => 'nullable|string',
            'image'             => $this->isMethod('post')
                                        ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                                        : 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order'        => 'required|integer|min:1',
            'is_active'         => 'boolean',
            'view_in_home'      => 'boolean',

            // SEO fields
            'meta_title.en'       => 'nullable|string|max:255',
            'meta_title.ar'       => 'nullable|string|max:255',
            'meta_description.en' => 'nullable|string|max:500',
            'meta_description.ar' => 'nullable|string|max:500',
            'meta_keywords.en'    => 'nullable|string|max:255',
            'meta_keywords.ar'    => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom messages (اختياري).
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
