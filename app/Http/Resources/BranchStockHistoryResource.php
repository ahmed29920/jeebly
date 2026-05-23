<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchStockHistoryResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product_variant_id' => $this->product_variant_id,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'type' => $this->type,
            'quantity_change' => $this->quantity_change,
            'quantity_before' => $this->quantity_before,
            'quantity_after' => $this->quantity_after,
            'notes' => $this->notes,
            'branch_id' => $this->branch_id,
            'product' => new ProductResource($this->product),
            'product_variant' => new ProductVariantResource($this->productVariant),
            'user' => new EmployeeResource($this->user),
            'order' => new OrderResource($this->order),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
