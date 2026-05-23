<?php

namespace App\Services;

use App\Models\Coupon;
use App\Repositories\CouponRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CouponService
{
    public function __construct(
        protected CouponRepository $couponRepo
    ) {}
    public function all()
    {
        return $this->couponRepo->all();
    }
    public function find($id)
    {
        return $this->couponRepo->find($id);
    }
    public function findByCode($code)
    {
        return $this->couponRepo->findByCode($code);
    }
    public function store(array $data)
    {
        return $this->couponRepo->create($data);
    }
    public function update(Coupon $coupon, array $data)
    {
        $this->couponRepo->update($coupon, $data);

        return $coupon->refresh();
    }

    public function delete(Coupon $coupon)
    {
        return $this->couponRepo->delete($coupon);
    }

    public function bulkAction(array $ids, string $action)
    {
        return $this->couponRepo->bulkAction($ids, $action);
    }

    public function applyCoupon(Coupon $coupon, float $cartTotal, $cartItems = [])
    {
        $discount = $this->calculateDiscount($coupon, $cartTotal);

        return [
            'coupon'   => [
                'id'    => $coupon->id,
                'code'  => $coupon->code,
                'type'  => $coupon->type,
                'value' => $coupon->discount_value,
            ],
            'items'     => $cartItems,
            'subtotal'  => $cartTotal,
            'discount'  => $discount,
            'total'     => max(0, $cartTotal - $discount),
        ];
    }


    public function validateCoupon($code, $cartTotal)
    {
        $coupon = $this->couponRepo->findByCode($code);
        $user = Auth::user();
        if (! $coupon || ! $coupon->is_active) {
            throw ValidationException::withMessages(['code' => __('messages.coupon_invalid_or_inactive')]);
        }

        if ($coupon->start_date && now()->lt($coupon->start_date)) {
            throw ValidationException::withMessages(['code' => __('messages.coupon_not_started_yet')]);
        }

        if ($coupon->end_date && now()->gt($coupon->end_date)) {
            throw ValidationException::withMessages(['code' => __('messages.coupon_expired')]);
        }

        $usage = $user->coupons()->where('coupon_id', $coupon->id)->first();
        if ($usage && $usage->pivot->usage_count >= $coupon->usage_limit) {
            abort(400, __('messages.coupon_usage_limit_reached'));
        }

        if ($cartTotal < $coupon->min_cart_amount) {
            throw ValidationException::withMessages(['code' => __('messages.cart_total_below_coupon_minimum', ['amount' => $coupon->min_cart_amount])]);
        }

        return $coupon;
    }


    public function calculateDiscount(Coupon $coupon, float $cartTotal)
    {
        if ($coupon->type === 'percentage') {
            return ($cartTotal * $coupon->discount_value) / 100;
        }

        return min($coupon->discount_value, $cartTotal);
    }

    public function markAsUsed(Coupon $coupon, int $orderId): void
    {
        $coupon->usages()->create([
            'user_id' => Auth::id(),
            'order_id' => $orderId,
        ]);

        $coupon->increment('used_count');
    }
}
