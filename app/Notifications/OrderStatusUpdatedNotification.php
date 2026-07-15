<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Order $order,
        public readonly string $status,
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
            'type' => 'order_status_updated',
            'order_id' => $this->order->id,
            'order_uuid' => $this->order->uuid,
            'order_status' => $this->status,
            'data' => [
                'type' => 'order_status_updated',
                'order_id' => (string) $this->order->id,
                'order_uuid' => (string) $this->order->uuid,
                'order_status' => $this->status,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ];
    }
}
