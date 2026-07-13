<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order,
        public array $payload,
        public ?int $previousBranchId = null,
        public ?int $previousDeliveryId = null,
    ) {}

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('admin.orders'),
            new PrivateChannel('user.'.$this->order->user_id.'.orders'),
        ];

        if ($this->order->branch_id) {
            $channels[] = new PrivateChannel('branch.'.$this->order->branch_id.'.orders');
        }

        if ($this->previousBranchId && (int) $this->previousBranchId !== (int) $this->order->branch_id) {
            $channels[] = new PrivateChannel('branch.'.$this->previousBranchId.'.orders');
        }

        if ($this->order->delivery_id) {
            $channels[] = new PrivateChannel('delivery.'.$this->order->delivery_id);
        }

        if ($this->previousDeliveryId && (int) $this->previousDeliveryId !== (int) $this->order->delivery_id) {
            $channels[] = new PrivateChannel('delivery.'.$this->previousDeliveryId);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'order.updated';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
