<?php

use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id == (int) $id;
});

// Private channel for delivery-user communication during order delivery
Broadcast::channel('order-delivery.{orderId}', function ($user, $orderId) {
	\Log::info($user);
    if($user->role ==  'admin' || $user->role == 'employee'){
        return true;
    }

    $order = Order::with('delivery.user')->find($orderId);

    if (!$order) {
        return false;
    }

    // Allow access if user is the order owner
    if ($user->id == $order->user_id) {
        return true;

    }

    // Allow access if user is the delivery man assigned to this order
    if ($order->delivery && $order->delivery->user_id == $user->id) {
        return true;
    }

    return false;
});

