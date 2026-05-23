<?php

namespace App\Services\ProductTypes;

use App\Contracts\ProductPriceStockInterface;
use App\Models\Product;

class SimpleProduct implements ProductPriceStockInterface
{
    public function __construct(private Product $product) {}

    public function price()
    {
        return $this->product->price;
    }

    public function stock()
    {
        // Check if branch stocks exist for this product
        $hasBranchStocks = $this->product->branchProductStocks()->exists();

        if ($hasBranchStocks) {
            // If branch stocks exist, use the sum of all branch stocks
            return $this->product->branchProductStocks()->sum('quantity');
        }

        // Otherwise, fall back to the product's stock field
        return $this->product->stock ?? 0;
    }
}
