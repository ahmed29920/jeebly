<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function allByRole(string $role)
    {
        return $this->model->where('role', $role)->get();
    }

    public function paginateByRole(string $role, int $perPage = 15)
    {
        return $this->model->where('role', $role)->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

    public function findByPhone(string $phone): ?User
    {
        foreach (phone_lookup_variants($phone) as $variant) {
            $user = $this->model->where('phone', $variant)->first();

            if ($user) {
                return $user;
            }
        }

        return null;
    }
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
    public function findByVerificationCode(string $invitation_code): ?User
    {
        return $this->model->where('invitation_code', $invitation_code)->first();
    }
}
