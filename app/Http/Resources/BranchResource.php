<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();
        $address = $this->getTranslation('address', $locale);
        $name = $this->getTranslation('name', $locale);
        return [
            'id' => $this->id,
            'name' => $name,
            'slug' => $this->slug,
            'address' => $address,
            'phone' => $this->phone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
