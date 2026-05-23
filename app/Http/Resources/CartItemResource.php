<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => $this->id,
            'product'  => new ProductResource($this->product),
            'variant'  => $this->variant ? new ProductVariantResource($this->variant) : null,
            'quantity' => $this->quantity,
            'subtotal' => $this->quantity * ($this->variant ? $this->variant->price : $this->product->price),
        ];
    }
}
