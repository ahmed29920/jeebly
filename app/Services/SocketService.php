<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocketService
{
    public static function emit(string $event, array $data, ?string $room = null): void
    {
        try {
            Http::post(config('services.socket.url') . '/emit', [
                'secret' => config('services.socket.secret'),
                'event'  => $event,
                'room'   => $room,
                'data'   => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('[Socket] ' . $e->getMessage());
        }
    }

    // ─── Tracking ──────────────────────────────────────────────
    public static function updateLocation(int $orderId, float $lat, float $lng, array $extra = []): void
    {
        self::emit('location.updated', array_merge([
            'order_id'  => $orderId,
            'latitude'  => $lat,
            'longitude' => $lng,
            'timestamp' => now()->toISOString(),
        ], $extra), "order-{$orderId}");
    }

    // ─── Chat ──────────────────────────────────────────────────
    public static function sendMessage(int $chatId, array $message): void
    {
        self::emit('chat:message', $message, "chat-{$chatId}");
    }

    // ─── Notifications ─────────────────────────────────────────
    public static function notify(int $userId, string $type, array $data): void
    {
        self::emit('notification', array_merge(['type' => $type], $data), "user-{$userId}");
    }

    public static function productCreated(array $data): void
    {
        self::emit('product.created', $data, 'catalog');
    }

    public static function productUpdated(array $data): void
    {
        self::emit('product.updated', $data, 'catalog');
    }

    public static function orderCreated(array $data, ?int $branchId = null): void
    {
        self::emit('order.created', $data, 'admin-orders');

        if ($branchId) {
            self::emit('order.created', $data, "branch-{$branchId}-orders");
        }
    }

    public static function orderUpdated(
        array $data,
        ?int $branchId = null,
        ?int $userId = null,
        ?int $deliveryId = null,
        ?int $previousBranchId = null,
        ?int $previousDeliveryId = null,
    ): void {
        self::emit('order.updated', $data, 'admin-orders');

        if ($userId) {
            self::emit('order.updated', $data, 'user.'.$userId.'.orders');
        }

        if ($branchId) {
            self::emit('order.updated', $data, "branch-{$branchId}-orders");
        }

        if ($previousBranchId && $previousBranchId !== $branchId) {
            self::emit('order.updated', $data, "branch-{$previousBranchId}-orders");
        }

        if ($deliveryId) {
            self::emit('order.updated', $data, 'delivery.'.$deliveryId);
        }

        if ($previousDeliveryId && $previousDeliveryId !== $deliveryId) {
            self::emit('order.updated', $data, 'delivery.'.$previousDeliveryId);
        }
    }

    public static function productDeleted(array $data): void
    {
        self::emit('product.deleted', $data, 'catalog');
    }

    public static function deliveryOrderAssigned(array $data, int $deliveryId): void
    {
        self::emit('delivery.order.assigned', $data, 'delivery.' . $deliveryId);
    }
}
