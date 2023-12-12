<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserBet;
use App\Models\UserBet1d;
use App\Models\UserBet3d;
use Illuminate\Http\Request;
use App\Models\OverAllSetting;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use App\Models\BetslipTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TwoBetOnlyController extends Controller
{
    public function one_history(Request $request, $id)
    {
        $startdate = Carbon::parse($request->start_date)->startOfDay();
        $enddate = Carbon::parse($request->end_date)->endOfDay();

        $user_id = $id;
        $bet_history_1d = UserBet1d::with('bettings')->orderBy('id', 'DESC')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('user_id', $user_id)->get();

        $amount = 0;
        $total_bet = 0;
        $result = 0;
        foreach ($bet_history_1d as $slip) {
            $amount += $slip->total_amount;
            $total_bet += $slip->reward_amount;
        };
        $result = $total_bet - $amount;

        return view('super_admin.only_betslip.1d_only_betslip', compact('bet_history_1d', 'user_id', 'amount', 'total_bet', 'result'));
    }

    public function two_history(Request $request, $id)
    {
        $startdate = Carbon::parse($request->start_date)->startOfDay();
        $enddate = Carbon::parse($request->end_date)->endOfDay();

        $user_id = $id;
        $bet_history_2d = UserBet::with('bettings')->orderBy('id', 'DESC')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('user_id', $user_id)->get();

        $amount = 0;
        $total_bet = 0;
        $result = 0;
        foreach ($bet_history_2d as $slip) {
            $amount += $slip->total_amount;
            $total_bet += $slip->reward_amount;
        };
        $result = $total_bet - $amount;


        return view('super_admin.only_betslip.2d_only_betslip', compact('bet_history_2d', 'user_id', 'amount', 'total_bet', 'result'));
    }

    public function crypto_one_history(Request $request, $id)
    {
        $startdate = Carbon::parse($request->start_date)->startOfDay();
        $enddate = Carbon::parse($request->end_date)->endOfDay();

        $user_id = $id;
        $bet_history_crypto2d = UserBetCrypto1d::with('bettings')->orderBy('id', 'DESC')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('user_id', $user_id)->get();

        $amount = 0;
        $total_bet = 0;
        $result = 0;
        foreach ($bet_history_crypto2d as $slip) {
            $amount += $slip->total_amount;
            $total_bet += $slip->reward_amount;
        };
        $result = $total_bet - $amount;


        return view('super_admin.only_betslip.crypto_1d_only_betslip', compact('bet_history_crypto2d', 'user_id', 'amount', 'total_bet', 'result'));
    }


    public function crypto_two_history(Request $request, $id)
    {
        $startdate = Carbon::parse($request->start_date)->startOfDay();
        $enddate = Carbon::parse($request->end_date)->endOfDay();

        $user_id = $id;
        $bet_history_crypto2d = UserBetCrypto2d::with('bettings_c2d')->orderBy('id', 'DESC')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('user_id', $user_id)->get();

        $amount = 0;
        $total_bet = 0;
        $result = 0;
        foreach ($bet_history_crypto2d as $slip) {
            $amount += $slip->total_amount;
            $total_bet += $slip->reward_amount;
        };
        $result = $total_bet - $amount;


        return view('super_admin.only_betslip.crypto_2d_only_betslip', compact('bet_history_crypto2d', 'user_id', 'amount', 'total_bet', 'result'));
    }

    public function three_history(Request $request, $id)
    {

        $startdate = Carbon::parse($request->start_date)->startOfDay();
        $enddate = Carbon::parse($request->end_date)->endOfDay();

        $user_id = $id;
        $bet_history_3d = UserBet3d::with('bettings_3d')->orderBy('id', 'DESC')
            ->whereDate('created_at', '>=', $startdate)
            ->whereDate('created_at', '<=', $enddate)
            ->where('user_id', $user_id)->get();

        $tot = OverAllSetting::first('tot_3d');
        $tot = $tot->tot_3d;


        $amount = 0;
        $total_bet = 0;
        $result = 0;
        foreach ($bet_history_3d as $slip) {
            $amount += $slip->total_amount;
            $total_bet += $slip->reward_amount;
        };
        $result = $total_bet - $amount;

        return view('super_admin.only_betslip.3d_only_betslip', compact('bet_history_3d', 'tot', 'user_id', 'amount', 'total_bet', 'result'));
    }

    public function allReport(Request $request, $id)
    {
        $startdate = Carbon::parse($request->start_date)->startOfDay();
        $enddate = Carbon::parse($request->end_date)->endOfDay();

        $user_id = $id;

        $bet_history_1d =  Cache::remember('bet_history_1d_' . $user_id, now()->addMinutes(10), function () use ($user_id, $startdate, $enddate) {
        return  UserBet1d::select('total_amount','reward_amount')->whereDate('created_at', '>=', $startdate)
                    ->whereDate('created_at', '<=', $enddate)
                    ->where('user_id', $user_id)->get();
        });

        $bet_history_2d =  Cache::remember('bet_history_2d_' . $user_id, now()->addMinutes(10), function () use ($user_id, $startdate, $enddate) {
        return  UserBet::select('total_amount','reward_amount')->whereDate('created_at', '>=', $startdate)
                    ->whereDate('created_at', '<=', $enddate)
                    ->where('user_id', $user_id)->get();
        });

        $bet_history_crypto_1d =  Cache::remember('bet_history_crypto_1d_' . $user_id, now()->addMinutes(10), function () use ($user_id, $startdate, $enddate) {
        return  UserBetCrypto1d::select('total_amount','reward_amount')->whereDate('created_at', '>=', $startdate)
                    ->whereDate('created_at', '<=', $enddate)
                    ->where('user_id', $user_id)->get();
        });

        $bet_history_crypto_2d =  Cache::remember('bet_history_crypto_2d_' . $user_id, now()->addMinutes(10), function () use ($user_id, $startdate, $enddate) {
        return  UserBetCrypto2d::select('total_amount','reward_amount')->whereDate('created_at', '>=', $startdate)
                    ->whereDate('created_at', '<=', $enddate)
                    ->where('user_id', $user_id)->get();
        });

        $bet_history_3d =  Cache::remember('bet_history_3d_' . $user_id, now()->addMinutes(10), function () use ($user_id, $startdate, $enddate) {
            return  UserBet3d::select('total_amount','reward_amount')->whereDate('created_at', '>=', $startdate)
                        ->whereDate('created_at', '<=', $enddate)
                        ->where('user_id', $user_id)->get();
            });

        $code = User::where('id', $user_id)->first()->user_code;

        $game_slip = Cache::remember('slips_' . $code, now()->addMinutes(10), function () use ($code, $startdate, $enddate) {
            return BetslipTransaction::select('bet_amount', 'payout_amount')
                ->where('member_code', $code)
                ->whereBetween('created_at', [$startdate, $enddate])
                ->get();
        });

        //1d
        $oneAmount = 0;
        $oneTotal_bet = 0;
        $oneResult = 0;
        foreach ($bet_history_1d as $slip) {
            $oneAmount += $slip->total_amount;
            $oneTotal_bet += $slip->reward_amount;
        };
        $oneResult = $oneTotal_bet - $oneAmount;

        //2d
        $twoAmount = 0;
        $twoTotal_bet = 0;
        $twoResult = 0;
        foreach ($bet_history_2d as $slip) {
            $twoAmount += $slip->total_amount;
            $twoTotal_bet += $slip->reward_amount;
        };
        $twoResult = $twoTotal_bet - $twoAmount;

        //c1d
        $cryptoOneAmount = 0;
        $cryptoOneTotal_bet = 0;
        $cryptoOneResult = 0;
        foreach ($bet_history_crypto_1d as $slip) {
            $cryptoOneAmount += $slip->total_amount;
            $cryptoOneTotal_bet += $slip->reward_amount;
        };
        $cryptoOneResult = $cryptoOneTotal_bet - $cryptoOneAmount;

        //c2d
        $cryptotwoAmount = 0;
        $cryptotwoTotal_bet = 0;
        $cryptotwoResult = 0;
        foreach ($bet_history_crypto_2d as $slip) {
            $cryptotwoAmount += $slip->total_amount;
            $cryptotwoTotal_bet += $slip->reward_amount;
        };
        $cryptotwoResult = $cryptotwoTotal_bet - $cryptotwoAmount;

        //3d
        $threeAmount = 0;
        $threeTotal_bet = 0;
        $threeResult = 0;
        foreach ($bet_history_3d as $slip_three) {
            $threeAmount += $slip_three->total_amount;
            $threeTotal_bet += $slip_three->reward_amount;
        };
        $threeResult = $threeTotal_bet - $threeAmount;


        $game_amount = 0;
        $game_total_bet = 0;
        $game_result = 0;
        foreach ($game_slip as $slip_g) {
            $game_amount += $slip_g->bet_amount;
            $game_total_bet += $slip_g->payout_amount;
        };
        $game_result = $game_total_bet - $game_amount;

        return view('super_admin.task.task', compact(
            'user_id',
            'oneAmount',
            'oneTotal_bet',
            'oneResult',

            'twoAmount',
            'twoTotal_bet',
            'twoResult',

            'cryptotwoAmount',
            'cryptotwoTotal_bet',
            'cryptotwoResult',

            'cryptoOneAmount',
            'cryptoOneTotal_bet',
            'cryptoOneResult',

            'threeAmount',
            'threeTotal_bet',
            'threeResult',

            'game_amount',
            'game_total_bet',
            'game_result'
        ));
    }
}
