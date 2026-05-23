<?php

namespace App\Factories;

use App\Contracts\ProductPriceStockInterface;
use App\Models\Product;
use App\Services\ProductTypes\SimpleProduct;
use App\Services\ProductTypes\VariableProduct;

class ProductPriceStockFactory
{
    public static function make(Product $product): ProductPriceStockInterface
    {
        return $product->type == 'variable'
            ? new VariableProduct($product)
            : new SimpleProduct($product);
    }
}
