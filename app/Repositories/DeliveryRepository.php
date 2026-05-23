<?php

namespace App\Repositories;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;

class DeliveryRepository
{
    protected $model;

    public function __construct(Delivery $delivery)
    {
        $this->model = $delivery;
    }

    public function all(int $branchId = null)
    {

        $query = $this->model->with('branch');

        if ($branchId) {
            $query->where(function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
                $query->orWhereNull('branch_id');
            });
        }
        return $query->get();
    }

    public function findById(int $id)
    {
        return $this->model->with('branch')->findOrFail($id);
    }
    public function findByUserId(int $userId)
    {
        return $this->model->with('branch')->where('user_id', $userId)->get();
    }
    public function create(array $data): Delivery
    {
        return $this->model->create($data);
    }

    public function update(Delivery $delivery, array $data): Delivery
    {
        $delivery->update($data);
        return $delivery;
    }

    public function delete(Delivery $delivery): bool
    {
        return $delivery->delete();
    }
    public function bulkAction(array $ids, string $action)
    {
        return $this->model->whereIn('id', $ids)->update(['is_active' => $action == 'activate' ? true : false]);
    }
}
