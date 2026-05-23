<?php

namespace App\Services;

use App\Models\Review;
use App\Repositories\ReviewRepository;

class ReviewService
{   
    protected $reviewRepo;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepo = $reviewRepository;
    }

    public function all()
    {
        return $this->reviewRepo->all();
    }

    public function allWithRelations()
    {
        return $this->reviewRepo->allWithRelations();
    }

    public function find($id)
    {
        return $this->reviewRepo->find($id);
    }
  
    public function getUserReviews($userId)
    {
        return $this->reviewRepo->allByUser($userId);
    }
 
    public function getProductReviews($productId)
    {
        return $this->reviewRepo->allByProduct($productId);
    }
 
    public function store(array $data)
    {
        $data['user_id'] = auth()->id();
        return $this->reviewRepo->create($data);
    }

    public function update(Review $review, $data)
    {
        $review->update($data);
        return ['success' => true, 'message' => __('messages.review_updated_successfully')];
    }
    
    public function delete(Review $review): bool
    {
        return $this->reviewRepo->delete($review);
    }
}

