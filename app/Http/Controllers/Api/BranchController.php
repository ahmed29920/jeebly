<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Services\BranchService;

class BranchController extends Controller
{
    protected $service;

    public function __construct(BranchService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $branches = $this->service->all();
        return BranchResource::collection($branches);
    }
}
