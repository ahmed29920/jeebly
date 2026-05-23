<?php

namespace App\Http\Requests\Web\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CouponRequest extends FormRequest
{
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
            'code' => 'required|string|unique:coupons,code,' . $this->route('coupon')?->id,
            'description.en'    => 'nullable|string',
            'description.ar'    => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'coupon_discount_value' => 'required|numeric|min:0',
            'min_cart_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ];
    }
}
