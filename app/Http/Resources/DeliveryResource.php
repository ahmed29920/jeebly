<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
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
            'user_id' => $this->user_id,
            'branch_id' => $this->branch_id,
            'is_online' => $this->is_online,
            'plate_number' => $this->plate_number,
            'vehicle_name' => $this->vehicle_name,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_color' => $this->vehicle_color,
            'wallet' => $this->wallet,
            'documents' => $this->when($this->documents, function () {
                return collect($this->documents)->map(function ($document) {
                    return asset('storage/' . $document);
                })->toArray();
            }),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'user' => new UserResource($this->user),
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

