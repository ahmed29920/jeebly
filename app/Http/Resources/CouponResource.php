<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'code'            => $this->code,
            'description'     => $this->description,
            'type'            => $this->type,
            'coupon_discount_value'  => $this->coupon_discount_value,
            'min_cart_amount' => $this->min_cart_amount,
            'usage_limit'     => $this->usage_limit,
            'used_count'      => $this->used_count,
            'start_date'      => $this->start_date,
            'end_date'        => $this->end_date,
            'is_active'       => $this->is_active,
        ];
    }
}
