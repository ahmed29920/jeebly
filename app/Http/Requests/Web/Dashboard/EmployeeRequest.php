<?php

namespace App\Http\Requests\Web\Dashboard;

use App\Http\Requests\Concerns\NormalizesPhoneInput;
use App\Models\User;
use App\Rules\UniqueNormalizedPhone;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property User|null $employee
 */
class EmployeeRequest extends FormRequest
{
    use NormalizesPhoneInput;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $employeeId,
            'phone' => ['required', 'string', new UniqueNormalizedPhone($employeeId)],
            'password' => $this->isMethod('POST') ? 'required|string|min:6' : 'nullable|string|min:6',
            'branch_id' => 'required|integer|exists:branches,id',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name is required.',
            'email.unique' => 'The email has already been taken.',
            'phone.required' => 'The phone is required.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 6 characters.',
            'branch_id.required' => 'The branch is required.',
            'branch_id.exists' => 'The selected branch is invalid.',
        ];
    }
}
