<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'is_filterable' => (bool) $this->is_filterable,

            
            'value' => $this->when(
                $this->pivot?->value,
                fn() => $this->pivot->value
            ),

            
            'selected_option' => $this->when(
                $this->pivot?->attribute_option_id,
                fn () => new AttributeOptionResource(
                    $this->options->firstWhere('id', $this->pivot->attribute_option_id)
                )
            ),

            'options' => AttributeOptionResource::collection($this->options),

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
