<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryWalletHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'delivery_id' => $this->delivery_id,
            'order_id' => $this->order_id,
            'amount' => (float) $this->amount,
            'type' => $this->type,
            'wallet_before' => (float) $this->wallet_before,
            'wallet_after' => (float) $this->wallet_after,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'order' => $this->whenLoaded('order', function () {
                return [
                    'id' => $this->order->id,
                    'uuid' => $this->order->uuid,
                    'status' => $this->order->status,
                    'final_total' => (float) $this->order->final_total,
                ];
            }),
        ];
    }
}
