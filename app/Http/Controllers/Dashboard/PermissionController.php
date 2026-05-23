<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $permissions = $this->service->all();
        return view('dashboard.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('dashboard.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        $this->service->store($request->all());

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created!');
    }

    public function edit(Permission $permission)
    {
        return view('dashboard.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id
        ]);

        $this->service->update($permission, $request->all());

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated!');
    }

    public function destroy(Permission $permission)
    {
        $this->service->delete($permission);
        return back()->with('success', 'Permission deleted!');
    }
}
