<?php

namespace App\Repositories;

use App\Models\Variant;

class VariantRepository
{
    protected $model;

    public function __construct(Variant $variant)
    {
        $this->model = $variant;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findById(int $id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }

    public function create(array $data): Variant
    {
        return $this->model->create($data);
    }

    public function update(Variant $variant, array $data): Variant
    {
        $variant->update($data);
        return $variant;
    }

    public function delete(Variant $variant): bool
    {
        return $variant->delete();
    }
}
