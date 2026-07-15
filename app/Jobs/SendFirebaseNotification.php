<?php

namespace App\Jobs;

use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendFirebaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $token,
        public string $title,
        public string $body,
        public array $data = [],
    ) {}

    public function handle(FirebaseNotificationService $firebase): void
    {
        try {
            $firebase->sendToToken(
                $this->token,
                $this->title,
                $this->body,
                $this->data + ['click_action' => 'FLUTTER_NOTIFICATION_CLICK'],
            );
        } catch (Throwable $e) {
            Log::warning('FCM job send failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
