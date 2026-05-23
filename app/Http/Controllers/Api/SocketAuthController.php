<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Chat;
use Illuminate\Http\Request;

class SocketAuthController extends Controller
{
    public function authorize(Request $request)
    {
        $user = $request->user();
        $room = $request->input('room');

        if (!$user || !$room) {
            return response()->json(['allowed' => false], 401);
        }

        [$allowed] = match(true) {
            // tracking:join  → "order-{id}"
            str_starts_with($room, 'order-') => $this->authorizeOrder($user, $room),

            // chat:join → "chat-{id}"
            str_starts_with($room, 'chat-')  => $this->authorizeChat($user, $room),

   
            $room === 'user-' . $user->id    => [true],

            default => [false],
        };

        return response()->json([
            'allowed' => $allowed,
            'user'    => $allowed ? [
                'id'   => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ] : null,
        ]);
    }

    // ─── Order Tracking ────────────────────────────────────────
    private function authorizeOrder($user, $room): array
    {
        $orderId = str_replace('order-', '', $room);
        $order   = Order::with('delivery.user')->find($orderId);

        if (!$order) return [false];

   
        if (in_array($user->role, ['admin', 'employee'])) return [true];

 
        if ($user->id == $order->user_id) return [true];

 
        if ($order->delivery && $order->delivery->user_id == $user->id) return [true];

        return [false];
    }

    // ─── Chat ──────────────────────────────────────────────────
    private function authorizeChat($user, $room): array
    {
        $chatId = str_replace('chat-', '', $room);
        $chat   = Chat::find($chatId);

        if (!$chat) return [false];

         $allowed = $chat->user_id       == $user->id
                || $chat->recipient_id  == $user->id
                || in_array($user->role, ['admin', 'employee']);

        return [$allowed];
    }
}
