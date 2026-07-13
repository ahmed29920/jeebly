<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssignDeliveryEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order,
        public array $payload,
    ) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('delivery.' . $this->order->delivery_id);
    }

    public function broadcastAs(): string
    {
        return 'delivery.order.assigned';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
