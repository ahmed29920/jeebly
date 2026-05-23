<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
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
        $branchId = auth()->user()->branch_id;
        $branch = $this->service->findById($branchId);
        return view('branch.branches.show', compact('branch'));
    }
}
