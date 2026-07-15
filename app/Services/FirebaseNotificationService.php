<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Throwable;

class FirebaseNotificationService
{
    public function sendToToken($token, $title, $body, $data = [])
    {
        if (!$token) {
            return null;
        }

        $messaging = Firebase::messaging();

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData($this->stringifyData($data));

        return $messaging->send($message);
    }

    public function sendToTokens(array $tokens, $title, $body, $data = []): int
    {
        $messaging = Firebase::messaging();
        $sent = 0;
        $payload = $this->stringifyData($data);

        foreach ($tokens as $token) {
            if (!$token) {
                continue;
            }

            try {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification(Notification::create($title, $body))
                    ->withData($payload);

                $messaging->send($message);
                $sent++;
            } catch (Throwable $e) {
                Log::warning('FCM send failed', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $sent;
    }

    /**
     * FCM data payloads must be string key/value pairs.
     */
    protected function stringifyData(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $result[(string) $key] = json_encode($value);
            } else {
                $result[(string) $key] = (string) $value;
            }
        }

        return $result;
    }
}
