<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $limit = request()->input('limit',15);
        $userId = $request->user()->id;
        $orders = $this->orderService->getUserOrders($userId,$limit);
        return OrderResource::collection($orders);
    }
    public function show($id, Request $request)
    {
        $order = $this->orderService->getUserOrderById($request->user()->id,$id);
        return new OrderResource($order);
    }

    public function store(OrderRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $order = $this->orderService->createOrder(
            $user->id,
            $data['shipping_address_id'] ?? null,
            $data['billing_address_id'] ?? null,
            $data['payment_method'],
            $data['coupon_code'] ?? null,
            $data['use_points'] ?? false,
            $data['note'] ?? null
        );

        return response()->json($order, 201);
    }

    public function cancel($id, Request $request)
    {
        $order = $this->orderService->cancelOrder($request->user()->id, $id);
        return new OrderResource($order);
    }
    public function deliveryLocation(Order $order)
    {

        $userId = Auth::user()->id;
        if($userId != $order->user_id){
            return response()->json(['found' => false, 'message' => __('messages.order_view_unauthorized')], 403);
        }

        $deliveryId = $order->delivery_id;

        if (! $deliveryId) {
            return response()->json(['found' => false], 404);
        }

        $val = Redis::get('order:'.$order->id);

        if (! $val) {
            return response()->json(['found' => false], 404);
        }

        return response()->json(['found' => true, 'data' => json_decode($val, true)]);
    }
    public function updateRoute(Request $request,Order $order){
	$validated = $request->validate([
          'polyline' => ['required','string']
        ]);
	$order->update([
		'route' => ['polyline' => $validated['polyline']]
        ]);
	return  response()->json([
		'message' => __('messages.route_updated_successfully'),
        ]);
    }
}
