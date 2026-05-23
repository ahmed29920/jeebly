<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $couponService;
    protected $cartService;

    public function __construct(CouponService $couponService, CartService $cartService)
    {
        $this->couponService = $couponService;
        $this->cartService = $cartService;
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:coupons,code',
        ]);

        $cart = $this->cartService->getCart($request->user()->id);

        // get subtotal
        $subtotal = $cart->sum(function ($item) {
            $price = $item->product->price;
            return $price * $item->quantity;
        });

        $coupon = $this->couponService->validateCoupon($request->code, $subtotal);

        // apply coupon to cart
        $cartWithDiscount = $this->couponService->applyCoupon(
            $coupon,
            $subtotal,
            CartItemResource::collection($cart)
        );

        return response()->json($cartWithDiscount);
    }
}
