<?php

namespace App\Http\Controllers\Dashboard;

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
    public function index(Request $request)
    {
        $reviews = $this->service->allWithRelations();

        return view('dashboard.reviews.index', compact('reviews'));
    }
    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $result = $this->service->update($review, $data);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }
    public function destroy(Review $review)
    {
        if (request()->ajax()) {
            $this->service->delete($review);
            return response()->json(['success' => true, 'message' => 'Reviews deleted successfully.']);
        }

        $this->service->delete($review);
        return redirect()->route('admin.reviews.index')->with('success', 'Reviews deleted successfully!');

       
    }
}
