<?php

namespace App\Services;

use App\Models\Branch;
use App\Repositories\BranchRepository;
use Illuminate\Support\Facades\Auth;

class BranchService
{
    public function __construct(
        protected BranchRepository $branchRepo
    ) {}

    public function all()
    {
        $user = Auth::user();

        // If user is an employee, only return their branch
        if ($user && $user->role === 'employee' && $user->branch_id) {
            return collect([$this->branchRepo->findById($user->branch_id)]);
        }

        return $this->branchRepo->all();
    }

    public function findBySlug(string $slug)
    {
        return $this->branchRepo->findBySlug($slug);
    }

    public function findById(int $id)
    {
        return $this->branchRepo->findById($id);
    }
    public function store(array $data)
    {
        return $this->branchRepo->create($data);
    }

    public function update(Branch $branch, array $data)
    {
        return $this->branchRepo->update($branch, $data);
    }

    public function delete(Branch $branch)
    {
        return $this->branchRepo->delete($branch);
    }

    public function findClosestBranch($latitude, $longitude)
    {
        return $this->branchRepo->findClosestBranch($latitude, $longitude);
    }
}
