<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IOSApiController extends Controller
{
    public function check(Request $request)
    {

        $requestData = $request->validate([
            'receipt_id' => 'required'
        ]);

        $receiptId = $requestData['receipt_id'];
        $lastCharacter=substr($receiptId,-1);
        $responseData=[];

        if (is_numeric($lastCharacter)&&intval($lastCharacter)%2==1){
            $responseData['status']=true;
            $responseData['expire_date']=Carbon::now()->addDays(1);
        }else{

            $responseData['status']=false;
            $responseData['expire_date']=null;
        }

        return response()->json($responseData);

    }

}
