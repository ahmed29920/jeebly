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
}
