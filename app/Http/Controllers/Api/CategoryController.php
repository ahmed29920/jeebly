<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\CategoryRequest;
use App\Services\CategoryService;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

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
        $limit = request()->input('limit',15);
        $categories = $this->service->all($limit);
        return CategoryResource::collection($categories);
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load('products.unit','children');
        return new CategoryResource($category);
    }

}
