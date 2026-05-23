<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $limit = request()->input('limit',15);
        $categories = $this->service->active($limit);
        return ProductResource::collection($categories);
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $product = $this->service->find($id);
        return new ProductResource($product);
    }

    public function toggleFavorite($productId)
    {
        $user = auth()->user();

        if ($user->favoriteProducts()->where('product_id', $productId)->exists()) {
            $user->favoriteProducts()->detach($productId);
            return response()->json(['message' => __('messages.removed_from_favorites')]);
        } else {
            $user->favoriteProducts()->attach($productId);
            return response()->json(['message' => __('messages.added_to_favorites')]);
        }
    }
    public function favoriteList()
    {
        $user = auth()->user();
        $favorites = $user->favoriteProducts()->with('images')->get();
        return ProductResource::collection($favorites);
    }

    public function search(Request $request)
    {
        // search for products by name or sku
        $query = $request->input('query');
        $products = $this->service->searchApi($query);
        return ProductResource::collection($products);
    }

}
