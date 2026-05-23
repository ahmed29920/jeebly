<?php

namespace App\Repositories;

use App\Models\Slider;
use Illuminate\Support\Collection;

class SliderRepository
{
    protected $model;

    public function __construct(Slider $slider)
    {
        $this->model = $slider;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }
    public function findAllActive(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }
    public function create(array $data): Slider
    {
        return $this->model->create($data);
    }

    public function update(Slider $slider, array $data): Slider
    {
        $slider->update($data);
        return $slider;
    }

    public function delete(Slider $slider): bool
    {
        return $slider->delete();
    }
}
