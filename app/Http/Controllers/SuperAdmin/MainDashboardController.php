<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\CashIn;
use App\Models\CashOut;
use App\Models\UserBet;
use App\Models\UserBet1d;
use App\Models\UserBet3d;
use Illuminate\Http\Request;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MainDashboardController extends Controller
{
    public function index(Request $request){

        $code = User::get();

        if ($request->start_date) {
            $start = $request->start_date;
        } else {
            $start = Carbon::now();
        }

        if ($request->end_date) {
            $end = Carbon::parse($request->end_date)->addDay();
            $ended = $request->end_date;
        } else {
            $end = Carbon::now()->addDay();
            $ended = Carbon::now();
        }

        $total_cashin = CashIn::select(DB::raw('SUM(amount) as amount'))
        ->whereBetween('created_at', [$start, $end])
        ->where('status','Approve')
        ->first();

        $total_cashout = CashOut::select(DB::raw('SUM(amount) as amount'))
        ->whereBetween('created_at', [$start, $end])
        ->where('status','Approve')
        ->first();

        $different_cashin_out = $total_cashin->amount - $total_cashout->amount;

        $one = UserBet1d::select(DB::raw('SUM(total_amount) as one_bet_amount'), DB::raw('SUM(reward_amount) as one_payout_amount'))
        ->whereBetween('date', [$start, $ended])
        ->first();

        $one_result =  $one->one_bet_amount - $one->one_payout_amount;

        $two = UserBet::select(DB::raw('SUM(total_amount) as two_bet_amount'), DB::raw('SUM(reward_amount) as two_payout_amount'))
        ->whereBetween('date', [$start, $ended])
        ->first();

        $two_result =  $two->two_bet_amount - $two->two_payout_amount;



        $cryptoOne = UserBetCrypto1d::select(DB::raw('SUM(total_amount) as cryptoOne_bet_amount'), DB::raw('SUM(reward_amount) as cryptoOne_payout_amount'))
        ->whereBetween('date', [$start, $ended])
        ->first();

        $cryptoOne_result =  $cryptoOne->cryptoOne_bet_amount - $cryptoOne->cryptoOne_payout_amount;

        $cryptoTwo = UserBetCrypto2d::select(DB::raw('SUM(total_amount) as cryptoTwo_bet_amount'), DB::raw('SUM(reward_amount) as cryptoTwo_payout_amount'))
        ->whereBetween('date', [$start, $ended])
        ->first();

        $cryptoTwo_result =  $cryptoTwo->cryptoTwo_bet_amount - $cryptoTwo->cryptoTwo_payout_amount;

        $three = UserBet3d::select(DB::raw('SUM(total_amount) as three_bet_amount'), DB::raw('SUM(reward_amount) as three_payout_amount'))
        ->whereBetween('date', [$start, $ended])
        ->first();

        $three_result = $three->three_bet_amount - $three->three_payout_amount;


       

       
        return view('super_admin.main.index',compact('cryptoTwo','cryptoTwo_result','cryptoOne','cryptoOne_result','total_cashin','total_cashout','different_cashin_out','two','two_result','three','three_result','one','one_result'));
    }
}
