<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Services\OfferService;

class OfferController extends Controller
{
    protected $service;

    public function __construct(OfferService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $offers = $this->service->getActiveOffers();
        return OfferResource::collection($offers);
    }
}
