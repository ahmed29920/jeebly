<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Models\Product;

class ProductController extends Controller
{
    protected $productService, $categoryService;

    public function __construct(
        ProductService $productService,
        CategoryService $categoryService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $products = $this->productService->all($branchId);
        $categories = $this->categoryService->all(-1);
        return view('branch.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $branchId = Auth::user()->branch_id;
        $product = $this->productService->findForBranchBySlug($slug, $branchId);

        return view('branch.products.show', compact('product', 'branchId'));
    }

    public function updateBranchStocks(Request $request, Product $product)
    {
        if ($product->type === 'variable') {
            $request->validate([
                'product_variants' => 'required|array|min:1',
                'product_variants.*.id' => 'required|exists:product_variants,id',
                'product_variants.*.branch_stocks' => 'nullable|array',
                'product_variants.*.branch_stocks.*' => 'nullable|integer|min:0',
            ]);
        } else {
            $request->validate([
                'branch_stocks' => 'nullable|array',
                'branch_stocks.*' => 'nullable|integer|min:0',
            ]);
        }

        $this->productService->updateBranchStocks($product, $request->all());

        return back()->with('success', __('Branch stock updated successfully.'));
    }
}
