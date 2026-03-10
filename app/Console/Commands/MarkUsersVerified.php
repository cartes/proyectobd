<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MarkUsersVerified extends Command
{
    protected $signature = 'users:verify-all';

    protected $description = 'Mark all users as email verified';

    public function handle()
    {
        $count = User::whereNull('email_verified_at')->count();

        if ($count === 0) {
            $this->info('All users are already verified.');

            return 0;
        }

        $this->info("Found {$count} unverified users.");

        if ($this->confirm('Do you want to mark them all as verified?')) {
            User::whereNull('email_verified_at')
                ->update(['email_verified_at' => now()]);

            $this->info("✓ Marked {$count} users as verified.");
        }

        return 0;
    }
}
