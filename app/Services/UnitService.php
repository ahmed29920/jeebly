<?php

namespace App\Services;

use App\Models\Unit;
use App\Repositories\UnitRepository;

class UnitService
{
    protected $unitRepo;

    public function __construct(UnitRepository $unitRepo)
    {
        $this->unitRepo = $unitRepo;
    }

    public function all($limit = -1)
    {
        return $this->unitRepo->all($limit);
    }

    public function find(int $id)
    {
        return $this->unitRepo->find($id);
    }

    public function findByCode(string $code)
    {
        return $this->unitRepo->findByCode($code);
    }

    public function store(array $data): Unit
    {
        return $this->unitRepo->create($data);
    }

    public function update(Unit $unit, array $data): Unit
    {
        return $this->unitRepo->update($unit, $data);
    }

    public function delete(Unit $unit): bool
    {
        return $this->unitRepo->delete($unit);
    }

    public function bulkAction(array $ids, string $action)
    {
        return $this->unitRepo->bulkAction($ids, $action);
    }
}

