<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\VariantRequest;
use App\Models\Variant;
use App\Services\VariantService;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    protected $service;

    public function __construct(VariantService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $variants = $this->service->all();
        return view('dashboard.variants.index', compact('variants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.variants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VariantRequest $request)
    {
        $this->service->store($request->validated());
        return redirect()->route('admin.variants.index')->with('success', 'Variant created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Variant $variant)
    {
        $variant->load('options');
        return view('dashboard.variants.show', compact('variant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Variant $variant)
    {
        $variant->load('options');
        return view('dashboard.variants.edit', compact('variant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VariantRequest $request, Variant $variant)
    {
        $this->service->update($variant, $request->validated());
        return redirect()->route('admin.variants.index')->with('success', 'Variant updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variant $variant)
    {
        $this->service->delete($variant);
        return redirect()->route('admin.variants.index')->with('success', 'Variant deleted successfully!');
    }
}
