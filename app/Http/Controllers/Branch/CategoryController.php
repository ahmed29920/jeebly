<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $categories = $this->service->allForBranch($branchId);
        return view('branch.categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $branchId = Auth::user()->branch_id;
        $category = $this->service->findForBranchBySlug($slug, $branchId);
        return view('branch.categories.show', compact('category'));
    }
}
