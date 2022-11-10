<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
	public function sendSuccess($message, $result = NULL)
    {
    	$response = [
            'ResponseCode'      => 200,
            'Status'            => True,
            'Message'           => $message,
        ];

        if(!empty($result)){
            $response['Data'] = $result;
        }
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendFailed($errorMessages = [], $code = 200)
    {
    	$response = [
            'ResponseCode'      => $code,
            'Status'            => False,
        ];


        if(!empty($errorMessages)){
            $response['Message'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function sendOtp($mobile_number, $verification_otp){        
        // echo $mobile_number;die;
        // $opt_url = "https://2factor.in/API/V1/fd9c6a99-19d7-11ec-a13b-0200cd936042/SMS/".$mobile_number."/".$verification_otp."/OTP_TAMPLATE";
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $opt_url);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        // curl_setopt($curl, CURLOPT_PROXYPORT, "80");
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // $result = curl_exec($curl);
        return;
    }
}