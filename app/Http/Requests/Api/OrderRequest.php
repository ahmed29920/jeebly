<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'shipping_address_id' => 'nullable|exists:addresses,id',
            'billing_address_id'  => 'nullable|exists:addresses,id',
            'branch_id'           => 'sometimes|required_if:shipping_address_id,null|exists:branches,id',
            'payment_method'      => 'required|string',
            'coupon_code'         => 'nullable|string|exists:coupons,code',
            'note'                => 'nullable|string',
            'use_points'          => 'sometimes|boolean',
        ];
    }
}
