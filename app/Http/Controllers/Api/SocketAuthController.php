<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class SocketAuthController extends Controller
{
    public function authorize(Request $request)
    {
        $room = $request->input('room');

        if (! $room) {
            return response()->json(['allowed' => false, 'message' => 'Room is required'], 422);
        }

        // Public room — guests and logged-in users (no token required)
        if ($room === 'catalog') {
            $user = $this->resolveSanctumUser($request);

            return response()->json([
                'allowed' => true,
                'guest' => $user === null,
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                ] : null,
            ]);
        }

        $user = $this->resolveSanctumUser($request);

        if (! $user) {
            return response()->json(['allowed' => false], 401);
        }

        [$allowed] = match (true) {
            $room === 'admin-orders' => $this->authorizeAdminOrders($user),
            str_starts_with($room, 'order-') => $this->authorizeOrder($user, $room),
            str_starts_with($room, 'branch-') && str_ends_with($room, '-orders') => $this->authorizeBranchOrders($user, $room),
            str_starts_with($room, 'chat-') => $this->authorizeChat($user, $room),
            $room === 'user-'.$user->id => [true],
            default => [false],
        };

        return response()->json([
            'allowed' => $allowed,
            'user' => $allowed ? [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ] : null,
        ]);
    }

    private function resolveSanctumUser(Request $request): ?User
    {
        $token = $request->bearerToken();

        if (! $token) {
            return null;
        }

        return PersonalAccessToken::findToken($token)?->tokenable;
    }

    private function authorizeAdminOrders($user): array
    {
        $allowed = $user->role === 'admin'
            || ($user->role === 'employee' && ! $user->branch_id);

        return [$allowed];
    }

    private function authorizeBranchOrders($user, string $room): array
    {
        if (! preg_match('/^branch-(\d+)-orders$/', $room, $matches)) {
            return [false];
        }

        $branchId = (int) $matches[1];

        if ($user->role === 'admin') {
            return [true];
        }

        return [
            in_array($user->role, ['employee', 'admin'], true)
            && (int) $user->branch_id === $branchId,
        ];
    }

    private function authorizeOrder($user, $room): array
    {
        $orderId = str_replace('order-', '', $room);
        $order = Order::with('delivery.user')->find($orderId);

        if (! $order) {
            return [false];
        }

        if (in_array($user->role, ['admin', 'employee'], true)) {
            return [true];
        }

        if ($user->id == $order->user_id) {
            return [true];
        }

        if ($order->delivery && $order->delivery->user_id == $user->id) {
            return [true];
        }

        return [false];
    }

    private function authorizeChat($user, $room): array
    {
        $chatId = str_replace('chat-', '', $room);
        $chat = Chat::find($chatId);

        if (! $chat) {
            return [false];
        }

        $allowed = $chat->user_id == $user->id
            || $chat->recipient_id == $user->id
            || in_array($user->role, ['admin', 'employee'], true);

        return [$allowed];
    }
}
