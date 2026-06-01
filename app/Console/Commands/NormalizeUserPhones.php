<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class NormalizeUserPhones extends Command
{
    protected $signature = 'users:normalize-phones';

    protected $description = 'Normalize all user phone numbers to local format (07XXXXXXXXX)';

    public function handle(): int
    {
        $updated = 0;

        User::whereNotNull('phone')->each(function (User $user) use (&$updated) {
            $normalized = normalize_phone($user->phone);

            if ($normalized && $user->phone !== $normalized) {
                $this->line("{$user->id}: {$user->phone} → {$normalized}");
                $user->update(['phone' => $normalized]);
                $updated++;
            }
        });

        $this->info("Done. Updated {$updated} user(s).");

        return self::SUCCESS;
    }
}
