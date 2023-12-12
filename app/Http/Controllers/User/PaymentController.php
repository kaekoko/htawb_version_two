<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\CashIn;
use App\Models\CashOut;
use App\Models\UserBet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function cash_in_history($id)
    {
        $cash_ins = CashIn::where('user_id',$id)->get();
        return view('user.payment.cash_in_history',compact('cash_ins'));
    }

    public function cash_out_history($id)
    {
        $cash_outs = CashOut::where('user_id',$id)->get();
        return view('user.payment.cash_out_history',compact('cash_outs'));
    }
}
