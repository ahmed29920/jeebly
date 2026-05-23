<?php

namespace App\Services;

use App\Mail\BookingCreatedMail;
use App\Models\BookingList;
use App\Repositories\BookingListRepository;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingListService
{
    protected $bookingListRepo;
    protected $productService;
    public function __construct(BookingListRepository $bookingListRepo, ProductService $productService)
    {
        $this->bookingListRepo = $bookingListRepo;
        $this->productService = $productService;
    }

    public function all()
    {
        return $this->bookingListRepo->all();
    }
    public function findById(int $id)
    {
        return $this->bookingListRepo->findById($id);
    }
    public function findByUserId(int $userId)
    {
        return $this->bookingListRepo->findByUserId($userId);
    }
    public function findByProductId(int $productId)
    {
        return $this->bookingListRepo->findByProductId($productId);
    }
    public function findByUserIdAndProductId(int $userId, int $productId)
    {
        return $this->bookingListRepo->findByUserIdAndProductId($userId, $productId);
    }
    public function findByUserIdAndProductIdAndStatus(int $userId, int $productId, string $status)
    {
        return $this->bookingListRepo->findByUserIdAndProductIdAndStatus($userId, $productId, $status);
    }
    public function create(array $data)
    {
        $data['user_id'] = Auth::user()->id;
        $product = $this->productService->find($data['product_id']);
        if (!$product) {
            throw new \Exception(__('messages.product_not_found'));
        }
        if ($product->manager()->stock() >= $data['quantity']) {
            throw new \Exception(__('messages.product_in_stock_order_instead'));
        }

        $bookingList = $this->bookingListRepo->create($data);

        // Load relationships for email
        $bookingList->load('product', 'user');

        // Send booking confirmation email
        if ($bookingList->user && $bookingList->user->email) {
            Mail::to($bookingList->user->email)->send(new BookingCreatedMail($bookingList));
        }

        return $bookingList;
    }
    public function update(BookingList $bookingList, array $data)
    {
        $data['status'] = $data['status'] ?? 'pending';
        return $this->bookingListRepo->update($bookingList, $data);
    }
    public function delete(BookingList $bookingList)
    {
        return $this->bookingListRepo->delete($bookingList);
    }
}
