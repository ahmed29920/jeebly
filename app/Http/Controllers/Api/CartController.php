<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $user = Auth::user();

        $cartItems = $this->cartService->getCart($user->id);
        return CartItemResource::collection($cartItems);
    }

    public function add(Request $request, Product $product)
    {
        // $request->validate([
        //     'variant_id' => 'sometimes|required|exists:product_variants,id',
        // ]);
        if($product->type == 'variable'){
            $request->validate([
                'variant_id' => 'required|exists:product_variants,id',
            ]);
            $variantId = $request->variant_id;
        }else{
            $variantId = null;
        }
        $user = Auth::user();
        $cartItem = $this->cartService->addProduct($user->id, $product, $variantId);
        return new CartItemResource($cartItem);
    }

    public function updateQuantity(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            // 'variant_id' => 'sometimes|required|exists:product_variants,id',
        ]);
        if($product->type == 'variable'){
            $request->validate([
                'variant_id' => 'required|exists:product_variants,id',
            ]);
        }else{
            $request->variant_id = null;
        }
        $variantId = $request->variant_id ?? null;
        $user = Auth::user();
        $cartItem = $this->cartService->updateProductQuantity($user->id, $product, $request->quantity, $variantId);
        return new CartItemResource($cartItem);
    }

    public function remove(Request $request, Product $product)
    {
        $request->validate([
            'variant_id' => 'sometimes|required|exists:product_variants,id',
        ]);
        $variantId = $request->variant_id ?? null;
        $user = Auth::user();
        $this->cartService->removeProduct($user->id, $product, $variantId);
        return response()->json(['message' => __('messages.product_removed_from_cart')]);
    }

    public function clear()
    {
        $user = Auth::user();
        $this->cartService->clearCart($user->id);
        return response()->json(['message' => __('messages.cart_cleared')]);
    }
}
