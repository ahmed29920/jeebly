<?php

namespace App\Http\Requests\Api\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class WalletRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'     => 'required|in:withdrawal,settlement',
            'amount'   => 'required_if:type,withdrawal|nullable|numeric|min:0.01',
            'order_id' => 'required_if:type,settlement|nullable|exists:orders,id',
            'notes'    => 'nullable|string|max:1000',
        ];
    }
}
