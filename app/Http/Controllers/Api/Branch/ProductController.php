<?php

namespace App\Http\Controllers\Api\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Models\Product;
use App\Http\Resources\ProductResource;
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
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $branchId = Auth::user()->branch_id;
        $product = $this->productService->findForBranchById($id, $branchId);

        return ProductResource::make($product);
    }

    public function updateBranchStocks(Request $request, Product $product)
    {
        if ($product->type === 'variable') {
            $data = $request->validate([
                'product_variants' => 'required|array|min:1',
                'product_variants.*.id' => 'required|exists:product_variants,id',
                'product_variants.*.branch_stocks' => 'nullable|array',
                'product_variants.*.branch_stocks.*' => 'nullable|integer|min:0',
            ]);

        } else {
            $request->validate([
                'stock' => 'required|integer|min:0',
            ]);
            $branchId = Auth::user()->branch_id;
            $data['branch_stocks'] = [$branchId => $request->stock];
        }

        $this->productService->updateBranchStocks($product, $data);

        return response()->json(['success' => true, 'message' => __('messages.branch_stock_updated_successfully')]);
    }
}
