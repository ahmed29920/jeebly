<?php

namespace App\Http\Controllers\Api\Branch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\DeliveryRequest;
use App\Http\Resources\DeliveryResource;
use App\Models\Delivery;
use App\Services\DeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    protected $service;

    public function __construct(DeliveryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of delivery men for the current branch.
     */
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $deliveries = $this->service->all($branchId);

        return DeliveryResource::collection($deliveries);
    }

    /**
     * Display the specified delivery man (current branch only).
     */
    public function show(Delivery $delivery)
    {
        if ($delivery->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => __('messages.delivery_not_found_or_not_accessible')
            ], 404);
        }

        $delivery->load('user', 'branch', 'orders.items.product.unit', 'orders.items.variant', 'orders.shippingAddress', 'orders.billingAddress');

        return DeliveryResource::make($delivery);
    }

    /**
     * Store a newly created delivery man scoped to the current branch.
     */
    public function store(DeliveryRequest $request)
    {
        // Merge branch_id into request before validation
        // $request->merge(['branch_id' => Auth::user()->branch_id]);
        $data = $request->validated();
        $data['branch_id'] = Auth::user()->branch_id;
        $delivery = $this->service->store($data);
        $delivery->load('user', 'branch');

        return response()->json([
            'success' => true,
            'message' => __('messages.delivery_created_successfully'),
            'data' => DeliveryResource::make($delivery)
        ], 201);
    }

    /**
     * Update the specified delivery man (current branch only).
     */
    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        if ($delivery->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => __('messages.delivery_not_found_or_not_accessible')
            ], 404);
        }



        $data = $request->validated();
        $data['branch_id'] = Auth::user()->branch_id;
        $delivery = $this->service->update($delivery, $data);
        $delivery->load('user', 'branch');

        return response()->json([
            'success' => true,
            'message' => __('messages.delivery_updated_successfully'),
            'data' => DeliveryResource::make($delivery)
        ]);
    }

    /**
     * Remove the specified delivery man (current branch only).
     */
    public function destroy(Delivery $delivery)
    {
        if ($delivery->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => __('messages.delivery_not_found_or_not_accessible')
            ], 404);
        }

        $this->service->delete($delivery);

        return response()->json([
            'success' => true,
            'message' => __('messages.delivery_deleted_successfully')
        ]);
    }
}

