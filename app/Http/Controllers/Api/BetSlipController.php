<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\UserBet;
use App\Models\UserBet1d;
use App\Models\UserBet3d;
use Illuminate\Http\Request;
use App\Models\RefundHistory;
use App\Models\OverAllSetting;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BetSlipController extends Controller
{
    public function bet_slip_history_2d(Request $request)
    {
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now();
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
        }

        $user_id = Auth::user()->id;
        $bet_history_2d = UserBet::with('bettings')->orderBy('id','DESC')->whereBetween('date', [$start, $end])->where('user_id',$user_id)->get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $bet_history_2d,
        ]);
    }

    public function bet_slip_history_1d(Request $request)
    {
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now();
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
        }

        $user_id = Auth::user()->id;
        $bet_history_2d = UserBet1d::with('bettings')->orderBy('id','DESC')->whereBetween('date', [$start, $end])->where('user_id',$user_id)->get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $bet_history_2d,
        ]);
    }

    public function bet_slip_history_c2d(Request $request)
    {
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now();
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
        }

        $user_id = Auth::user()->id;
        $bet_history_c2d = UserBetCrypto2d::with('bettings_c2d')->orderBy('id','DESC')->whereBetween('date', [$start, $end])->where('user_id',$user_id)->get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $bet_history_c2d,
        ]);
    }

    

    public function bet_slip_history_c1d(Request $request)
    {
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now();
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
        }

        $user_id = Auth::user()->id;
        $bet_history_c2d = UserBetCrypto1d::with('bettings')->orderBy('id','DESC')->whereBetween('date', [$start, $end])->where('user_id',$user_id)->get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $bet_history_c2d,
        ]);
    }

    public function bet_slip_history_3d(Request $request)
    {
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now();
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
        }

        $user_id = Auth::user()->id;
        $bet_history_3d = UserBet3d::with('bettings_3d')->orderBy('id','DESC')->whereBetween('date', [$start, $end])->where('user_id',$user_id)->get();

        $tot = OverAllSetting::first('tot_3d');

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'tot' => $tot->tot_3d,
            'data' => $bet_history_3d,
        ]);
    }

    public function refund_history()
    {
        $user_id = Auth::user()->id;
        $refund_history = RefundHistory::where('user_id',$user_id)->get();
        return response()->json([
            'result' => count($refund_history),
            'status' => 200,
            'message' => 'success',
            'data' => $refund_history
        ]);
    }
}
