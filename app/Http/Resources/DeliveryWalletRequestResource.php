<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryWalletRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'delivery_id'  => $this->delivery_id,
            'type'         => $this->type,
            'amount'       => (float) $this->amount,
            'order_id'     => $this->order_id,
            'status'       => $this->status,
            'notes'        => $this->notes,
            'admin_notes'  => $this->when($this->admin_notes, $this->admin_notes),
            'processed_at' => $this->processed_at?->toIso8601String(),
            'created_at'   => $this->created_at?->toIso8601String(),
            'updated_at'   => $this->updated_at?->toIso8601String(),
            'order'        => $this->whenLoaded('order', function () {
                return [
                    'id'             => $this->order->id,
                    'uuid'           => $this->order->uuid,
                    'status'         => $this->order->status,
                    'payment_method' => $this->order->payment_method,
                    'payment_status' => $this->order->payment_status,
                    'final_total'    => (float) $this->order->final_total,
                ];
            }),
            'delivery'     => $this->whenLoaded('delivery', function () {
                return [
                    'id'     => $this->delivery->id,
                    'wallet' => (float) ($this->delivery->wallet ?? 0),
                    'user'   => $this->when($this->delivery->relationLoaded('user'), function () {
                        return [
                            'id'    => $this->delivery->user->id,
                            'name'  => $this->delivery->user->name,
                            'email' => $this->delivery->user->email,
                            'phone' => $this->delivery->user->phone,
                        ];
                    }),
                ];
            }),
        ];
    }
}
