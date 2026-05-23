<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\CategoryRequest;
use App\Services\CategoryService;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = $this->service->all(-1);
        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $categories = $this->service->getCategoryTree();
        return view('dashboard.categories.create', compact('categories')); // Blade form
    }

    /**
     * Store a newly created category.
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all();
        $category = $this->service->store($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Show the form for editing the category.
     */
    public function edit($slug)
    {
        $category = $this->service->findBySlug($slug);
        $categories = $this->service->getCategoryTree();

        return view('dashboard.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category.
     */
    public function update(CategoryRequest $request, Category $category)
    {

        $data = $request->all();
        $this->service->update($category, $data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        $this->service->delete($category);

        if (request()->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Category deleted successfully!',
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
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

    public function export()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }
}
