<?php

namespace App\Repositories;

use App\Models\Review;

class ReviewRepository
{
    protected $model;

    public function __construct(Review $review)
    {
        $this->model = $review;
    }

    public function all()
    {
        return $this->model->withExistingProduct()->get();
    }

    public function allWithRelations()
    {
        return $this->model->withExistingProduct()
            ->with(['product', 'user'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function allByUser(int $userId)
    {
        return $this->model->withExistingProduct()->where('user_id', $userId)->get();
    }

    public function allByProduct(int $productId)
    {
        return $this->model->withExistingProduct()->where('product_id', $productId)->get();
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Review $review, array $data): Review
    {
        $review->update($data);
        return $review;
    }

    public function delete(Review $review): bool
    {
        return $review->delete();
    }
}
