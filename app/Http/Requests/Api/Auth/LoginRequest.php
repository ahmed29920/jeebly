<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Concerns\NormalizesPhoneInput;
use App\Rules\PhoneExists;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use NormalizesPhoneInput;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone'     => ['required', 'string', 'max:25', new PhoneExists()],
            'password'  => 'required|string|max:255',
        ];
    }
}
