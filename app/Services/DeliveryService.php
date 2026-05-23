<?php

namespace App\Services;

use App\Models\Delivery;
use App\Repositories\DeliveryRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DeliveryService
{
    protected $deliveryRepo;
    protected $userRepo;

    public function __construct(DeliveryRepository $deliveryRepo, UserRepository $userRepo)
    {
        $this->deliveryRepo = $deliveryRepo;
        $this->userRepo = $userRepo;
    }

    public function all($branchId = null, $limit = -1)
    {
        return $this->deliveryRepo->all($branchId);
    }

    public function find(int $id)
    {
        return $this->deliveryRepo->findById($id);
    }

    public function findByUserId(int $userId)
    {
        return $this->deliveryRepo->findByUserId($userId);
    }

    public function store(array $data): Delivery
    {
        /** @var array<int, int> $zoneIds */
        $zoneIds = [];
        if (isset($data['zone_ids']) && is_array($data['zone_ids'])) {
            $zoneIds = array_values(array_filter(array_map('intval', $data['zone_ids'])));
        }
        unset($data['zone_ids']);

        // Handle documents upload
        $data = $this->handleDocuments($data);
      
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'delivery',
            'branch_id' => $data['branch_id'] ?? null,
        ];
        $user = $this->userRepo->create($userData);
        $data['user_id'] = $user->id;
        $delivery = $this->deliveryRepo->create($data);
        $delivery->zones()->sync($zoneIds);

        return $delivery;
    }

    public function update(Delivery $delivery, array $data): Delivery
    {
        /** @var array<int, int> $zoneIds */
        $zoneIds = [];
        if (isset($data['zone_ids']) && is_array($data['zone_ids'])) {
            $zoneIds = array_values(array_filter(array_map('intval', $data['zone_ids'])));
        }
        unset($data['zone_ids']);

        // Handle documents upload
        $data = $this->handleDocuments($data, $delivery);

        // Update user information
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'branch_id' => $data['branch_id'],
        ];

        // Update password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        $this->userRepo->update($delivery->user, $userData);

        // Remove user fields from data before updating delivery
        unset($data['name'], $data['email'], $data['phone'], $data['password']);

        $delivery = $this->deliveryRepo->update($delivery, $data);
        $delivery->zones()->sync($zoneIds);

        return $delivery;
    }

    public function delete(Delivery $delivery): bool
    {
        // Delete associated documents before deleting delivery
        $this->deleteDocuments($delivery);

        return $this->deliveryRepo->delete($delivery);
    }

    public function bulkAction(array $ids, string $action)
    {
        return $this->deliveryRepo->bulkAction($ids, $action);
    }

    /**
     * Handle document uploads
     *
     * @param array $data
     * @param Delivery|null $delivery For update operations, to handle old documents
     * @return array
     */
    protected function handleDocuments(array $data, ?Delivery $delivery = null): array
    {
        $documentPaths = [];

        // Process uploaded documents
        if (isset($data['documents']) && is_array($data['documents']) && !empty($data['documents'])) {
            foreach ($data['documents'] as $document) {
                if ($document instanceof \Illuminate\Http\UploadedFile && $document->isValid()) {
                    $path = $document->store('deliveries/documents', 'public');
                    $documentPaths[] = $path;
                }
            }
        }

        // Handle documents based on operation type
        if (!empty($documentPaths)) {
            // If updating and new documents are uploaded, delete old ones and set new paths
            if ($delivery) {
                $this->deleteDocuments($delivery);
            }
            $data['documents'] = $documentPaths;
        } else {
            // No new documents uploaded
            if ($delivery) {
                // For update: keep existing documents (don't modify the documents field)
                unset($data['documents']);
            } else {
                // For create: set empty array if no documents
                $data['documents'] = [];
            }
        }

        return $data;
    }

    /**
     * Delete documents from storage
     *
     * @param Delivery $delivery
     * @return void
     */
    protected function deleteDocuments(Delivery $delivery): void
    {
        if ($delivery->documents && is_array($delivery->documents)) {
            foreach ($delivery->documents as $documentPath) {
                if (Storage::disk('public')->exists($documentPath)) {
                    Storage::disk('public')->delete($documentPath);
                }
            }
        }
    }
}
