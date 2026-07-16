<?php

namespace App\Repositories;

use App\Models\DeliveryWalletRequest;

class DeliveryWalletRequestRepository
{
    public function __construct(protected DeliveryWalletRequest $model)
    {
    }

    public function allWithRelations()
    {
        return $this->model->with(['delivery.user', 'order', 'processedBy'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function paginateForDelivery(int $deliveryId, int $limit = 15)
    {
        return $this->model->with('order')
            ->where('delivery_id', $deliveryId)
            ->orderByDesc('created_at')
            ->paginate($limit);
    }

    public function find(int $id): DeliveryWalletRequest
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): DeliveryWalletRequest
    {
        return $this->model->create($data);
    }

    public function hasPendingWithdrawal(int $deliveryId): bool
    {
        return $this->model->where('delivery_id', $deliveryId)
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->exists();
    }

    public function hasActiveSettlementForOrder(int $orderId): bool
    {
        return $this->model->where('order_id', $orderId)
            ->where('type', 'settlement')
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }
}
