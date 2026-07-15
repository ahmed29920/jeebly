<?php

namespace App\Services;

use App\Models\Delivery;
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
    public function sendToUsers(
        Collection $users,
        string $title,
        string $body,
        array $data,
        ?int $sentByUserId,
        bool $sendInApp = true,
        bool $sendPush = true,
    ): array {
        $dbCount = 0;
        $tokenList = [];

        foreach ($users as $user) {
            if ($sendInApp) {
                $user->notify(new AdminManualNotification(
                    title: $title,
                    body: $body,
                    data: $data,
                    sentByUserId: $sentByUserId,
                ));
                $dbCount++;
            }

            if ($sendPush && !empty($user->fcm_token)) {
                $tokenList[] = $user->fcm_token;
            }
        }

        $pushCount = 0;
        if ($sendPush && count($tokenList) > 0) {
            $pushCount = $this->firebase->sendToTokens($tokenList, $title, $body, $data);
        }

        return [
            'database' => $dbCount,
            'push' => $pushCount,
        ];
    }

    /**
     * Resolve recipients by type and audience.
     *
     * @param  array<int, int|string>  $ids  user_ids, branch_ids, or delivery_ids depending on type
     */
    public function recipientsByAudience(string $recipientType, string $audience, array $ids = []): \Generator
    {
        $query = User::query()->orderBy('id');

        match ($recipientType) {
            'users' => $query->where('role', 'user'),
            'branches' => $query->where('role', 'employee'),
            'deliveries' => $query->where('role', 'delivery'),
            default => $query->whereRaw('1 = 0'),
        };

        if ($audience === 'selected') {
            if ($recipientType === 'branches') {
                $query->whereIn('branch_id', $ids);
            } elseif ($recipientType === 'deliveries') {
                $userIds = Delivery::query()
                    ->whereIn('id', $ids)
                    ->pluck('user_id')
                    ->filter()
                    ->all();
                $query->whereIn('id', $userIds);
            } else {
                $query->whereIn('id', $ids);
            }
        }

        foreach ($query->cursor() as $user) {
            yield $user;
        }
    }

    /**
     * @deprecated Use recipientsByAudience()
     */
    public function usersByAudience(string $audience, array $userIds = []): \Generator
    {
        return $this->recipientsByAudience('users', $audience, $userIds);
    }
}
