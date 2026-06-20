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
            'id'                      => $this->id,
            'uuid'                    => $this->uuid,
            'user_id'                 => $this->user_id,
            'status'                  => $this->status,
            'total'                   => (float) $this->total,
            'shipping_cost'           => (float) ($this->shipping_cost ?? 0),
            'service_fee'             => (float) ($this->service_fee ?? 0),
            'coupon_id'               => $this->coupon_id,
            'coupon_discount_value'   => (float) ($this->coupon_discount_value ?? 0),
            'offer_id'                => $this->offer_id,
            'offer_discount_value'    => (float) ($this->offer_discount_value ?? 0),
            'points_discount_value'   => (float) ($this->points_discount_value ?? 0),
            'final_total'             => (float) $this->final_total,
            'note'                    => $this->note,
            'payment_status'          => $this->payment_status,
            'payment_method'          => $this->payment_method,
            'shipping_address_id'     => $this->shipping_address_id,
            'billing_address_id'      => $this->billing_address_id,
            'branch_id'               => $this->branch_id,
            'delivery_id'             => $this->delivery_id,
            'route'                   => $this->route,
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
            'deleted_at'              => $this->deleted_at,
            'delivery'                => new DeliveryResource($this->delivery),
            'items'                   => OrderItemResource::collection($this->whenLoaded('items')),
            'user'                    => new UserResource($this->whenLoaded('user')),
            'shipping_address'        => new AddressResource($this->whenLoaded('shippingAddress')),
            'billing_address'         => new AddressResource($this->whenLoaded('billingAddress')),
            'coupon'                  => new CouponResource($this->whenLoaded('coupon')),
            'offer'                   => new OfferResource($this->whenLoaded('offer')),
        ];
    }
}
