<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\ProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\AttributeService;
use App\Services\BranchService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\UnitService;
use App\Services\VariantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    protected $productService, $categoryService, $attributeService, $variantService, $unitService, $branchService;

    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
        AttributeService $attributeService,
        VariantService $variantService,
        UnitService $unitService,
        BranchService $branchService,
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->attributeService = $attributeService;
        $this->variantService = $variantService;
        $this->unitService = $unitService;
        $this->branchService = $branchService;
    }

    public function index()
    {
        $products = $this->productService->all(auth()->user()->branch_id ?? null);
        $categories = $this->categoryService->all(-1);
        return view('dashboard.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = $this->categoryService->getCategoryTree();
        $attributes = $this->attributeService->all();
        $variants = $this->variantService->all()->load('options');
        $units = $this->unitService->all(-1);
        return view('dashboard.products.create', compact('categories', 'attributes', 'variants', 'units'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        $this->productService->store($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit($slug)
    {
        $product = $this->productService->findBySlug($slug);
        $product->load(['variants.branchVariantStocks', 'branchProductStocks']);
        $categories = $this->categoryService->getCategoryTree();
        $attributes = $this->attributeService->all();
        $variants = $this->variantService->all()->load('options');
        $units = $this->unitService->all(-1);
        return view('dashboard.products.edit', compact('product', 'categories', 'attributes', 'variants', 'units'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        $this->productService->update($product, $data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function show($slug)
    {
        $product = $this->productService->findBySlug($slug);
        $product->load(['variants.branchVariantStocks', 'branchProductStocks', 'unit']);
        $branches = $this->branchService->all();
        return view('dashboard.products.show', compact('product', 'branches'));
    }

    public function destroy(Product $product)
    {
        if (request()->ajax()) {
            $this->productService->delete($product);
            return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
        }

        $this->productService->delete($product);
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string|in:delete,activate,deactivate',
        ]);

        $this->productService->bulkAction($request->ids, $request->action);

        return redirect()->back()->with('success', 'Bulk action applied successfully.');
    }

    public function search(Request $request)
    {
        $q = $request->get('q');

        if (!$q) {
            return response()->json([]);
        }
        return $this->productService->search($q);
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function import(Request $request)
    {
        $data = $request->validate([
            'products' => 'required|file|mimes:csv,xlsx,xls',
            'category_id' => 'nullable|exists:categories,id',
        ]);


        $this->productService->import($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Products import started! It will complete in the background.');
    }
    public function importPage()
    {
        $categories = $this->categoryService->all(-1);
        return view('dashboard.products.import', compact('categories'));
    }

    public function updateBookable(Product $product, Request $request)
    {
        $bookable = $request->is_bookable == 'true' ? true : false;
        $product->update(['is_bookable' => $bookable]);
        return response()->json(['success' => true, 'message' => 'Bookable status updated successfully.']);
    }

    public function removeImage(ProductImage $productImage)
    {
        if (Storage::disk('public')->exists($productImage->path)) {
            Storage::disk('public')->delete($productImage->path);
        }
        $productImage->delete();
        return response()->json(['success' => true, 'message' => 'Image removed successfully.']);
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

        return response()->json([
            'success' => true,
            'message' => 'Branch stocks updated successfully.',
            'stock' => $product->fresh()->manager()->stock()
        ]);
    }
}
