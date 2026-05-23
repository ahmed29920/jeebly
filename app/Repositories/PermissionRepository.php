<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create(['name' => $data['name']]);
    }

    public function update($permission, array $data)
    {
        $permission->update(['name' => $data['name']]);
        return $permission;
    }

    public function delete($permission)
    {
        return $permission->delete();
    }
}
