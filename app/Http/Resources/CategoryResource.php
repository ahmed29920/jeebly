<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id'               => $this->id,
            'name'             => $this->name,
            'description'      => $this->description,
            'image'            => $this->image_url,
            'sort_order'       => $this->sort_order,
            'view_in_home'     => $this->view_in_home,
            'parent_id'        => $this->parent_id,
            'children_count'   => $this->children()->count(),
            'products'         => ProductResource::collection($this->whenLoaded('products')),
            'children'         => CategoryResource::collection($this->whenLoaded('children')),
            'products_count'   => $this->products()->count(),

        ];
    }
}
