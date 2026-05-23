<?php

namespace App\Http\Requests\Web\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $branch = $this->route('branch');

        return [
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:branches,slug,' . $branch?->id,
            'address.en' => 'nullable|string|max:255',
            'address.ar' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];
    }
    public function messages()
    {
        return [
            'name.en.required' => 'The English name is required.',
            'name.ar.required' => 'The Arabic name is required.',
            'slug.required' => 'The slug is required.',
            'slug.unique' => 'The slug must be unique.',
            'address.en.required' => 'The English address is required.',
            'address.ar.required' => 'The Arabic address is required.',
            'phone.required' => 'The phone number is required.',
            'latitude.required' => 'The latitude is required.',
            'longitude.required' => 'The longitude is required.',
            'latitude.numeric' => 'The latitude must be a number.',
            'longitude.numeric' => 'The longitude must be a number.',
        ];
    }
}
