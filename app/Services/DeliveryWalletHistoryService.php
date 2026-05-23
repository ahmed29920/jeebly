<?php

namespace App\Services;

use App\Models\DeliveryWalletHistory;

class DeliveryWalletHistoryService
{
    public function getHistoryForDelivery($deliveryId, $limit = 15)
    {
        return DeliveryWalletHistory::where('delivery_id', $deliveryId)
            ->with('order')
            ->orderByDesc('created_at')
            ->paginate($limit);
    }
    public function recordCredit($delivery, $order, $amount)
    {
        // Refresh delivery to get current wallet balance
        $delivery->refresh();

        $walletBefore = $delivery->wallet ?? 0;
        $walletAfter = $walletBefore + $amount;

        // Update delivery wallet balance
        $delivery->increment('wallet', $amount);

        // Record history
        DeliveryWalletHistory::create([
            'delivery_id'    => $delivery->id,
            'order_id'       => $order->id,
            'amount'         => $amount,
            'type'           => 'credit',
            'wallet_before'  => $walletBefore,
            'wallet_after'   => $walletAfter,
            'notes'          => 'Payment for completed order #' . $order->id,
        ]);
    }
}
