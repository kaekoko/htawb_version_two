<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ShamelessGameProvider;

class OnlyBetSLipController extends Controller
{
    public function history(Request $request,$code){
        $providers = ShamelessGameProvider::all();
        $provider_slip = [];
        if ($request->has('provider')) {
            $providerCode = $request->provider;
            $startdate = Carbon::parse($request->start_date)->startOfDay();
            $enddate = Carbon::parse($request->end_date)->endOfDay();

            $provider_slip = DB::connection('mysql2')->table($code)->select('provider_name', 'provider_code', DB::raw('SUM(bet_amount) as bet_amount'), DB::raw('SUM(payout_amount) as payout_amount'))
            ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
            ->whereIn('provider_code', $request->provider)
            ->groupBy('provider_name', 'provider_code')
            ->get();
        }
        //return view('super_admin.BetslipTransaction.userTransitionHistory', compact('provider_slip','providers','code','startdate','enddate'));
        return view('super_admin.only_betslip_table.betslip_history', compact('provider_slip','providers','code'));
    }


    public function details(Request $request,$code){
        
            $startdate = Carbon::parse($request->start_date)->startOfDay();
            $enddate = Carbon::parse($request->end_date)->endOfDay();
            $slips =  DB::connection('mysql2')->table($code)->select('provider_code', 'provider_name', 'method_name', 'member_code', 'wager_id', 'bet_amount', 'payout_amount', 'status', 'before_balance', 'balance', 'created_on', 'created_at', 'game')
            ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                    ->orderBy('created_at','desc')
                    ->get();

            return view('super_admin.only_betslip_table.betslip_details', compact('slips','code'));
    }
}
