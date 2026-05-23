<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantValueResource extends JsonResource
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
            'id' => $this->id,
            'value' => $this->value,
            'variant_option_id' => $this->variant_option_id,
            'variant_option' => new VariantOptionResource($this->whenLoaded('variantOption')),
        ];
    }
}

