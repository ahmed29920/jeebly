<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\CreateZoneRequest;
use App\Http\Requests\Web\Dashboard\UpdateZoneRequest;
use App\Models\Delivery;
use App\Models\Zone;
use App\Services\ZoneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZoneController extends Controller
{
    public function __construct(
        protected ZoneService $service
    ) {}

    public function index(Request $request): View
    {
        $perPage = (int) $request->get('per_page', 15);
        $filters = [
            'search' => (string) $request->get('search', ''),
            'is_active' => $request->get('is_active', ''),
        ];

        $zones = $this->service->getPaginatedZones($perPage, $filters);

        return view('dashboard.zones.index', compact('zones', 'filters'));
    }

    public function create(): View
    {
        $deliveries = Delivery::query()
            ->with('user')
            ->latest('id')
            ->get();

        return view('dashboard.zones.create', compact('deliveries'));
    }

    public function store(CreateZoneRequest $request): RedirectResponse
    {
        $this->service->createZone($request->validated());

        return redirect()->route('admin.zones.index')
            ->with('success', __('Zone created successfully.'));
    }

    public function show(Zone $zone): View
    {
        $zone->load('deliveries.user');

        return view('dashboard.zones.show', compact('zone'));
    }

    public function edit(Zone $zone): View
    {
        $zone->load('deliveries');
        $deliveries = Delivery::query()
            ->with('user')
            ->latest('id')
            ->get();

        $assignedDeliveryIds = $zone->deliveries->pluck('id')->all();

        return view('dashboard.zones.edit', compact('zone', 'deliveries', 'assignedDeliveryIds'));
    }

    public function update(UpdateZoneRequest $request, Zone $zone): RedirectResponse
    {
        $this->service->updateZone($zone, $request->validated());

        return redirect()->route('admin.zones.index')
            ->with('success', __('Zone updated successfully.'));
    }

    public function destroy(Zone $zone): RedirectResponse
    {
        $this->service->deleteZone($zone);

        return redirect()->route('admin.zones.index')
            ->with('success', __('Zone deleted successfully.'));
    }
}
