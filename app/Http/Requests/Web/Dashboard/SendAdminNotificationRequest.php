<?php

namespace App\Http\Requests\Web\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SendAdminNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'audience' => 'required|string|in:all,selected',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'integer|exists:users,id',

            'title' => 'required|string|max:150',
            'body' => 'required|string|max:1000',
            'data_json' => 'nullable|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $audience = (string) $this->input('audience');
            $userIds = $this->input('user_ids');

            if ($audience === 'selected') {
                if (!is_array($userIds) || count($userIds) === 0) {
                    $validator->errors()->add('user_ids', __('Please select at least one user.'));
                }
            }

            $dataJson = $this->input('data_json');
            if (is_string($dataJson) && trim($dataJson) !== '') {
                json_decode($dataJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $validator->errors()->add('data_json', __('Data must be valid JSON.'));
                }
            }
        });
    }
}

