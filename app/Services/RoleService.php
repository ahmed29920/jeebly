<?php

namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService
{
    protected $repo;

    public function __construct(RoleRepository $repo)
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

    public function update($role, array $data)
    {
        return $this->repo->update($role, $data);
    }

    public function delete($role)
    {
        return $this->repo->delete($role);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }
}
