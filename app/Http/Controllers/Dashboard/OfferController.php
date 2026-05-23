<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\OfferRequest;
use App\Models\Offer;
use App\Services\OfferService;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    protected OfferService $offerService;
    protected ProductService $productService;
    protected CategoryService $categoryService;

    public function __construct(
        OfferService $offerService,
        ProductService $productService,
        CategoryService $categoryService
    ) {
        $this->offerService = $offerService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display all offers
     */
    public function index(Request $request)
    {
        $offers = $this->offerService->getAllOffers($request);
        return view('dashboard.offers.index', compact('offers'));
    }

    /**
     * Show form to create new offer
     */
    public function create()
    {
        $products = $this->productService->all();
        $categories = $this->categoryService->all(-1);
        return view('dashboard.offers.create', compact('products', 'categories'));
    }

    /**
     * Store new offer
     */
    public function store(OfferRequest $request)
    {
        $data = $request->validated();

        // Transform condition array from form to JSON format
        if (isset($data['condition']) && is_array($data['condition'])) {
            $condition = array_filter($data['condition']); // Remove empty values
            $data['condition'] = !empty($condition) ? $condition : null;
        }

        $this->offerService->create($data);

        return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully.');
    }

    /**
     * Show form to edit existing offer
     */
    public function edit(Offer $offer)
    {
        $products = $this->productService->all();
        $categories = $this->categoryService->all(-1);
        return view('dashboard.offers.edit', compact('offer', 'products', 'categories'));
    }

    /**
     * Update offer
     */
    public function update(OfferRequest $request, Offer $offer)
    {
        $data = $request->validated();

        // Transform condition array from form to JSON format
        if (isset($data['condition']) && is_array($data['condition'])) {
            $condition = array_filter($data['condition']); // Remove empty values
            $data['condition'] = !empty($condition) ? $condition : null;
        }

        $this->offerService->update($offer, $data);

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    /**
     * Delete offer
     */
    public function destroy(Offer $offer)
    {
        $this->offerService->delete($offer);
        return back()->with('success', 'Offer deleted successfully.');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus(Offer $offer)
    {
        $this->offerService->toggleStatus($offer);
        return back()->with('success', 'Offer status updated.');
    }

}
