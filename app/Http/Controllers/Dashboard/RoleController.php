<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use App\Services\RoleService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $service;
    protected $permissionService;
    public function __construct(RoleService $service, PermissionService $permissionService)
    {
        $this->service = $service;
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        $roles = $this->service->all();
        return view('dashboard.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->permissionService->all();
        return view('dashboard.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array'
        ]);

        $this->service->store($request->all());

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = $this->permissionService->all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('dashboard.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array'
        ]);

        $this->service->update($role, $request->all());

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->service->delete($role);
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
