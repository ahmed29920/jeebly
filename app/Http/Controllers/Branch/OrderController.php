<?php

namespace App\Http\Controllers\Branch;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Zone;
use App\Services\OrderService;
use App\Services\DeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

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
        return view('branch.orders.index', compact('orders'));
    }
    public function show($id, Request $request)
    {
        $branchId = Auth::user()->branch_id;
        $order = $this->orderService->findByUUIDForBranch($id, $branchId);
        abort_if(! $order, 404);

        $orderZone = null;
        $shipping = $order?->shippingAddress;
        if ($shipping && $shipping->latitude !== null && $shipping->longitude !== null) {
            $lat = (float) $shipping->latitude;
            $lng = (float) $shipping->longitude;
            $orderZone = Zone::query()
                ->active()
                ->get()
                ->first(fn (Zone $zone) => $zone->containsPoint(['lat' => $lat, 'lng' => $lng]));
        }

        return view('branch.orders.show', compact('order', 'orderZone'));
    }
    public function createInvoice($orderId)
    {
        $branchId = Auth::user()->branch_id;
        $order = $this->orderService->findForBranch($orderId, $branchId);

        $result = $this->orderService->createInvoice($order);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function downloadInvoice($orderId)
    {
        $branchId = Auth::user()->branch_id;
        $order = $this->orderService->findForBranch($orderId, $branchId);

        $pdf = PDF::loadView('branch.orders.invoice', compact('order'));
        // Open the PDF in browser
        return $pdf->stream('invoice_' . $order->id . '.pdf');
    }
    public function update(Order $order, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $result = $this->orderService->update($order, $data);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function storeComment(Request $request, Order $order)
    {
        $data = $request->validate([
            'comment' => 'required|string',
            'notify' => 'sometimes|boolean',
        ]);

        $result = $this->orderService->storeComment($order, $data);

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

    public function assignDelivery(Order $order, Request $request)
    {
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

        return redirect()->back()->with('success', 'Delivery assigned successfully');
    }

    /**
     * Transfer order to admin (set branch_id to null)
     * This means the branch cannot handle the order and admin needs to reassign it
     */
    public function transferToAdmin(Order $order)
    {
        // Verify that the order belongs to the current branch
        if ($order->branch_id !== Auth::user()->branch_id) {
            return redirect()->back()->with('error', __('Order not found or not accessible'));
        }

        $result = $this->orderService->transferToAdmin($order);

        return redirect()->route('branch.orders.index')->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
