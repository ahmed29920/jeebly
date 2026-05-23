<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\DeliveryRequest;
use App\Services\BranchService;
use App\Models\Delivery;
use App\Models\Zone;
use App\Services\DeliveryService;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    protected $service;
    protected $branchService;
    public function __construct(DeliveryService $service, BranchService $branchService)
    {
        $this->service = $service;
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveries = $this->service->all();
        return view('dashboard.deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = $this->branchService->all();
        $zones = Zone::query()->orderBy('name')->get();
        return view('dashboard.deliveries.create', compact('branches', 'zones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeliveryRequest $request)
    {
        $this->service->store($request->validated());
        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        $delivery->load(['zones', 'zones.deliveries']);
        return view('dashboard.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery)
    {
        $branches = $this->branchService->all();
        $delivery->load('zones');
        $zones = Zone::query()->orderBy('name')->get();
        $assignedZoneIds = $delivery->zones->pluck('id')->all();
        return view('dashboard.deliveries.edit', compact('delivery', 'branches', 'zones', 'assignedZoneIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        $this->service->update($delivery, $request->validated());
        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        $this->service->delete($delivery);
        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery deleted successfully!');
    }
}
