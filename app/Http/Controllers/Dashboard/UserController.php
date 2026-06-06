<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\CreateUserRequest;
use App\Http\Requests\Web\Dashboard\EditUserRequest;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service , $roleService;

    public function __construct(UserService $service, RoleService $roleService)
    {
        $this->service = $service;
        $this->roleService = $roleService;
    }

    public function index(string $role)
    {
        $users = $this->service->getUsersByRole($role);

        return view('dashboard.users.index', compact('users', 'role'));
    }

    public function create()
    {
        $roles = $this->roleService->all();
        return view('dashboard.users.create', compact('roles'));
    }

    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $this->service->createUser($data);

        return redirect()->route('admin.users.index', $data['role'])->with('success', 'User created!');
    }

    public function edit(User $user)
    {
        $roles = $this->roleService->all();
        return view('dashboard.users.edit', compact('user','roles'));
    }

    public function update(EditUserRequest $request, User $user)
    {

        
        $data = $request->only(['name', 'email', 'role',]);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $this->service->updateUser($user, $data);

        return redirect()->route('admin.users.index', $data['role'])->with('success', 'User updated!');
    }

    public function destroy(User $user)
    {
        $role = $user->getRoleNames()->first() ?? 'user';
        $this->service->deleteUser($user);
        return back()->with('success', 'User deleted!')->with('role', $role);
    }
}
