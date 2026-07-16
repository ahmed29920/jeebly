<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Events\DeliveryLocationUpdated;
use App\Events\DeliveryStarted;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryWalletHistoryResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\DeliveryWalletHistoryService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    protected $orderService;

    protected $walletHistoryService;

    public function __construct(OrderService $orderService, DeliveryWalletHistoryService $walletHistoryService)
    {
        $this->orderService = $orderService;
        $this->walletHistoryService = $walletHistoryService;
    }

    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:current,completed,waiting',
        ]);

        $user = Auth::user();
        
        if (! $user) {
            return response()->json([
                'message' => __('messages.unauthenticated'),
            ], 401);
        }
        
        if (! $user->delivery) {
            return response()->json([
                'message' => __('messages.forbidden'),
            ], 403);
        }
        
        $deliveryId = $user->delivery->id;
        $status = $request->input('status');
        $orders = $this->orderService->allForDelivery($deliveryId, $status);

        return response()->json([
            'success' => true,
            'message' => __('messages.orders_fetched_successfully'),
            'data' => OrderResource::collection($orders),
        ]);
    }

    public function show($id, Request $request)
    {
        $deliveryId = Auth::user()->delivery->id;
        $order = $this->orderService->findForDelivery($deliveryId, $id);

        return response()->json([
            'success' => true,
            'message' => __('messages.order_fetched_successfully'),
            'data' => OrderResource::make($order),
        ]);
    }

    public function startDelivery(Order $order, Request $request)
    {
        
        $data = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);
        // Verify that the order is assigned to this delivery
        $deliveryId = Auth::user()->delivery->id;
        if ($order->delivery_id != $deliveryId) {
            return response()->json([
                'success' => false,
                'message' => __('messages.order_not_assigned_to_you'),
            ], 403);
        }

        // Only allow if order is in 'shipped' status
        if ($order->status != 'shipped') {
            return response()->json([
                'success' => false,
                'message' => __('messages.order_must_be_shipped_to_start_delivery'),
            ], 400);
        }

        $data['status'] = 'out_for_delivery';
        $result = $this->orderService->update($order, $data);

        // Refresh order to get updated relationships
        $order->refresh();
        $order->load('delivery.user', 'user');

        // Broadcast event to create WebSocket channel between delivery and user
        event(new DeliveryStarted($order));

        Redis::set('order:'.$order->id, json_encode([
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'updated_at' => now()->toDateTimeString(),
        ]));

        DeliveryLocationUpdated::dispatch($order->delivery_id, $order->id, $data['lat'], $data['lng']);

        return response()->json(['success' => $result['success'], 'message' => $result['message']]);
    }

    public function complete(Order $order)
    {
        // Verify that the order is assigned to this delivery
        $delivery = Auth::user()->delivery;
        if ($order->delivery_id != $delivery->id) {
            return response()->json([
                'success' => false,
                'message' => __('messages.order_not_assigned_to_you'),
            ], 403);
        }

        // Only allow if order is in 'out_for_delivery' status
        if ($order->status != 'out_for_delivery') {
            return response()->json([
                'success' => false,
                'message' => __('messages.order_must_be_out_for_delivery_to_complete'),
            ], 400);
        }

        // Update order status to completed (wallet credit happens in OrderService)
        $data = [
            'status' => 'completed',
        ];
        $result = $this->orderService->update($order, $data);

        $paymentAmount = $this->orderService->calculateDeliveryCommission($order->fresh());

        Redis::del('order:'.$order->id);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'you_will_receive' => $paymentAmount,
        ]);
    }

    public function walletHistory(Request $request)
    {
        $delivery = Auth::user()->delivery;
        $limit = $request->input('limit', 15);

        $history = $this->walletHistoryService->getHistoryForDelivery($delivery->id, $limit);

        return response()->json([
            'success' => true,
            'message' => __('messages.wallet_history_fetched_successfully'),
            'data' => DeliveryWalletHistoryResource::collection($history->items()),
            'pagination' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
            ],
        ]);
    }
}
