<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AdminManualNotification;
use Illuminate\Support\Collection;

class AdminNotificationService
{
    public function __construct(
        protected FirebaseNotificationService $firebase,
    ) {}

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\User>  $users
     */
    public function sendToUsers(Collection $users, string $title, string $body, array $data, ?int $sentByUserId): array
    {
        $dbCount = 0;
        $tokenList = [];

        foreach ($users as $user) {
            $user->notify(new AdminManualNotification(
                title: $title,
                body: $body,
                data: $data,
                sentByUserId: $sentByUserId,
            ));
            $dbCount++;

            if (!empty($user->fcm_token)) {
                $tokenList[] = $user->fcm_token;
            }
        }

        $pushCount = 0;
        if (count($tokenList) > 0) {
            // Current Firebase service sends one-by-one; we count tokens attempted.
            $this->firebase->sendToTokens($tokenList, $title, $body, $data);
            $pushCount = count($tokenList);
        }

        return [
            'database' => $dbCount,
            'push' => $pushCount,
        ];
    }

    public function usersByAudience(string $audience, array $userIds = []): \Generator
    {
        $query = User::query()->where('role', 'user')->orderBy('id');

        if ($audience === 'selected') {
            $query->whereIn('id', $userIds);
        }

        foreach ($query->cursor() as $user) {
            yield $user;
        }
    }
}

