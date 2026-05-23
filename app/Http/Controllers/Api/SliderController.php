<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Services\SliderService;

class SliderController extends Controller
{
    protected $service;

    public function __construct(SliderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $sliders = $this->service->all();
        return SliderResource::collection($sliders);
    }
}
