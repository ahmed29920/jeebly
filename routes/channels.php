<?php

use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin.orders', function ($user) {
    return $user->role === 'admin'
        || ($user->role === 'employee' && ! $user->branch_id);
});

Broadcast::channel('branch.{branchId}.orders', function ($user, $branchId) {
    if ($user->role === 'admin') {
        return true;
    }

    return in_array($user->role, ['employee', 'admin'], true)
        && (int) $user->branch_id === (int) $branchId;
});

// Private channel for delivery-user communication during order delivery
Broadcast::channel('order-delivery.{orderId}', function ($user, $orderId) {
    if ($user->role === 'admin' || $user->role === 'employee') {
        return true;
    }

    $order = Order::with('delivery.user')->find($orderId);

    if (! $order) {
        return false;
    }

    if ($user->id == $order->user_id) {
        return true;
    }

    if ($order->delivery && $order->delivery->user_id == $user->id) {
        return true;
    }

    return false;
});

Broadcast::channel('delivery.{deliveryId}', function ($user, $deliveryId) {
    return $user->delivery?->id == (int) $deliveryId;
});

Broadcast::channel('user.{userId}.orders', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});