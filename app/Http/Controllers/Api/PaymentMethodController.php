<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function showCashIn()
    {
        $pay = PaymentMethod::where('cash_in_status', 0)->where('type', 'pay')->with('payment_account_numbers')->get(['id', 'name', 'logo']);
        $banking = PaymentMethod::where('cash_in_status', 0)->where('type', 'banking')->with('payment_account_numbers')->get(['id', 'name', 'logo']);
        $mobile_topup = PaymentMethod::where('cash_in_status', 0)->where('type', 'mobile-topup')->get(['id', 'name', 'logo', 'percentage']);
        $thai_banking = PaymentMethod::where('cash_in_status', 0)->where('type', 'foreign-banking')->with('payment_account_numbers')->get(['id', 'name', 'logo', 'exchange_rate']);

        return response()->json([
            'pay' => $pay,
            'banking' => $banking,
            'mobile_topup' => $mobile_topup,
            'thai_banking' => $thai_banking
        ], 200);
    }

    

    public function showCashInNew()
    {
        $pay = PaymentMethod::where('cash_in_status', 0)->where('type', 'pay')->with('new_payment_account_numbers')->get(['id', 'name', 'logo']);
        $banking = PaymentMethod::where('cash_in_status', 0)->where('type', 'banking')->with('new_payment_account_numbers')->get(['id', 'name', 'logo']);
        $mobile_topup = PaymentMethod::where('cash_in_status', 0)->where('type', 'mobile-topup')->get(['id', 'name', 'logo', 'percentage']);
        $thai_banking = PaymentMethod::where('cash_in_status', 0)->where('type', 'foreign-banking')->with('new_payment_account_numbers')->get(['id', 'name', 'logo', 'exchange_rate']);

        return response()->json([
            'pay' => $pay,
            'banking' => $banking,
            'mobile_topup' => $mobile_topup,
            'thai_banking' => $thai_banking
        ], 200);
    }

    public function showCashOut()
    {
        $pay = PaymentMethod::where('cash_out_status', 0)->where('type', 'pay')->with('payment_account_numbers')->get(['id', 'name', 'logo']);
        $banking = PaymentMethod::where('cash_out_status', 0)->where('type', 'banking')->with('payment_account_numbers')->get(['id', 'name', 'logo']);
        $thai_banking = PaymentMethod::where('cash_out_status', 0)->where('type', 'foreign-banking')->with('payment_account_numbers')->get(['id', 'name', 'logo', 'exchange_rate']);

        return response()->json([
            'pay' => $pay,
            'banking' => $banking,
            'thai_banking' => $thai_banking
        ], 200);
    }
}
