<?php

namespace App\Console\Commands;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignDeliveryRoles extends Command
{
    protected $signature = 'deliveries:assign-roles';

    protected $description = 'Assign the Spatie delivery role to delivery users missing it';

    public function handle(): int
    {
        Role::findOrCreate('delivery');

        $userIds = Delivery::query()
            ->pluck('user_id')
            ->merge(User::query()->where('role', 'delivery')->pluck('id'))
            ->unique()
            ->filter();

        $assigned = 0;
        $skipped = 0;

        User::whereIn('id', $userIds)->each(function (User $user) use (&$assigned, &$skipped) {
            if ($user->role !== 'delivery') {
                $user->update(['role' => 'delivery']);
            }

            if ($user->hasRole('delivery')) {
                $skipped++;
                return;
            }

            $user->assignRole('delivery');
            $this->line("Assigned delivery role to user #{$user->id} ({$user->name})");
            $assigned++;
        });

        $this->info("Done. Assigned: {$assigned}, already had role: {$skipped}.");

        return self::SUCCESS;
    }
}
