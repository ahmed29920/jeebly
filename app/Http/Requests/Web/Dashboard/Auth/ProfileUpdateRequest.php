<?php

namespace App\Http\Requests\Web\Dashboard\Auth;

use App\Http\Requests\Concerns\NormalizesPhoneInput;
use App\Rules\UniqueNormalizedPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    use NormalizesPhoneInput;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'phone' => ['required', 'string', 'max:20', new UniqueNormalizedPhone($userId)],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.*' => 'Name is required and must be a valid string.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'phone.required' => 'Phone number is required.',
            'phone.unique' => 'This phone number is already taken.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be jpeg, png, jpg, or gif format.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}
