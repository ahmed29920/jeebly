<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\OrderService;
use App\Services\DeliveryService;
use App\Services\BranchService;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class OrderController extends Controller
{
    protected $orderService;
    protected $deliveryService;
    protected $branchService;

    public function __construct(OrderService $orderService, DeliveryService $deliveryService, BranchService $branchService)
    {
        $this->orderService = $orderService;
        $this->deliveryService = $deliveryService;
        $this->branchService = $branchService;
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->all();
        return view('dashboard.orders.index', compact('orders'));
    }
    public function show($id, Request $request)
    {
        $order = $this->orderService->findByUUID($id);
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

        // Get delivery men: those without branch OR with the same branch as the order
        $deliveryMen = \App\Models\Delivery::with('user', 'branch')
            ->where(function($query) use ($order) {
                $query->whereNull('branch_id');
                if ($order->branch_id) {
                    $query->orWhere('branch_id', $order->branch_id);
                }
            })
            ->get();

        // Get all branches for assignment (only if order has no branch)
        $branches = null;
        if ($order->branch_id === null) {
            $branches = $this->branchService->all();
        }

        return view('dashboard.orders.show', compact('order', 'deliveryMen', 'branches', 'orderZone'));
    }
    public function createInvoice($orderId)
    {
        $order = $this->orderService->find($orderId);

        $result = $this->orderService->createInvoice($order);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function downloadInvoice($orderId)
    {
        $order = $this->orderService->find($orderId);

        $pdf = PDF::loadView('dashboard.orders.invoice', compact('order'));
        // Open the PDF in browser
        return $pdf->stream('invoice_' . $order->id . '.pdf');
    }
    public function update(Order $order, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled,out_for_delivery',
            'delivery_id' => 'nullable|exists:deliveries,id',
        ]);

        // If shipping and delivery_id is provided, assign delivery first
        if ($data['status'] === 'shipped' && isset($data['delivery_id']) && $data['delivery_id']) {
            try {
                $this->orderService->assignDelivery($order, $data['delivery_id']);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        // Remove delivery_id from data as it's already handled by assignDelivery
        unset($data['delivery_id']);

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

        $order = $this->orderService->assignDelivery($order, $data['delivery_id']);

        return redirect()->back()->with('success', 'Delivery assigned successfully');
    }

    /**
     * Assign order to a branch
     * Used when order has no branch (branch_id is null)
     */
    public function assignToBranch(Order $order, Request $request)
    {
        $data = $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $result = $this->orderService->assignToBranch($order, $data['branch_id']);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }
    public function deliveryLocation(Order $order) {

        $deliveryId = $order->delivery_id;

        //if (!$deliveryId) {
         //   return response()->json(['found' => false], 404);
        //}


        $val = Redis::get('order:' . $order->id);

        //if (!$val) {
          //  return response()->json(['found' => false], 404);
        //}

        return response()->json(['found' => true, 'data' => json_decode($val, true)]);
    }
}
