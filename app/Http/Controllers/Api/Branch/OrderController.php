<?php

namespace App\Http\Controllers\Api\Branch;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\DeliveryService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    protected $orderService;

    protected $deliveryService;

    public function __construct(OrderService $orderService, DeliveryService $deliveryService)
    {
        $this->orderService = $orderService;
        $this->deliveryService = $deliveryService;
    }

    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        $orders = $this->orderService->allForBranch($branchId);

        return OrderResource::collection($orders);
    }

    public function show($id, Request $request)
    {
        $branchId = Auth::user()->branch_id;
        $order = $this->orderService->findForBranch($id);

        return OrderResource::make($order);
    }

    public function createInvoice($orderId)
    {
        $branchId = Auth::user()->branch_id;
        $order = $this->orderService->findForBranch($orderId, $branchId);

        $result = $this->orderService->createInvoice($order);

        return response()->json(['success' => $result['success'], 'message' => $result['message']]);
    }

    public function downloadInvoice($orderId)
    {
        $branchId = Auth::user()->branch_id;
        $order = $this->orderService->findForBranch($orderId, $branchId);

        return response()->json(['success' => true, 'message' => __('messages.invoice_created_successfully')]);
    }

    public function update(Order $order, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $result = $this->orderService->update($order, $data);

        return response()->json(['success' => $result['success'], 'message' => $result['message']]);
    }

    public function storeComment(Request $request, Order $order)
    {
        $data = $request->validate([
            'comment' => 'required|string',
            'notify' => 'sometimes|boolean',
        ]);

        $result = $this->orderService->storeComment($order, $data);

        return response()->json(['success' => $result['success'], 'message' => $result['message']]);
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

    /**
     * Transfer order to admin (set branch_id to null)
     * This means the branch cannot handle the order and admin needs to reassign it
     */
    public function transferToAdmin(Order $order)
    {
        // Verify that the order belongs to the current branch
        if ($order->branch_id != Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => __('messages.order_not_found_or_not_accessible'),
            ], 404);
        }

        $result = $this->orderService->transferToAdmin($order);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function deliveryLocation(Order $order)
    {
        $deliveryId = $order->delivery_id;

        $branchId = Auth::user()->branch_id;

        if($branchId != $order->branch_id){
            return response()->json(['found' => false, 'message' => __('messages.order_view_unauthorized')], 403);
        }

        if (! $deliveryId) {
            return response()->json(['found' => false], 404);
        }

        $val = Redis::get('order:'.$order->id);

        if (! $val) {
            return response()->json(['found' => false], 404);
        }

        return response()->json(['found' => true, 'data' => json_decode($val, true)]);
    }

    public function assignDelivery(Order $order, Request $request) {
        $data = $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
        ]);

        $delivery = $this->deliveryService->find($data['delivery_id']);
        $branchId = Auth::user()->branch_id;
        if($branchId != $delivery->branch_id){
            return redirect()->back()->with('error', 'You are not authorized to assign this delivery to this order');
        }
        if($delivery->branch_id && $delivery->branch_id != $branchId){
            return redirect()->back()->with('error', 'Delivery is not assigned to the same branch as the order');
        }

        $order = $this->orderService->assignDelivery($order, $data['delivery_id']);

        return response()->json(['success' => true, 'message' => __('messages.delivery_assigned_successfully')]);
    }
}
