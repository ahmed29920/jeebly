<?php

namespace App\Events;

use App\Models\Delivery;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeliveryLocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $deliveryId;
    public $orderId;
    public $lat;
    public $lng;

    /**
     * Create a new event instance.
     */
    public function __construct($deliveryId, $orderId, $lat, $lng)
    {
        $this->deliveryId = $deliveryId;
        $this->orderId = $orderId;
        $this->lat = $lat;
        $this->lng = $lng;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order-delivery.'.$this->orderId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'DeliveryLocationUpdated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'delivery_id' => $this->deliveryId,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'updated_at' => now()->toIso8601String(),
        ];
    }
}
