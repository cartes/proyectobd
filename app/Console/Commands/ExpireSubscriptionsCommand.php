<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpireSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire active subscriptions that have reached their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCount = \App\Models\Subscription::where('status', 'active')
            ->where('ends_at', '<=', now())
            ->update(['status' => 'expired']);

        $this->info("Expired {$expiredCount} subscriptions.");
    }
}
