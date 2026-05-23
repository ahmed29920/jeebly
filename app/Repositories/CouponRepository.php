<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepository
{
    protected $model;

    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }
    public function all()
    {
        return $this->model->latest()->paginate(10);
    }

    public function find($id): ?Coupon
    {
        return $this->model->find($id);
    }

    public function findByCode(string $code): ?Coupon
    {
        return $this->model->where('code', $code)->first();
    }

    public function create(array $data): Coupon
    {
        return $this->model->create($data);
    }

    public function update(Coupon $coupon, array $data): Coupon
    {
        $coupon->update($data);
        return $coupon;
    }

    public function delete(Coupon $coupon): bool
    {
        return $coupon->delete();
    }

    public function bulkAction(array $ids, string $action)
    {
        $query = $this->model->whereIn('id', $ids);

        return match ($action) {
            'delete' => $query->delete(),
            'activate' => $query->update(['is_active' => true]),
            'deactivate' => $query->update(['is_active' => false]),
            default => false,
        };
    }
}
