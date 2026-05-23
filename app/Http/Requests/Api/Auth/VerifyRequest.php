<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Concerns\NormalizesPhoneInput;
use App\Rules\PhoneExists;
use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
{
    use NormalizesPhoneInput;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => ['required', 'string', 'max:25', new PhoneExists()],
            'code'  => 'required|digits:6',
        ];
    }
}
