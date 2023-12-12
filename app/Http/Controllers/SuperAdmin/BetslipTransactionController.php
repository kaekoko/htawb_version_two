<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\BetslipTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\ShamelessGameProvider;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

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
        $provider_slip = [];
        if ($request->has('provider')) {
            $providerCode = $request->provider;
            $startdate = Carbon::parse($request->start_date)->startOfDay();
            $enddate = Carbon::parse($request->end_date)->endOfDay();

            $provider_slip = Cache::remember('slips_' . $code . '_' . implode('_', $request->provider), now()->addMinutes(10), function () use ($code, $startdate, $enddate, $request) {
                return BetslipTransaction::select('provider_name', 'provider_code', DB::raw('SUM(bet_amount) as bet_amount'), DB::raw('SUM(payout_amount) as payout_amount'))
                    ->where('member_code', $code)
                    ->whereBetween('created_at', [$startdate, $enddate])
                    ->whereIn('provider_code', $request->provider)
                    ->groupBy('provider_name', 'provider_code')
                    ->get();
            });
        }
        //return view('super_admin.BetslipTransaction.userTransitionHistory', compact('provider_slip','providers','code','startdate','enddate'));
        return view('super_admin.BetslipTransaction.userTransitionHistory', compact('provider_slip','providers','code'));
    }

    public function userTransitionDetail(Request $request, $code)
    {
            $startdate = Carbon::parse($request->start_date)->startOfDay();
            $enddate = Carbon::parse($request->end_date)->endOfDay();
            $slips = Cache::remember('slips_' . $code, now()->addMinutes(10), function () use ($code, $startdate, $enddate) {
                return BetslipTransaction::select('provider_code', 'provider_name', 'method_name', 'member_code', 'wager_id', 'bet_amount', 'payout_amount', 'status', 'before_balance', 'balance', 'created_on', 'created_at', 'game')
                    ->where('member_code', $code)
                    ->whereBetween('created_at', [$startdate, $enddate])
                    ->orderBy('created_at','desc')
                    ->get();
            });

        // dd($slips,$code);
        return view('super_admin.BetslipTransaction.userTransitionDetail', compact('slips','code'));
    }
}
