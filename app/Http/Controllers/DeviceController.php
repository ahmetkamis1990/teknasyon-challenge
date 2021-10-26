<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function register(Request $request)
    {
        $requestData = $request->validate([
            'uid' => 'required',
            'appId' => 'required',
            'language' => 'required',
            'operating_system' => 'required',
        ]);

        $appId = $requestData['appId'];
        $uid = $requestData['uid'];

        $device = Device::where(['uid' => $uid, 'appId' => $appId])->first();

        $responseData = [];
        $responseData['code'] = 200;
        $responseData['message'] = 'Register OK';

        if ($device != null) {
            $responseData['data'] = $device;
        } else {

            try {
                $requestData['client_token'] = Str::random(255);
                $device = Device::create($requestData);
                $responseData['data'] = $device;

            } catch (\Exception $e) {

                $responseData['code'] = 400;
                $responseData['message'] = 'Internal error occured.';
                $responseData['data'] = null;
                Log::error($e);
            }

        }

        return response()->json($responseData);

    }

}
