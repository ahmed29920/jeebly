<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource  extends JsonResource
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
            'id'         => $this->id,
            'order_id'   => $this->order_id,
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'quantity'   => $this->quantity,
            'free_quantity' => $this->free_quantity,
            'price'      => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product'    => new ProductResource($this->whenLoaded('product')),
            'variant'    => new ProductVariantResource($this->whenLoaded('variant')),
        ];
    }
}
