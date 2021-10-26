<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {

        $requestData = $request->validate([
            'client_token' => 'required',
            'receipt_id' => 'required'
        ]);

        $clientToken = $requestData['client_token'];
        $receiptId = $requestData['receipt_id'];

        $responseData = [];

        $subscription = Subscription::where(['client_token' => $clientToken, 'receipt_id' => $receiptId])->first();

        if ($subscription == null) {

            $device = Device::where('client_token', $clientToken)->first();

            if ($device != null) {

                $endPoint = '';

                if ($device->operating_system == 'Google') {
                    $endPoint = 'google-api';
                } elseif ($device->operating_system == 'IOS') {
                    $endPoint = 'ios-api';
                } else {

                    $responseData['code'] = 400;
                    $responseData['message'] = 'Invalid OS';
                }

                if ($endPoint != null) {

                    $url = 'http://127.0.0.1:8000/api/' . $endPoint;

                    $req = Request::create($url, 'POST', [
                        'receipt_id' => $receiptId
                    ]);
                    $res = app()->handle($req)->getContent();

                    $resJson = json_decode($res, true);

                    try {
                        $data['status'] = $resJson['status'];
                        $data['client_token'] = $clientToken;
                        $data['receipt_id'] = $receiptId;
                        $data['expire_date'] = Carbon::parse($resJson['expire_date']);;
                        $subscription = Subscription::create($data);


                        if ($resJson['status']) {

                            $responseData['code'] = 200;
                            $responseData['message'] = 'Subscription OK';
                            $responseData['data'] = $subscription;

                        } else {

                            $responseData['code'] = 400;
                            $responseData['message'] = 'Invalid/Expired receipt_id';
                            $responseData['data'] = null;

                        }
                    } catch (\Exception $err) {

                        $responseData['code'] = 400;
                        $responseData['message'] = 'Internal error occured.';
                        $responseData['data'] = null;
                        Log::error($err);
                    }
                }


            } else {
                $responseData['code'] = 400;
                $responseData['message'] = 'Invalid Device';
            }
        } else {

            $responseData['code'] = 400;
            $responseData['expire_date'] = $subscription->expire_date;
            $responseData['message'] = 'Subscription already exists';
            $responseData['data'] = $subscription;

        }

        return response()->json($responseData);

    }

    public function listSubscriptions(Request $request)
    {

        $requestData = $request->validate([
            'client_token' => 'required',
            'receipt_id' => 'required'
        ]);

        $clientToken = $requestData['client_token'];
        $activeSubscriptions = [];

        try {
            $activeSubscriptions = Subscription::where(['client_token' => $clientToken, 'status' => true])->orderBy('expire_date', 'asc')->get();

        } catch (\Exception $err) {
            $responseData['code'] = 400;
            $responseData['message'] = 'Internal error occured.';
            Log::error($err);
        }

        $responseData['data'] = $activeSubscriptions;
        return response()->json($responseData);

    }
}
