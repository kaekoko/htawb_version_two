<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BetslipTransaction;
use App\Models\ShamelessGameProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BetslipTransactionController extends Controller
{
    public function index()
    {
        $transactions = BetslipTransaction::orderBy('id', 'desc')->paginate(30);
        return view('super_admin.BetslipTransaction.index', compact('transactions'));
    }

    public function userTransition(Request $request, $code)
    {
        $providers = ShamelessGameProvider::all();
        $slips = [];
        $provider_slip = [];


        if ($request->has('provider')) {
            $providerCode = $request->provider;
            $startdate = Carbon::parse($request->start_date)->startOfDay();
            $enddate = Carbon::parse($request->end_date)->endOfDay();

            $provider_slip = BetslipTransaction::select('provider_code','provider_name','method_name','member_code','wager_id','bet_amount','payout_amount','status','before_balance','balance','created_on','created_at','game')->where('member_code', $code)
                ->orderBy('created_at', 'desc')
                // ->where('method_name', '!=', 'buyin')
                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                ->select('provider_name', 'provider_code', DB::raw('SUM(bet_amount) as bet_amount'), DB::raw('SUM(payout_amount) as payout_amount'))
                ->groupBy('provider_name', 'provider_code',)
                ->get();
            $provider_slip = $provider_slip->whereIn('provider_code', $request->provider);
        }

        if ($request->has('provider')) {

            $startdate = Carbon::parse($request->start_date)->startOfDay();
            $enddate = Carbon::parse($request->end_date)->endOfDay();

            $slips = BetslipTransaction::select('provider_code','provider_name','method_name','member_code','wager_id','bet_amount','payout_amount','status','before_balance','balance','created_on','created_at','game')->where('member_code', $code)
                ->orderBy('created_at', 'desc')

                ->whereDate('created_at', '>=', $startdate)
                ->whereDate('created_at', '<=', $enddate)
                ->get();

            $slips = $slips->whereIn('provider_code', $request->provider);
        }

        // dd($slips);

        $amount = 0;
        $total_bet = 0;
        $result = 0;
        foreach ($slips as $slip) {
            $amount += $slip->bet_amount;
            $total_bet += $slip->payout_amount;
        };
        $result = $total_bet - $amount;

        return view('super_admin.BetslipTransaction.userTransitionHistory', compact('code', 'providers', 'slips', 'provider_slip', 'amount', 'total_bet', 'result'));
    }

    public function userTransitionDetail($code, $p_code)
    {
        $slips = BetslipTransaction::select('provider_code','provider_name','method_name','member_code','wager_id','bet_amount','payout_amount','status','before_balance','balance','created_on','created_at','game')->where('member_code', $code)
            ->where('provider_code', $p_code)
            ->orderBy('created_at', 'desc')
            ->where('status', '!=', '',)
            ->get();

        // dd($slips,$code);
        return view('super_admin.BetslipTransaction.userTransitionDetail', compact('code', 'slips'));
    }
}
