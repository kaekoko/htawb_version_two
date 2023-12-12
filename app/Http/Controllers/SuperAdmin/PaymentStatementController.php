<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Sma;
use Illuminate\Http\Request;
use App\Models\LotteryOffDay;
use App\Http\Controllers\Controller;

class PaymentStatementController extends Controller
{
    public function index()
    {
        $diffWithGMT=6*60*60+30*60;
        $date = gmdate('Y-m-d', time()+$diffWithGMT);
        $day = gmdate('D', time()+$diffWithGMT);
        $curtime = gmdate('H:i', time()+$diffWithGMT);
        $close = 0;
        // $lottery_off = LotteryOffDay::where('off_day', $date)->first();
        // if($day === 'Sat' || $day === 'Sun'){
        //     $close = 1;
        // } else {
        //     if($lottery_off === NULL){
        //         if($curtime != '20:00'){
        //             $close = 1;
        //         }
        //     }  else {
        //         $close = 1;
        //     }
        // }

        return view('super_admin.payment_statement.index', compact('close'));
    }

    public function smaLists(Request $request, $req){
        $diffWithGMT=6*60*60+30*60;
        $date = gmdate('Y-m-d', time()+$diffWithGMT);
        if($req === "daily"){
            return Sma::sma_record_daily($request->date, $request->column,$request->sma_id,$request->who);
        }
        return Sma::sma_record_section($request->time,$request->date, $request->column,$request->sma_id,$request->who, $request->id);
    }

}
