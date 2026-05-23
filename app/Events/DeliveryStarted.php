<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $delivery;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order)
    {
        // Ensure relationships are loaded
        if (!$order->relationLoaded('delivery')) {
            $order->load('delivery.user');
        }
        if (!$order->delivery || !$order->delivery->relationLoaded('user')) {
            $order->load('delivery.user');
        }

        $this->order = $order;
        $this->delivery = $order->delivery;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order-delivery.' . $this->order->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'DeliveryStarted';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $data = [
            'order_id' => $this->order->id,
            'order_uuid' => $this->order->uuid,
            'status' => $this->order->status,
            'started_at' => now()->toIso8601String(),
        ];

        if ($this->delivery && $this->delivery->user) {
            $data['delivery'] = [
                'id' => $this->delivery->id,
                'name' => $this->delivery->user->name,
                'phone' => $this->delivery->user->phone,
                'plate_number' => $this->delivery->plate_number,
                'vehicle_name' => $this->delivery->vehicle_name,
            ];
        }

        return $data;
    }
}
