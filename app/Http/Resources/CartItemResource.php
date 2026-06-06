<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $price = $this->variant?->price ?? $this->product?->price ?? 0;

        return [
            'id'       => $this->id,
            'product'  => $this->product ? new ProductResource($this->product) : null,
            'variant'  => $this->variant ? new ProductVariantResource($this->variant) : null,
            'quantity' => $this->quantity,
            'subtotal' => $this->quantity * $price,
        ];
    }
}
