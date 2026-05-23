<?php

namespace App\Http\Requests\Web\Dashboard;

use App\Http\Requests\Concerns\NormalizesPhoneInput;
use App\Rules\UniqueNormalizedPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateUserRequest extends FormRequest
{
    use NormalizesPhoneInput;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'string', new UniqueNormalizedPhone()],
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:user,admin',
            'assigned_role' => 'nullable|string|exists:roles,name',
        ];
    }
}
