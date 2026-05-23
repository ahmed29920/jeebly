<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\UnitRequest;
use App\Models\Unit;
use App\Services\UnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected $service;

    public function __construct(UnitService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of units.
     */
    public function index()
    {
        $units = $this->service->all(-1);
        return view('dashboard.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        return view('dashboard.units.create');
    }

    /**
     * Store a newly created unit.
     */
    public function store(UnitRequest $request)
    {
        $data = $request->validated();
        $this->service->store($data);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit created successfully!');
    }

    /**
     * Display the specified unit.
     */
    public function show(Unit $unit)
    {
        return view('dashboard.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the unit.
     */
    public function edit(Unit $unit)
    {
        return view('dashboard.units.edit', compact('unit'));
    }

    /**
     * Update the specified unit.
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $data = $request->validated();
        $this->service->update($unit, $data);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully!');
    }

    /**
     * Remove the specified unit.
     */
    public function destroy(Unit $unit)
    {
        $this->service->delete($unit);

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Unit deleted successfully!',
            ]);
        }

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully!');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string|in:delete,activate,deactivate',
        ]);

        $this->service->bulkAction($request->ids, $request->action);

        return redirect()->back()->with('success', 'Bulk action applied successfully.');
    }
}

