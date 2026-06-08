<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model
            ->with(['branch', 'user'])
            ->latest()
            ->get();
    }

    public function allForBranch(int $branchId, ?string $q = null)
    {
        return $this->model
            ->where('branch_id', $branchId)
            ->when($q, function ($query) use ($q) {
                if (is_numeric($q)) {
                    $query->where('id', $q);
                } else {
                    $query->whereHas('user', function ($userQuery) use ($q) {
                        $userQuery->where('name', 'like', "%{$q}%");
                    });
                }
            })
            ->with(['branch', 'user'])
            ->latest()
            ->get();
    }

    public function find(int $id): ?Order
    {
        return $this->model
            ->withTrashed()
            ->with($this->orderDetailRelations())
            ->find($id);
    }

    public function findForBranch(int $id, int $branchId): ?Order
    {
        return $this->model
            ->where('branch_id', $branchId)
            ->with($this->orderDetailRelations())
            ->find($id);
    }

    public function findByUUID(string $uuid): ?Order
    {
        return $this->model
            ->withTrashed()
            ->with($this->orderDetailRelations(includeDelivery: true))
            ->where('uuid', $uuid)
            ->first();
    }

    public function findByUUIDForBranch(string $uuid, int $branchId): ?Order
    {
        return $this->model
            ->where('branch_id', $branchId)
            ->with($this->orderDetailRelations())
            ->where('uuid', $uuid)
            ->first();
    }

    public function findByUserId(int $userId, $limit)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with($this->orderDetailRelations())
            ->latest()
            ->paginate($limit);
    }

    public function findByIdForUser(int $userId, int $id)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with($this->orderDetailRelations())
            ->find($id);
    }

    public function findById(int $id): ?Order
    {
        return $this->model
            ->withTrashed()
            ->with($this->orderDetailRelations())
            ->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function allForDelivery($deliveryId)
    {
        return $this->model
            ->where('delivery_id', $deliveryId)
            ->with($this->orderDetailRelations())
            ->get();
    }

    public function findByIdForDelivery($deliveryId, $id)
    {
        return $this->model
            ->where('delivery_id', $deliveryId)
            ->with($this->orderDetailRelations())
            ->find($id);
    }

    protected function orderDetailRelations(bool $includeDelivery = false): array
    {
        $relations = [
            'branch',
            'user',
            'items.product' => fn ($query) => $query->withTrashed()->with('unit'),
            'items.variant' => fn ($query) => $query->withTrashed(),
            'shippingAddress',
            'billingAddress',
            'coupon',
            'offer',
        ];

        if ($includeDelivery) {
            $relations['delivery.user'] = fn ($query) => $query;
            $relations['delivery.branch'] = fn ($query) => $query;
        }

        return $relations;
    }
}
