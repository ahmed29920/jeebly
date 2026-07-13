<?php

namespace App\Services;

use App\Mail\OrderCreatedMail;
use App\Models\Address;
use App\Models\BranchProductStock;
use App\Models\BranchVariantStock;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class OrderService
{
    protected $cartService;

    protected $couponService;

    protected $orderRepo;

    protected $offerService;

    protected $productService;

    protected $branchService;

    protected $deliveryService;

    public function __construct(CartService $cartService, CouponService $couponService, OrderRepository $orderRepo, OfferService $offerService, ProductService $productService, BranchService $branchService, DeliveryService $deliveryService)
    {
        $this->cartService = $cartService;
        $this->couponService = $couponService;
        $this->orderRepo = $orderRepo;
        $this->offerService = $offerService;
        $this->productService = $productService;
        $this->branchService = $branchService;
        $this->deliveryService = $deliveryService;
    }

    public function all()
    {
        return $this->orderRepo->all();
    }

    public function allForBranch($branchId)
    {
        $user = Auth::user();
        $q = request('q', null);
        return $this->orderRepo->allForBranch($user->branch_id,$q);
    }

    public function find($id)
    {
        return $this->orderRepo->find($id);
    }

    public function findForBranch($id)
    {
        $user = Auth::user();

        return $this->orderRepo->findForBranch($id, $user->branch_id);
    }

    public function findByUUID($uuid)
    {
        return $this->orderRepo->findByUUID($uuid);
    }

    public function findByUUIDForBranch($uuid)
    {
        $user = Auth::user();

        return $this->orderRepo->findByUUIDForBranch($uuid, $user->branch_id);
    }
    // allForBranch
    // findByUUIDForBranch
    // findForBranch

    public function getUserOrders($userId, $limit)
    {
        return $this->orderRepo->findByUserId($userId, $limit);
    }

    public function getUserOrderById($userId, $id)
    {
        return $this->orderRepo->findByIdForUser($userId, $id);
    }

    public function createOrder($userId, $shippingAddressId, $billingAddressId, $paymentMethod, $couponCode = null, $usePoints = false, $note = null)
    {
        return DB::transaction(function () use ($userId, $shippingAddressId, $billingAddressId, $paymentMethod, $couponCode, $usePoints,$note) {
            $cartItems = $this->cartService->getCart($userId);

            if ($cartItems->isEmpty()) {
                throw new \Exception(__('messages.cart_is_empty'));
            }

            $user = Auth::user();

            // get the shipping address
            $shippingAddress = Address::find($shippingAddressId);
            if (! $shippingAddress) {
                throw new \Exception(__('messages.shipping_address_not_found'));
            }

            // get the closest branch from the shipping address
            $closestBranch = $this->branchService->findClosestBranch($shippingAddress->latitude, $shippingAddress->longitude);
            if (! $closestBranch) {
                throw new \Exception(__('messages.no_branch_found'));
            }

            // Subtotal
            $subtotal = $cartItems->sum(function ($item) {
                if ($item->variant) {
                    $price = $item->variant->price;
                } else {
                    $price = $item->product->price;
                }

                return $price * $item->quantity;
            });

            $coupon = null;
            $couponDiscount = 0;
            $pointsDiscount = 0;
            $offerDiscount = 0;
            $appliedOfferId = null;

            $subtotalAfterDiscount = $subtotal;

            /** --------------------
             * Apply Coupon
             * -------------------- */
            if ($couponCode) {
                $coupon = $this->couponService->validateCoupon($couponCode, $subtotalAfterDiscount);
                $couponData = $this->couponService->applyCoupon($coupon, $subtotalAfterDiscount, $cartItems);
                $couponDiscount = $couponData['discount'];
                $subtotalAfterDiscount -= $couponDiscount;

                // Update coupon usage
                $usage = $user->coupons()->where('coupon_id', $coupon->id)->first();
                $user->coupons()->syncWithoutDetaching([
                    $coupon->id => [
                        'usage_count' => $usage ? $usage->pivot->usage_count + 1 : 1,
                    ],
                ]);
            }

            /** --------------------
             * Apply Points
             * -------------------- */
            if ($usePoints && ! $couponCode) {
                $pointToMoneyRate = setting('point_to_money_rate'); // ? points = 1 EGP
                $maxDiscount = setting('max_points_discount_per_order');

                $availableMoney = $user->points / $pointToMoneyRate;
                $pointsDiscount = min($availableMoney, $maxDiscount);

                $subtotalAfterDiscount -= $pointsDiscount;

                // deduct equivalent points
                $pointsToDeduct = $pointsDiscount * $pointToMoneyRate;
                $user->points -= $pointsToDeduct;
                $user->save();
            }
            /** --------------------
             * Apply Best Offer
             * -------------------- */
            $offerData = $this->offerService->applyBestOffer($cartItems, $subtotalAfterDiscount);
            if ($offerData['offer']) {
                $offerDiscount = $offerData['discount'] ?? 0;
                $appliedOfferId = $offerData['offer']->id;
                $subtotalAfterDiscount -= $offerDiscount;
            }
            $shippingCostPerKm = setting('shipping_cost');
            $minShippingCost = setting('min_shipping_cost');
            $branch_lat = $closestBranch->latitude;
            $branch_lng = $closestBranch->longitude;
            $user_lat = $shippingAddress->latitude;
            $user_lng = $shippingAddress->longitude;
            $distance = $this->calculateDistance($branch_lat, $branch_lng, $user_lat, $user_lng);
            $shippingCost = $distance * $shippingCostPerKm;

            if ($shippingCost < $minShippingCost) {
                $shippingCost = $minShippingCost;
            }

            $serviceFee = max(0, (float) setting('service_fee', 0));

            $finalTotal = $subtotalAfterDiscount + $shippingCost + $serviceFee;

            // Create order
            $orderData = [
                'user_id' => $userId,
                'coupon_id' => $coupon?->id,
                'status' => 'pending',
                'total' => $subtotal,
                'points_discount_value' => $pointsDiscount,
                'coupon_discount_value' => $couponDiscount,
                'offer_discount_value' => $offerDiscount,
                'offer_id' => $appliedOfferId,
                'final_total' => $finalTotal,
                'note' => $note,
                'shipping_cost' => $shippingCost,
                'service_fee' => $serviceFee,
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $billingAddressId,
                'branch_id' => $closestBranch->id,
            ];

            $order = $this->orderRepo->create($orderData);

            // Create order items
            foreach ($cartItems as $item) {
                $product = $item->product;
                if ($item->quantity > $product->max_order_quantity) {
                    throw new \Exception(__('messages.max_order_quantity_exceeded', ['max' => $product->max_order_quantity]));
                }

                if ($item->variant) {
                    $price = $item->variant->price;
                } else {
                    $price = $item->product->price;
                }

                $freeQuantity = $offerData['free_quantity'] ?? 0;
                $freeQuantityProducts = $offerData['free_quantity_products'] ?? [];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'free_quantity' => in_array($item->variant_id, $freeQuantityProducts) ? $freeQuantity : 0,
                ]);
            }

            // Clear cart
            $this->cartService->clearCart($userId);

            // Events & Notifications
            RealtimeService::orderCreated($order->fresh(['user', 'branch', 'items']));

            // Add Points to User and Inviter
            if (setting('allow_order_points') && ! $usePoints) {
                // get order_points_rate and inviter_order_points_rate from cache or settings
                $order_points_rate = setting('order_points_rate') / 100;
                $inviter_order_points_rate = setting('inviter_order_points_rate') / 100;

                // calculate points
                $points = $order->total * $order_points_rate;
                $inviter_points = $order->total * $inviter_order_points_rate;

                // add points to user
                $order->user->points += $points;
                $order->user->save();
                if (setting('allow_inviter_order_points')) {
                    // add points to inviter
                    if ($order->user->invited_by) {
                        $inviter = User::find($order->user->invited_by);
                        $inviter->points += $inviter_points;
                        $inviter->save();
                    }
                }
            }

            // Mail::to($order->user->email)->send(new OrderCreatedMail($order));

            $admins = User::where('role', 'admin')->get();
            // Notification::send($admins, new NewOrderNotification($order));

            return $order->load(['items', 'coupon', 'offer', 'shippingAddress', 'billingAddress']);
        });
    }

    public function createInvoice(Order $order)
    {
        if ($order->status !== 'pending') {
            return [
                'success' => false,
                'message' => __('messages.invoice_already_created_or_invalid_status'),
            ];
        }

        DB::beginTransaction();

        try {
            $branchId = $order->branch_id;

            foreach ($order->items as $item) {
                $product = $item->product;
                $quantity = $item->quantity;

                if ($item->variant) {
                    // Check and decrease variant branch stock
                    $branchStock = BranchVariantStock::where('product_variant_id', $item->variant_id)
                        ->where('branch_id', $branchId)
                        ->first();

                    $quantityBefore = $branchStock ? $branchStock->quantity : 0;

                    if ($quantityBefore < $quantity) {
                        throw new \Exception(__('messages.insufficient_variant_stock_in_branch', ['name' => $item->variant->getTranslation('name', app()->getLocale())]));
                    }

                    // Decrease stock
                    $quantityAfter = $quantityBefore - $quantity;
                    $branchStock->update(['quantity' => $quantityAfter]);

                    // Log stock history
                    $this->productService->logOrderStockDecrease(
                        $branchId,
                        $product->id,
                        $item->variant_id,
                        $order->id,
                        $quantity,
                        $quantityBefore,
                        $quantityAfter
                    );
                } else {
                    // Check and decrease product branch stock
                    $branchStock = BranchProductStock::where('product_id', $product->id)
                        ->where('branch_id', $branchId)
                        ->first();

                    $quantityBefore = $branchStock ? $branchStock->quantity : 0;

                    if ($quantityBefore < $quantity) {
                        throw new \Exception(__('messages.insufficient_product_stock_in_branch', ['name' => $product->getTranslation('name', app()->getLocale())]));
                    }

                    // Decrease stock
                    $quantityAfter = $quantityBefore - $quantity;
                    $branchStock->update(['quantity' => $quantityAfter]);

                    // Log stock history
                    $this->productService->logOrderStockDecrease(
                        $branchId,
                        $product->id,
                        null,
                        $order->id,
                        $quantity,
                        $quantityBefore,
                        $quantityAfter
                    );
                }
            }

            $order->status = 'processing';
            $order->save();
            
            // Send Firebase notification to user
            if ($order->user->fcm_token) {
                SendFirebaseNotification::dispatch(
                    $order->user->fcm_token,
                    'Your order status has been updated',
                    'Your order with id #'.$order->id.' has been updated to processing',
                    ['order_id' => $order->id, 'order_status' => 'processing']
                );
            }

            DB::commit();

            RealtimeService::orderUpdated($order->fresh(['user', 'branch', 'items']));

            return ['success' => true, 'message' => __('messages.invoice_created_successfully')];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => __('messages.something_went_wrong', ['error' => $e->getMessage()]),
            ];
        }
    }

    public function cancelOrder($userId, $orderId)
    {
        $order = $this->orderRepo->findByIdForUser($userId, $orderId);

        if (! $order) {
            throw new \Exception(__('messages.order_not_found'));
        }

        if ($order->status !== 'pending') {
            throw new \Exception(__('messages.only_pending_orders_can_be_cancelled'));
        }

        // If order was already processed, restore stock
        if ($order->status === 'processing') {
            $this->restoreOrderStock($order);
        }

        $order->status = 'cancelled';
        $order->save();

        RealtimeService::orderUpdated($order->fresh(['user', 'branch', 'items']));

        return $order;
    }

    protected function restoreOrderStock(Order $order)
    {
        $branchId = $order->branch_id;

        foreach ($order->items as $item) {
            $quantity = $item->quantity;

            if ($item->variant) {
                // Restore variant branch stock
                $branchStock = BranchVariantStock::where('product_variant_id', $item->variant_id)
                    ->where('branch_id', $branchId)
                    ->first();

                if ($branchStock) {
                    $quantityBefore = $branchStock->quantity;
                    $quantityAfter = $quantityBefore + $quantity;
                    $branchStock->update(['quantity' => $quantityAfter]);

                    // Log stock history
                    $this->productService->logBranchStockHistory([
                        'branch_id'          => $branchId,
                        'product_id'         => $item->product_id,
                        'product_variant_id' => $item->variant_id,
                        'order_id'           => $order->id,
                        'type'               => 'order_cancel',
                        'quantity_before'    => $quantityBefore,
                        'quantity_after'     => $quantityAfter,
                        'quantity_change'    => $quantity,
                        'notes'              => 'Stock restored due to order cancellation',
                    ]);
                }
            } else {
                // Restore product branch stock
                $branchStock = BranchProductStock::where('product_id', $item->product_id)
                    ->where('branch_id', $branchId)
                    ->first();

                if ($branchStock) {
                    $quantityBefore = $branchStock->quantity;
                    $quantityAfter = $quantityBefore + $quantity;
                    $branchStock->update(['quantity' => $quantityAfter]);

                    // Log stock history
                    $this->productService->logBranchStockHistory([
                        'branch_id' => $branchId,
                        'product_id' => $item->product_id,
                        'product_variant_id' => null,
                        'order_id' => $order->id,
                        'type' => 'order_cancel',
                        'quantity_before' => $quantityBefore,
                        'quantity_after' => $quantityAfter,
                        'quantity_change' => $quantity,
                        'notes' => 'Stock restored due to order cancellation',
                    ]);
                }
            }
        }
    }

    public function update(Order $order, array $data)
    {
        $user = Auth::user();
        if (
            ($user->role == 'branch' && $order->branch_id != $user->branch_id) ||
            ($user->role == 'delivery' && $order->delivery_id != optional($user->delivery)->id)
        ) {
            throw new \Exception(__('messages.order_update_unauthorized'));
        }


        $order->update($data);

        // Mail::to($order->user->email)
        //     ->send(new \App\Mail\OrderStatusUpdatedMail($order));
            
            
        // Send Firebase notification to user
        if ($order->user->fcm_token && isset($data['status'])) {
            SendFirebaseNotification::dispatch(
                $order->user->fcm_token,
                'Your order status has been updated',
                'Your order with id #'.$order->id.' has been updated to '.$data['status'],
                ['order_id' => $order->id, 'order_status' => $data['status']]
            );
        }

        RealtimeService::orderUpdated($order->fresh(['user', 'branch', 'items']));

        return [
            'success' => true,
            'message' => __('messages.order_updated_successfully'),
        ];
    }

    public function storeComment(Order $order, $data)
    {
        try {
            $comment = $order->comments()->create([
                'user_id' => Auth::user()->id,
                'comment' => $data['comment'],
                'notify_customer' => $data['notify'] ?? false,
            ]);

            // Optionally notify customer via email if notify_customer is true
            if (! empty($data['notify']) && $data['notify']) {
                // Mail::to($order->user->email)->send(new \App\Mail\OrderCommentMail($order, $comment));
            }

            return ['success' => true, 'message' => __('messages.comment_added_successfully')];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => __('messages.failed_to_add_comment', ['error' => $e->getMessage()])];
        }
    }

    public function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // KM

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function assignDelivery(Order $order, $deliveryId)
    {
        $delivery = $this->deliveryService->find($deliveryId);
        if (! $delivery) {
            throw new \Exception(__('messages.delivery_not_found'));
        }
        if ($delivery->branch_id && $delivery->branch_id != $order->branch_id) {
            throw new \Exception(__('messages.delivery_branch_mismatch'));
        }
        if ($order->delivery_id) {
            throw new \Exception(__('messages.delivery_already_assigned'));
        }
        if (! $delivery->is_online) {
            throw new \Exception(__('messages.delivery_offline_order_paid'));
        }
        $order->delivery_id = $delivery->id;
        $order->delivery_assigned_at = now();
        $order->save();
        // realtime show order in delivery app (socket)
        $order = $order->fresh(['delivery', 'user', 'branch', 'items']);
        RealtimeService::assignDelivery($order);
        RealtimeService::orderUpdated($order);

        return $order->load(['delivery']);
    }

    public function transferToAdmin(Order $order)
    {
        try {
            // Only allow transfer if order is in pending or processing status
            if (! in_array($order->status, ['pending'])) {
                throw new \Exception(__('messages.order_transfer_pending_only'));
            }

            $previousBranchId = $order->branch_id;
            $previousDeliveryId = $order->delivery_id;

            // Remove delivery assignment if exists
            if ($order->delivery_id) {
                $order->delivery_id = null;
                $order->delivery_assigned_at = null;
            }

            // Set branch_id to null to transfer to admin
            $order->branch_id = null;
            $order->save();

            RealtimeService::orderUpdated(
                $order->fresh(['user', 'branch', 'items']),
                $previousBranchId,
                $previousDeliveryId,
            );

            return [
                'success' => true,
                'message' => __('messages.order_transferred_to_admin_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('messages.failed_to_transfer_order', ['error' => $e->getMessage()]),
            ];
        }
    }

    public function assignToBranch(Order $order, $branchId)
    {
        try {
            // Only allow assignment if order has no branch (branch_id is null)
            if ($order->branch_id !== null) {
                throw new \Exception(__('messages.order_already_assigned_to_branch'));
            }

            // Validate branch exists
            $branch = $this->branchService->findById($branchId);
            if (! $branch) {
                throw new \Exception(__('messages.branch_not_found'));
            }

            // Assign order to branch
            $order->branch_id = $branchId;
            $order->save();

            RealtimeService::orderUpdated($order->fresh(['user', 'branch', 'items']));

            return [
                'success' => true,
                'message' => __('messages.order_assigned_to_branch_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('messages.failed_to_assign_order_to_branch', ['error' => $e->getMessage()]),
            ];
        }
    }

    public function allForDelivery($deliveryId)
    {
        return $this->orderRepo->allForDelivery($deliveryId);
    }

    public function findForDelivery($deliveryId, $id)
    {
        return $this->orderRepo->findByIdForDelivery($deliveryId, $id);
    }
}
