<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\DeliveryRequest;
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
     * Display a listing of the resource for the current branch.
     */
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $deliveries = $this->service->all($branchId);

        return view('branch.deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new delivery in the current branch.
     */
    public function create()
    {
        return view('branch.deliveries.create');
    }

    /**
     * Store a newly created delivery scoped to the current branch.
     */
    public function store(DeliveryRequest $request)
    {
        $data = $request->validated();
        $data['branch_id'] = Auth::user()->branch_id;

        $this->service->store($data);

        return redirect()->route('branch.deliveries.index')->with('success', __('Delivery created successfully!'));
    }

    /**
     * Display delivery (current branch only).
     */
    public function show(Delivery $delivery)
    {
        if ($delivery->branch_id !== Auth::user()->branch_id) {
            abort(404);
        }

        return view('branch.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified delivery (current branch only).
     */
    public function edit(Delivery $delivery)
    {
        if ($delivery->branch_id !== Auth::user()->branch_id) {
            abort(404);
        }

        return view('branch.deliveries.edit', compact('delivery'));
    }

    /**
     * Update delivery scoped to current branch.
     */
    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        if ($delivery->branch_id !== Auth::user()->branch_id) {
            abort(404);
        }

        $data = $request->validated();
        $data['branch_id'] = Auth::user()->branch_id;

        $this->service->update($delivery, $data);

        return redirect()->route('branch.deliveries.index')->with('success', __('Delivery updated successfully!'));
    }

    /**
     * Remove the specified resource from storage (current branch only).
     */
    public function destroy(Delivery $delivery)
    {
        if ($delivery->branch_id !== Auth::user()->branch_id) {
            abort(404);
        }

        if (request()->ajax()) {
            $this->service->delete($delivery);
            return response()->json(['success' => true, 'message' => __('Delivery deleted successfully.')]);
        }

        $this->service->delete($delivery);
        return redirect()->route('branch.deliveries.index')->with('success', __('Delivery deleted successfully!'));
    }
}

