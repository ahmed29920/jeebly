<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'order_id'         => $this->order_id,
            'payment_method'   => $this->payment_method,
            'amount'           => $this->amount,
            'currency'         => $this->currency,
            'status'           => $this->status,
            'order'            => new OrderResource($this->whenLoaded('order'))
        ];
    }
}
