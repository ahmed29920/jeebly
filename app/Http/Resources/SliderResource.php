<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image_url,
            'is_active' => $this->is_active,
        ];
    }
}