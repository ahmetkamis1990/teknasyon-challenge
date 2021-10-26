<?php

namespace App\Console\Commands;

use App\Jobs\BwinFetchEventData;
use App\Jobs\CheckSubscription;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SubscriptionWorker extends Command
{
    protected $signature = 'check-subscriptions';

    protected $description = 'Check subscriptions expire date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $subscriptions = Subscription::where('status', '=', 1)
            ->where('status', true)
            ->where('expire_date', '<', Carbon::now())
            ->join('devices', 'devices.client_token', '=', 'subscriptions.client_token')
            ->select('subscriptions.receipt_id', 'subscriptions.subscription_id', 'devices.operating_system')
            ->get()->toArray();

        echo "subscriptions count : ".count($subscriptions) . "\n";

        foreach ($subscriptions as $subscription) {

            dispatch((new CheckSubscription($subscription))->onQueue('check-expire-date-queue'));

        }


    }
}
