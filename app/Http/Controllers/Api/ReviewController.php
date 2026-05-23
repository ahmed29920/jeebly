<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ReviewController extends Controller
{
    protected $service;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }
    public function store(ReviewRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['product_id'] = $product->id;
        $this->service->store($data);
        
        return response()->json(['message' => __('messages.review_submitted_pending_approval')], 201);
    }

}
