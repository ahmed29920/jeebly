<?php

namespace App\Repositories;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartRepository
{
    protected $model;

    public function __construct(CartItem $cartItem)
    {
        $this->model = $cartItem;
    }

    public function getUserCartItems(int $userId)
    {
        return $this->model->with('product.unit')->where('user_id', $userId)->get();
    }

    public function addOrIncrement(int $userId, Product $product, $variantId = null)
    {
        if($variantId){
            $cartItem = $this->model->firstOrCreate([
            'user_id' => $userId,
            'product_id' => $product->id,
            'variant_id' => $variantId,
        ]);
        }else{
            $cartItem = $this->model->firstOrCreate([
                'user_id' => $userId,
                'product_id' => $product->id,
            ]);
        }
        if (!$cartItem->wasRecentlyCreated) {
            $cartItem->increment('quantity');
        }else {
            $cartItem->quantity = 1;
            $cartItem->save();
        }
        return $cartItem;
    }

    public function updateQuantity(int $userId, Product $product, int $quantity, $variantId = null)
    {
        if ($variantId) {
            $cartItem = $this->model->where('user_id', $userId)->where('product_id', $product->id)->where('variant_id', $variantId)->firstOrFail();
        } else {
            $cartItem = $this->model->where('user_id', $userId)->where('product_id', $product->id)->firstOrFail();
        }
        $cartItem->update(['quantity' => $quantity]);
        return $cartItem;
    }

    public function removeItem(int $userId, Product $product, $variantId = null)
    {
        if ($variantId) {
            return $this->model->where('user_id', $userId)->where('product_id', $product->id)->where('variant_id', $variantId)->delete();
        } else {
            return $this->model->where('user_id', $userId)->where('product_id', $product->id)->delete();
        }
    }

    public function clearCart(int $userId)
    {
        return $this->model->where('user_id', $userId)->delete();
    }
}
