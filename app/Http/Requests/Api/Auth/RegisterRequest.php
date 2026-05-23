<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Concerns\NormalizesPhoneInput;
use App\Rules\UniqueNormalizedPhone;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use NormalizesPhoneInput;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'                  => 'required|string|max:255',
            'phone'                 => ['required', 'string', 'max:255', new UniqueNormalizedPhone()],
            'email'                 => 'nullable|email|unique:users,email',
            'password'              => 'required|string',
            'password_confirmation' => 'required|string|same:password',
            'gender'                => 'nullable|in:male,female',
            'image'                 => 'nullable|image|max:2048',
            'invitation_code'       => 'nullable|exists:users,invitation_code',
        ];
    }
}
