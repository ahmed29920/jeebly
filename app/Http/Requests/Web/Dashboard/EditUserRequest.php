<?php

namespace App\Http\Requests\Web\Dashboard;

use App\Http\Requests\Concerns\NormalizesPhoneInput;
use App\Rules\UniqueNormalizedPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EditUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone' => ['required', 'string', new UniqueNormalizedPhone($this->user->id)],
            'role' => 'nullable|string|in:user,admin',
            'assigned_role' => 'nullable|string|exists:roles,name',
        ];
    }
}
