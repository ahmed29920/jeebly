<?php

namespace App\Http\Requests\Web\Dashboard;

use App\Http\Requests\Concerns\NormalizesPhoneInput;
use App\Models\Delivery;
use App\Rules\UniqueNormalizedPhone;
use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
{
    use NormalizesPhoneInput;

    /**
     * @var Delivery|null
     */
    protected $delivery;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $this->delivery = $this->route('delivery');
        $userId = $this->delivery ? $this->delivery->user_id : null;

        return [
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email,' . $userId,
            'phone'         => ['required', 'string', new UniqueNormalizedPhone($userId)],
            'password'      => $this->isMethod('POST') ? 'required|string|min:6' : 'nullable|string|min:6',
            'branch_id'     => 'nullable|integer|exists:branches,id',
            'is_online'     => 'nullable|boolean',
            'plate_number'  => 'required|string|max:255',
            'vehicle_name'  => 'required|string|max:255',
            'vehicle_type'  => 'required|string|max:255',
            'vehicle_color' => 'required|string|max:255',
            'wallet'        => 'required|numeric|min:0',
            'documents'     => 'nullable|array',
            'documents.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'zone_ids' => 'nullable|array',
            'zone_ids.*' => 'integer|exists:zones,id',
        ];
    }
}
