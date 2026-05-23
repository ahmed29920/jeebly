<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Services\ZoneService;
use App\Http\Resources\ZoneResource;
class ZoneController extends Controller
{
    public function __construct(
        protected ZoneService $zoneService
    ) {}

    public function index()
    {
        $zones = $this->zoneService->getAllZones();
        return ZoneResource::collection($zones);
    }
}
