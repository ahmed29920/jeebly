<?php

namespace App\Http\Requests\Web\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::in(['product', 'category', 'cart', 'shipping'])],
            'discount_type' => ['required', Rule::in(['percent', 'fixed', 'free_shipping', 'bogo'])],

            'discount_value' => 'nullable|numeric|min:0',
            'condition' => 'nullable|array',
            'condition.product_id' => 'nullable|exists:products,id',
            'condition.category_id' => 'nullable|exists:categories,id',
            'condition.min_cart_amount' => 'nullable|numeric|min:0',
            'condition.free_shipping' => 'boolean',
            'condition.bogo' => 'boolean',
            'condition.free_shipping_value' => 'nullable|numeric|min:0',
            'condition.bogo_value' => 'nullable|numeric|min:0',
            'condition.bogo_product_id' => 'nullable|exists:products,id',
            'condition.bogo_category_id' => 'nullable|exists:categories,id',
            'condition.bogo_min_cart_amount' => 'nullable|numeric|min:0',
            'condition.bogo_free_shipping' => 'boolean',
            'condition.bogo_free_shipping_value' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
