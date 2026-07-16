<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\DeliveryWalletRequest;
use App\Models\Order;
use App\Repositories\DeliveryWalletRequestRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeliveryWalletRequestService
{
    public function __construct(
        protected DeliveryWalletRequestRepository $repository,
        protected DeliveryWalletHistoryService $walletHistoryService,
    ) {
    }

    public function allWithRelations()
    {
        return $this->repository->allWithRelations();
    }

    public function getForDelivery(int $deliveryId, int $limit = 15)
    {
        return $this->repository->paginateForDelivery($deliveryId, $limit);
    }

    public function getSettleableOrders(Delivery $delivery)
    {
        return Order::where('delivery_id', $delivery->id)
            ->where('status', 'completed')
            ->where('payment_status', 'pending')
            ->where(function ($query) {
                $query->whereRaw('LOWER(REPLACE(REPLACE(payment_method, "-", "_"), " ", "_")) IN (?, ?, ?, ?)', [
                    'cod',
                    'cash_on_delivery',
                    'cash',
                    'cashondelivery',
                ]);
            })
            ->whereDoesntHave('walletSettlementRequests', function ($query) {
                $query->whereIn('status', ['pending', 'approved']);
            })
            ->orderByDesc('updated_at')
            ->get();
    }

    public function createWithdrawal(Delivery $delivery, float $amount, ?string $notes = null): DeliveryWalletRequest
    {
        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'amount' => __('messages.withdrawal_amount_must_be_positive'),
            ]);
        }

        $delivery->refresh();

        if ($amount > ($delivery->wallet ?? 0)) {
            throw ValidationException::withMessages([
                'amount' => __('messages.insufficient_wallet_balance'),
            ]);
        }

        if ($this->repository->hasPendingWithdrawal($delivery->id)) {
            throw ValidationException::withMessages([
                'amount' => __('messages.pending_withdrawal_exists'),
            ]);
        }

        return $this->repository->create([
            'delivery_id' => $delivery->id,
            'type'        => 'withdrawal',
            'amount'      => $amount,
            'status'      => 'pending',
            'notes'       => $notes,
        ]);
    }

    public function createSettlement(Delivery $delivery, Order $order, ?string $notes = null): DeliveryWalletRequest
    {
        if ($order->delivery_id !== $delivery->id) {
            throw ValidationException::withMessages([
                'order_id' => __('messages.order_not_assigned_to_you'),
            ]);
        }

        if ($order->status !== 'completed') {
            throw ValidationException::withMessages([
                'order_id' => __('messages.order_must_be_completed_for_settlement'),
            ]);
        }

        if (! DeliveryWalletRequest::isCodPaymentMethod($order->payment_method)) {
            throw ValidationException::withMessages([
                'order_id' => __('messages.order_is_not_cod'),
            ]);
        }

        if ($order->payment_status !== 'pending') {
            throw ValidationException::withMessages([
                'order_id' => __('messages.order_already_settled'),
            ]);
        }

        if ($this->repository->hasActiveSettlementForOrder($order->id)) {
            throw ValidationException::withMessages([
                'order_id' => __('messages.settlement_request_already_exists'),
            ]);
        }

        return $this->repository->create([
            'delivery_id' => $delivery->id,
            'type'        => 'settlement',
            'amount'      => $order->final_total,
            'order_id'    => $order->id,
            'status'      => 'pending',
            'notes'       => $notes,
        ]);
    }

    public function updateStatus(DeliveryWalletRequest $request, string $status, ?string $adminNotes = null): array
    {
        if ($request->status !== 'pending') {
            return ['success' => false, 'message' => __('messages.wallet_request_already_processed')];
        }

        if (! in_array($status, ['approved', 'rejected'], true)) {
            return ['success' => false, 'message' => __('messages.invalid_wallet_request_status')];
        }

        try {
            DB::transaction(function () use ($request, $status, $adminNotes) {
                $request->refresh();

                if ($request->status !== 'pending') {
                    throw new \RuntimeException(__('messages.wallet_request_already_processed'));
                }

                if ($status === 'approved') {
                    $this->processApproval($request);
                }

                $request->update([
                    'status'       => $status,
                    'admin_notes'  => $adminNotes,
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);
            });
        } catch (\InvalidArgumentException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } catch (\RuntimeException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        $messageKey = $status === 'approved'
            ? 'wallet_request_approved_successfully'
            : 'wallet_request_rejected_successfully';

        return ['success' => true, 'message' => __("messages.$messageKey")];
    }

    protected function processApproval(DeliveryWalletRequest $request): void
    {
        $delivery = $request->delivery()->lockForUpdate()->first();

        if ($request->type === 'withdrawal') {
            $this->walletHistoryService->recordDebit(
                $delivery,
                (float) $request->amount,
                'Withdrawal request #' . $request->id . ' approved',
            );

            return;
        }

        $order = $request->order;

        if (! $order) {
            throw new \RuntimeException(__('messages.order_not_found'));
        }

        if ($order->payment_status !== 'pending') {
            throw new \RuntimeException(__('messages.order_already_settled'));
        }

        $order->update(['payment_status' => 'paid']);

        if ($order->transaction) {
            $order->transaction->update([
                'status'         => 'paid',
                'payment_method' => $order->payment_method,
            ]);
        }
    }
}
