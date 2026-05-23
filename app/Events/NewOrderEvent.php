<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new Channel('orders'); 
    }

    public function broadcastAs()
    {
        return 'NewOrderEvent'; 
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->order->id,
            'customer_name' => $this->order->user->name,
            'total' => $this->order->final_total,
            'status' => $this->order->status,
        ];
    }
}
