<?php

namespace app;

use Illuminate\Support\Facades\Validator;
use PyaeSoneAung\MyanmarPhoneValidationRules\Mpt;

class Msg
{

    function __construct()
    {

    }

    public function sendSMS($mobileNumber, $message)
    {
        $isError = 0;
        $errorMessage = true;

        $secondPlace = substr($mobileNumber, 2, 2);

        if ($secondPlace === '40' || $secondPlace === '44' || $secondPlace === '25' || $secondPlace === '26' || $secondPlace === '88' || $secondPlace === '45' || $secondPlace === '50' || $secondPlace === '51' || $secondPlace === '52' || $secondPlace === '89' || $secondPlace === '42' || $secondPlace === '43') {
            $token = "3Uoo_FcJFXi7IbigxJnhUmlRhnrezpm-hkLeH7OxC3KolPTSzghoLdKg4ZFcrsv5";
            $brand_name = "SMSPoh";
        } else {
            $token = "a90A-zmJZt3VnWqzqCB2jrW_7EcV8Th-wBbSHrvKK25xgYBbumXtPB8ocBhz4H9I";
            $brand_name = "SMSPoh";
        }

        $data_msg = [
            "to" => $mobileNumber,
            "message" => $message,
            "sender" => $brand_name
        ];

        $ch = curl_init("https://smspoh.com/api/v2/send");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_msg));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            $isError = true;
            $errorMessage = curl_error($ch);
        }
        curl_close($ch);
        if ($isError) {
            return array('error' => 1, 'message' => $errorMessage);
        } else {
            return array('error' => 0);
        }
    }
}
