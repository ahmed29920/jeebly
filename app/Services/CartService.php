<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\CartRepository;

class CartService
{
    protected $cartRepo;

    public function __construct(CartRepository $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function getCart(int $userId)
    {
        return $this->cartRepo->getUserCartItems($userId);
    }

    public function addProduct(int $userId, Product $product, $variantId = null)
    {
        return $this->cartRepo->addOrIncrement($userId, $product, $variantId);
    }

    public function updateProductQuantity(int $userId, Product $product, int $quantity, $variantId = null)
    {
        return $this->cartRepo->updateQuantity($userId, $product, $quantity, $variantId);
    }

    public function removeProduct(int $userId, Product $product, $variantId = null)
    {
        return $this->cartRepo->removeItem($userId, $product, $variantId);
    }

    public function clearCart(int $userId)
    {
        return $this->cartRepo->clearCart($userId);
    }
}
