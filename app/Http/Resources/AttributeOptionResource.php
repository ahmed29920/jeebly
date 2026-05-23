<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeOptionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->id,
            'value' => $this->value,
        ];
    }
}
