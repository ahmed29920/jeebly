<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\BranchRequest;
use App\Models\Branch;
use App\Services\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $service;

    public function __construct(BranchService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = $this->service->all();
        return view('dashboard.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchRequest $request)
    {
        $this->service->store($request->validated());
        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        // Load products with their branch stocks for this branch
        $products = \App\Models\Product::with([
            'branchProductStocks' => function($query) use ($branch) {
                $query->where('branch_id', $branch->id);
            },
            'variants.branchVariantStocks' => function($query) use ($branch) {
                $query->where('branch_id', $branch->id);
            },
            'unit',
            'images'
        ])->get();

        return view('dashboard.branches.show', compact('branch', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return view('dashboard.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchRequest $request, Branch $branch)
    {
        $this->service->update($branch, $request->validated());
        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $this->service->delete($branch);
        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully!');
    }
}
