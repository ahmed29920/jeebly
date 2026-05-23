<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    protected $model;

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    public function all()
    {
        return $this->model
            ->with($this->listRelations())
            ->latest()
            ->paginate(10);
    }

    public function allForBranch($branchId)
    {
        return $this->model
            ->whereHas('order', function ($query) use ($branchId) {
                $query->withTrashed()->where('branch_id', $branchId);
            })
            ->with($this->listRelations())
            ->latest()
            ->get();
    }

    public function findByIdForBranch($id, $branchId)
    {
        return $this->model
            ->whereHas('order', function ($query) use ($branchId) {
                $query->withTrashed()->where('branch_id', $branchId);
            })
            ->with($this->detailRelations())
            ->find($id);
    }

    public function findById($id): ?Transaction
    {
        return $this->model
            ->with($this->detailRelations())
            ->find($id);
    }

    public function findByUUID($uuid): ?Transaction
    {
        return $this->model
            ->with($this->detailRelations())
            ->where('uuid', $uuid)
            ->first();
    }

    public function findByUUIDForBranch($uuid, $branchId): ?Transaction
    {
        return $this->model
            ->whereHas('order', function ($query) use ($branchId) {
                $query->withTrashed()->where('branch_id', $branchId);
            })
            ->with($this->detailRelations())
            ->where('uuid', $uuid)
            ->first();
    }

    public function findByUserId(int $userId, $limit)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['order' => fn ($query) => $query->withTrashed()])
            ->paginate($limit);
    }

    public function findByIdForUser(int $userId, int $id)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with($this->detailRelations())
            ->find($id);
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        $transaction->update($data);

        return $transaction;
    }

    public function delete(Transaction $transaction): bool
    {
        return $transaction->delete();
    }

    public function bulkAction(array $ids, string $action)
    {
        $query = $this->model->whereIn('id', $ids);

        return match ($action) {
            'delete' => $query->delete(),
            default => false,
        };
    }

    protected function listRelations(): array
    {
        return [
            'user',
            'order' => fn ($query) => $query->withTrashed(),
        ];
    }

    protected function detailRelations(): array
    {
        return [
            'user',
            'order' => fn ($query) => $query->withTrashed(),
            'order.user',
            'order.items.product' => fn ($query) => $query->withTrashed(),
            'order.items.variant' => fn ($query) => $query->withTrashed(),
        ];
    }
}
