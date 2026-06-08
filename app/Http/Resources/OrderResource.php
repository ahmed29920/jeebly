<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'user_id'            => $this->user_id,
            'status'             => $this->status,
            'total'              => $this->total,
            'discount'           => $this->discount,
            'shipping_cost'      => $this->shipping_cost,
            'service_fee'        => $this->service_fee,
            'coupon_id'          => $this->coupon_id,
            'coupon_discount_value'     => $this->coupon_discount_value,
            'final_total'        => $this->final_total,
            'note'               => $this->note,
            'payment_status'     => $this->payment_status,
            'payment_method'     => $this->payment_method,
            'shipping_address_id'=> $this->shipping_address_id,
            'billing_address_id' => $this->billing_address_id,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
            'deleted_at'         => $this->deleted_at,
            'offer_id'           => $this->offer_id,
	    'route'              => $this->route,
            'offer_discount_value' => $this->offer_discount_value,
            'delivery'           => new DeliveryResource($this->delivery),
            'items'              => OrderItemResource::collection($this->whenLoaded('items')),
            'user'               =>  new UserResource($this->whenLoaded('user')),
            'shipping_address'   => new AddressResource($this->whenLoaded('shippingAddress')),
            'billing_address'    => new AddressResource($this->whenLoaded('billingAddress')),
            'coupon'             => new CouponResource($this->whenLoaded('coupon')),
        ];
    }
}
