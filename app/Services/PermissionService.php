<?php

namespace App\Services;

use App\Repositories\PermissionRepository;

class PermissionService
{
    protected $repo;

    public function __construct(PermissionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function store(array $data)
    {
        return $this->repo->create($data);
    }

    public function update($permission, array $data)
    {
        return $this->repo->update($permission, $data);
    }

    public function delete($permission)
    {
        return $this->repo->delete($permission);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }
}
