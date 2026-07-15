<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DeliveryOrderAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Order $order,
        public readonly string $title,
        public readonly string $body,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'type' => 'delivery_order_assigned',
            'order_id' => $this->order->id,
            'order_uuid' => $this->order->uuid,
            'customer_name' => $this->order->user?->name,
            'total' => $this->order->final_total,
            'status' => $this->order->status,
            'delivery_id' => $this->order->delivery_id,
            'data' => [
                'type' => 'delivery_order_assigned',
                'order_id' => (string) $this->order->id,
                'order_uuid' => (string) $this->order->uuid,
                'order_status' => (string) $this->order->status,
                'delivery_id' => (string) ($this->order->delivery_id ?? ''),
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ];
    }
}
