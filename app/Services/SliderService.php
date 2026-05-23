<?php

namespace App\Services;

use App\Models\Slider;
use App\Repositories\SliderRepository;

class SliderService
{
    public function __construct(
        protected SliderRepository $sliderRepo
    ) {}

    public function all()
    {
        return $this->sliderRepo->all();
    }

    public function findById(int $id)
    {
        return $this->sliderRepo->find($id);
    }
    public function store(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('sliders', 'public');
        }
        return $this->sliderRepo->create($data);
    }

    public function update(Slider $slider, array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('sliders', 'public');
        }
        return $this->sliderRepo->update($slider, $data);
    }

    public function delete(Slider $slider)
    {
        return $this->sliderRepo->delete($slider);
    }
}
