<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
            'quantity' => $this->quantity,
            'expected_at' => $this->expected_at,
            'notified' => $this->notified,
            'status' => $this->status,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
