<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;

class TransactionService
{
    protected $orderService;
    protected $transactionRepo;

    public function __construct(OrderService $orderService, TransactionRepository $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
        $this->orderService = $orderService;
    }

    public function all()
    {
        return $this->transactionRepo->all();
    }
    public function allForBranch($branchId)
    {
        return $this->transactionRepo->allForBranch($branchId);
    }

    public function find($id)
    {
        return $this->transactionRepo->findById($id);
    }
    public function findByUUID($uuid)
    {
        return $this->transactionRepo->findByUUID($uuid);
    }
    public function findByUUIDForBranch($uuid, $branchId)
    {
        return $this->transactionRepo->findByUUIDForBranch($uuid, $branchId);
    }
    public function findByIdForBranch($id, $branchId)
    {
        return $this->transactionRepo->findByIdForBranch($id, $branchId);
    }

    public function getUserTransactions($userId, $limit)
    {
        return $this->transactionRepo->findByUserId($userId, $limit);
    }
    public function getUserTransactionById($userId, $id)
    {
        return $this->transactionRepo->findByIdForUser($userId, $id);
    }

    public function pay($id)
    {
        $userId = auth()->id();
        $transaction = $this->transactionRepo->findByIdForUser($userId, $id);

        if (!$transaction) {
            abort(404, __('messages.transaction_not_found'));
        }

        if ($transaction->status !== 'pending') {
            abort(400, __('messages.transaction_cannot_be_paid', ['status' => $transaction->status]));
        }

        try {
            // just for test
            $paymentResponse = [
                'status' => 'paid', // or 'failed'
                'transaction_id' => 'TRX123456',
                'reference_number' => 'REF987654',
            ];

            if ($paymentResponse['status'] === 'paid') {
                $transaction->update([
                    'status'            => 'paid',
                    'transaction_id'    => $paymentResponse['transaction_id'],
                    'reference_number'  => $paymentResponse['reference_number'],
                ]);

                $transaction->order->update([
                    'payment_status' => 'paid'
                ]);

                RealtimeService::orderUpdated($transaction->order->fresh(['user', 'branch', 'items']));

                return $transaction;
            }

            $transaction->update(['status' => 'failed']);
            $transaction->order->update([
                'payment_status' => 'failed'
            ]);

            RealtimeService::orderUpdated($transaction->order->fresh(['user', 'branch', 'items']));

            abort(402, __('messages.payment_failed'));
        } catch (\Exception $e) {
            abort(500, __('messages.payment_error', ['error' => $e->getMessage()]));
        }
    }


    public function update(Transaction $order, $data)
    {
        $order->update($data);
        return ['success' => true, 'message' => __('messages.transaction_updated_successfully')];
    }
}
