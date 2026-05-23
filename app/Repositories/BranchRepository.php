<?php

namespace App\Repositories;

use App\Models\Branch;

class BranchRepository
{
    protected $model;

    public function __construct(Branch $branch)
    {
        $this->model = $branch;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->firstOrFail();
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Branch
    {
        return $this->model->create($data);
    }

    public function update(Branch $branch, array $data): Branch
    {
        $branch->update($data);

        return $branch;
    }

    public function delete(Branch $branch): bool
    {
        return $branch->delete();
    }

    public function findClosestBranch($latitude, $longitude)
    {
        return $this->model
            ->selectRaw('
            *,
            (6371 * ACOS(
                COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                COS(RADIANS(longitude) - RADIANS(?)) +
                SIN(RADIANS(?)) * SIN(RADIANS(latitude))
            )) AS distance
        ', [$latitude, $longitude, $latitude])
            ->orderBy('distance')
            ->first();
    }
}
