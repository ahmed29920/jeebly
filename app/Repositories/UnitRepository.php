<?php

namespace App\Repositories;

use App\Models\Unit;

class UnitRepository
{
    protected $model;

    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }

    public function all($limit = -1)
    {
        $query = $this->model->orderBy('id', 'desc');

        if ($limit == -1) {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->firstOrFail();
    }

    public function create(array $data): Unit
    {
        return $this->model->create($data);
    }

    public function update(Unit $unit, array $data): Unit
    {
        $unit->update($data);
        return $unit;
    }

    public function delete(Unit $unit): bool
    {
        return $unit->delete();
    }

    public function bulkAction(array $ids, string $action)
    {
        switch ($action) {
            case 'delete':
                return $this->model->whereIn('id', $ids)->delete();

            case 'activate':
                return $this->model->whereIn('id', $ids)->update(['is_active' => 1]);

            case 'deactivate':
                return $this->model->whereIn('id', $ids)->update(['is_active' => 0]);

            default:
                return false;
        }
    }
}

