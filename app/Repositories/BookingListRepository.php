<?php

namespace App\Repositories;

use App\Models\BookingList;

class BookingListRepository
{
    protected $model;
    public function __construct(BookingList $bookingList)
    {
        $this->model = $bookingList;
    }

    public function all()
    {
        return $this->model->withExistingProduct()->with(['product.images', 'user'])->latest()->get();
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(BookingList $bookingList, array $data)
    {
        $bookingList->update($data);
        return $bookingList;
    }

    public function delete(BookingList $bookingList)
    {
        return $bookingList->delete();
    }

    public function findById(int $id)
    {
        return $this->model->with(['product.images', 'user'])->findOrFail($id);
    }
    public function findByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->with('product')->latest()->get();
    }
    public function findByProductId(int $productId)
    {
        return $this->model->where('product_id', $productId)->with('product')->latest()->get();
    }
    public function findByUserIdAndProductId(int $userId, int $productId)
    {
        return $this->model->where('user_id', $userId)->where('product_id', $productId)->with('product')->latest()->get();
    }
    public function findByUserIdAndProductIdAndStatus(int $userId, int $productId, string $status)
    {
        return $this->model->where('user_id', $userId)->where('product_id', $productId)->where('status', $status)->with('product')->latest()->get();
    }

}
