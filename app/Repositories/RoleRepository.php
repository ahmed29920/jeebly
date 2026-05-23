<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleRepository
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('permissions')->get();
    }

    public function find($id)
    {
        return $this->model->with('permissions')->findOrFail($id);
    }

    public function create(array $data)
    {
        $role = $this->model->create(['name' => $data['name']]);

        if (!empty($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        }

        return $role;
    }

    public function update($role, array $data)
    {
        $role->update(['name' => $data['name']]);

        if (!empty($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return $role;
    }

    public function delete($role)
    {
        return $role->delete();
    }
}
