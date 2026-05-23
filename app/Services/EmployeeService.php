<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeService
{
    protected $userRepository;
    protected $branchService;

    public function __construct(UserRepository $userRepository, BranchService $branchService)
    {
        $this->userRepository = $userRepository;
        $this->branchService = $branchService;
    }

    public function all($limit = -1)
    {
        $query = User::where('role', 'employee')->with('branch');

        if ($limit == -1) {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public function findByBranch($branchId)
    {
        return User::where('role', 'employee')
            ->where('branch_id', $branchId)
            ->with('branch')
            ->get();
    }

    public function find(int $id)
    {
        return User::where('role', 'employee')
            ->where('id', $id)
            ->with('branch')
            ->firstOrFail();
    }

    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' => 'employee',
                'branch_id' => $data['branch_id'],
                'is_active' => $data['is_active'] ?? true,
                'is_verified' => true,
                'invitation_code' => random_int(100000, 999999),
            ];

            if (isset($data['image'])) {
                $userData['image'] = $data['image']->store('uploads/users', 'public');
            }

            $user = $this->userRepository->create($userData);
            $user->assignRole('employee');

            return $user;
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'branch_id' => $data['branch_id'],
                'is_active' => $data['is_active'] ?? $user->is_active,
            ];

            if (isset($data['password']) && !empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            if (isset($data['image'])) {
                $userData['image'] = $data['image']->store('uploads/users', 'public');
            }

            return $this->userRepository->update($user, $userData);
        });
    }

    public function delete(User $user): bool
    {
        if ($user->role !== 'employee') {
            throw new \Exception(__('messages.user_is_not_an_employee'));
        }

        return DB::transaction(function () use ($user) {
            return $this->userRepository->delete($user);
        });
    }

    public function bulkAction(array $ids, string $action)
    {
        return DB::transaction(function () use ($ids, $action) {
            $users = User::where('role', 'employee')->whereIn('id', $ids)->get();

            switch ($action) {
                case 'delete':
                    foreach ($users as $user) {
                        $this->userRepository->delete($user);
                    }
                    return true;

                case 'activate':
                    return User::where('role', 'employee')
                        ->whereIn('id', $ids)
                        ->update(['is_active' => 1]);

                case 'deactivate':
                    return User::where('role', 'employee')
                        ->whereIn('id', $ids)
                        ->update(['is_active' => 0]);

                default:
                    return false;
            }
        });
    }
}

