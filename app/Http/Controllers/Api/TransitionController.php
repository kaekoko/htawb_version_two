<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BetslipTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransitionController extends Controller
{
    public function betslip(Request $request)
    {
        $code = $request->user()->user_code;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $slips = [];

        $slips = DB::connection('mysql2')->table($code)->select('bet_amount','payout_amount')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select('provider_name', 'provider_code', DB::raw('SUM(bet_amount) as bet_amount'), DB::raw('SUM(payout_amount) as payout_amount'))
            ->groupBy('provider_name', 'provider_code',)
            ->get();


        $amount = 0;
        $total_bet = 0;
        $result = 0;
        foreach ($slips as $slip) {
            $amount += $slip->bet_amount;
            $total_bet += $slip->payout_amount;
        };
        $result = $total_bet - $amount;

        return response()->json(['data' => $slips, 'message' => 'success', 'bet_amount_total' => $amount, 'payout_total' => $total_bet, 'win_lose' => $result]);
    }


    public function details(Request $request)
    {

        $code = $request->user()->user_code;
        $p_code = $request->input('p_code');

        $slips = DB::connection('mysql2')->table($code)->select('provider_code', 'provider_name', 'method_name', 'member_code', 'wager_id', 'bet_amount', 'payout_amount', 'status', 'before_balance', 'balance', 'created_on', 'created_at', 'game')
            ->where('provider_code', $p_code)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(['data' => $slips]);
    }
}
