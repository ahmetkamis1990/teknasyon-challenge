<?php

namespace App\Jobs;

use App\Models\Purchase;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class CheckSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscription;

    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle()
    {

        $endPoint = '';
        echo "test2:" . $this->subscription['operating_system'] . "\n";
        if ($this->subscription['operating_system'] == 'Google') {
            $endPoint = 'google-api';
        } elseif ($this->subscription['operating_system'] == 'IOS') {
            $endPoint = 'ios-api';
        } else {
            Log::error("invalid operating system : " . $this->subscription['operating_system']);
            return false;
        }

        echo $this->subscription['receipt_id'] . "\n";

        $url = 'http://127.0.0.1:8000/api/' . $endPoint;

        $req = Request::create($url, 'POST', [
            'receipt_id' => $this->subscription['receipt_id']
        ]);
        $res = app()->handle($req)->getContent();

        $resJson = json_decode($res, true);


        if (!$resJson['status']) {
            Subscription::where('subscription_id', '=', $this->subscription['subscription_id'])->update(['status' => false]);
        }

    }
}
