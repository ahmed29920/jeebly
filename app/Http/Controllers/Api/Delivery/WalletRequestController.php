<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Delivery\WalletRequestRequest;
use App\Http\Resources\DeliveryWalletRequestResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\DeliveryWalletRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletRequestController extends Controller
{
    public function __construct(protected DeliveryWalletRequestService $service)
    {
    }

    public function index(Request $request)
    {
        $delivery = Auth::user()->delivery;
        $limit = (int) $request->input('limit', 15);

        $requests = $this->service->getForDelivery($delivery->id, $limit);

        return response()->json([
            'success'    => true,
            'message'    => __('messages.wallet_requests_fetched_successfully'),
            'data'       => DeliveryWalletRequestResource::collection($requests->items()),
            'pagination' => [
                'current_page' => $requests->currentPage(),
                'last_page'    => $requests->lastPage(),
                'per_page'     => $requests->perPage(),
                'total'        => $requests->total(),
            ],
        ]);
    }

    public function settleableOrders()
    {
        $delivery = Auth::user()->delivery;
        $orders = $this->service->getSettleableOrders($delivery);

        return response()->json([
            'success' => true,
            'message' => __('messages.settleable_orders_fetched_successfully'),
            'data'    => OrderResource::collection($orders),
        ]);
    }

    public function store(WalletRequestRequest $request)
    {
        $delivery = Auth::user()->delivery;
        $data = $request->validated();

        if ($data['type'] === 'withdrawal') {
            $walletRequest = $this->service->createWithdrawal(
                $delivery,
                (float) $data['amount'],
                $data['notes'] ?? null,
            );
        } else {
            $order = Order::findOrFail($data['order_id']);
            $walletRequest = $this->service->createSettlement(
                $delivery,
                $order,
                $data['notes'] ?? null,
            );
        }

        $walletRequest->load('order');

        return response()->json([
            'success' => true,
            'message' => __('messages.wallet_request_created_successfully'),
            'data'    => DeliveryWalletRequestResource::make($walletRequest),
        ], 201);
    }
}
