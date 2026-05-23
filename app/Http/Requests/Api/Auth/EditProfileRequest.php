<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Concerns\NormalizesPhoneInput;
use App\Rules\UniqueNormalizedPhone;
use Illuminate\Foundation\Http\FormRequest;

class EditProfileRequest extends FormRequest
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
            'phone'                 => ['required', 'string', 'max:255', new UniqueNormalizedPhone($this->user()->id)],
            'email'                 => 'nullable|string|email|max:255|unique:users,email,' . $this->user()->id,
            'image'                 => 'nullable|image|max:2048',
            'gender'                => 'nullable|in:male,female',
        ];
    }
}
