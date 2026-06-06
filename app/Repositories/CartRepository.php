<?php

namespace App\Repositories;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;

class CartRepository
{
    protected $model;

    public function __construct(CartItem $cartItem)
    {
        $this->model = $cartItem;
    }

    public function getUserCartItems(int $userId)
    {
        $this->purgeInvalidItems($userId);

        return $this->model
            ->with(['product.unit', 'product.images', 'variant'])
            ->where('user_id', $userId)
            ->whereHas('product', fn (Builder $q) => $q->where('is_active', true))
            ->get()
            ->filter(function (CartItem $item) {
                if ($item->product->type === 'variable') {
                    return $item->variant !== null && $item->variant->is_active;
                }

                return true;
            })
            ->values();
    }

    public function purgeInvalidItems(int $userId): void
    {
        $this->model->where('user_id', $userId)->get()->each(function (CartItem $item) {
            $product = Product::withTrashed()->find($item->product_id);

            if (! $product || $product->trashed() || ! $product->is_active) {
                $item->delete();

                return;
            }

            if ($product->type !== 'variable') {
                return;
            }

            if (! $item->variant_id) {
                $item->delete();

                return;
            }

            $variant = ProductVariant::withTrashed()->find($item->variant_id);

            if (! $variant || $variant->trashed() || ! $variant->is_active) {
                $item->delete();
            }
        });
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
        return $cartItem->load(['product.unit', 'product.images', 'variant']);
    }

    public function updateQuantity(int $userId, Product $product, int $quantity, $variantId = null)
    {
        if ($variantId) {
            $cartItem = $this->model->where('user_id', $userId)->where('product_id', $product->id)->where('variant_id', $variantId)->firstOrFail();
        } else {
            $cartItem = $this->model->where('user_id', $userId)->where('product_id', $product->id)->firstOrFail();
        }
        $cartItem->update(['quantity' => $quantity]);

        return $cartItem->load(['product.unit', 'product.images', 'variant']);
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
