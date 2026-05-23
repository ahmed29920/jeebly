<?php

namespace App\Http\Controllers\Api\Branch;

use App\Http\Controllers\Controller;
use App\Services\BranchService;
use App\Http\Resources\BranchResource;
use Illuminate\Support\Facades\Auth;
class BranchController extends Controller
{
    protected $service;

    public function __construct(BranchService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $branch = $this->service->findById($branchId);
        return BranchResource::make($branch);
    }
}
