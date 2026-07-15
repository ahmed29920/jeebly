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
            'recipient_type' => 'required|string|in:users,branches,deliveries',
            'audience' => 'required|string|in:all,selected',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'integer|exists:users,id',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => 'integer|exists:branches,id',
            'delivery_ids' => 'nullable|array',
            'delivery_ids.*' => 'integer|exists:deliveries,id',
            'send_in_app' => 'nullable|boolean',
            'send_push' => 'nullable|boolean',
            'title' => 'required|string|max:150',
            'body' => 'required|string|max:1000',
            'data_json' => 'nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'send_in_app' => $this->boolean('send_in_app'),
            'send_push' => $this->boolean('send_push'),
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $audience = (string) $this->input('audience');
            $recipientType = (string) $this->input('recipient_type');

            if ($audience === 'selected') {
                $ids = match ($recipientType) {
                    'branches' => $this->input('branch_ids'),
                    'deliveries' => $this->input('delivery_ids'),
                    default => $this->input('user_ids'),
                };

                $field = match ($recipientType) {
                    'branches' => 'branch_ids',
                    'deliveries' => 'delivery_ids',
                    default => 'user_ids',
                };

                $message = match ($recipientType) {
                    'branches' => __('Please select at least one branch.'),
                    'deliveries' => __('Please select at least one delivery.'),
                    default => __('Please select at least one user.'),
                };

                if (!is_array($ids) || count($ids) === 0) {
                    $validator->errors()->add($field, $message);
                }
            }

            if (!$this->boolean('send_in_app') && !$this->boolean('send_push')) {
                $validator->errors()->add('send_in_app', __('Select at least one channel: in-app or push.'));
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
