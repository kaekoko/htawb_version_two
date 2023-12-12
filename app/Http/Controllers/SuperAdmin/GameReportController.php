<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\BettingHistory;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameProvider;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameReportController extends Controller
{
    public function win_lose(Request $request)
    {
        if ($request->start_date) {
            $start = $request->start_date;
        } else {
            $start = Carbon::now();
            $start = $start->format('Y-m-d');
        }

        if ($request->end_date) {
            $end = $request->end_date;
        } else {
            $end = Carbon::now();
            $end = $end->format('Y-m-d');
        }
        $users = User::all();
        foreach ($users as $user) {
            $usercode[] = $user->user_code;
        }
        $user_bettings = BettingHistory::whereBetween('date', [$start, $end])
            ->where('status', 1)
            ->wherein('username', $usercode)
            ->selectRaw('username')
            ->selectRaw('ROUND(SUM(turnover),2) as total_turnover')
            ->selectRaw('ROUND(SUM(commission),2) as total_commission')
            ->selectRaw('ROUND(SUM(payout),2) as total_payout')
            ->selectRaw('ROUND(SUM(bet),2) as total_bet')
            ->selectRaw('ROUND(SUM(payout) - SUM(bet),2) as total_winloss')
            ->selectRaw('ROUND(SUM(payout) - SUM(bet),2) as total_profitloss')
            ->groupBy('username')
            ->get(); // Betting User

        $user_bettings->load('user');

        $bet_histories = BettingHistory::orderBy('created_at', 'desc')
            ->where('status', 1)
            ->wherein('username', $usercode)
            ->whereBetween('created_at', [$start . " 00:00:00", $end . " 23:59:59"])
            ->get();
        return view('super_admin.game_report.win_lose', compact('bet_histories', 'user_bettings'));
    }

    public function userGameBetSlip(Request $request, User $user)
    {
        $username = $user->user_code;
        $data = [];
        $dates = [];
        if ($request->start_date) {
            $start = $request->start_date;
        } else {
            $start = Carbon::now();
            $start = $start->format('Y-m-d');
        }

        if ($request->end_date) {
            $end = $request->end_date;
        } else {
            $end = Carbon::now();
            $end = $end->format('Y-m-d');
        }
        $bettings = BettingHistory::whereBetween('date', [$start, $end])
            ->where('status', 1)
            ->where('username', $username)
            ->get();
        foreach ($bettings as $betting) {
            array_push($dates, $betting->date);
        }
        $datefilter = array_values(array_unique($dates));
        foreach ($datefilter as $date) {
            $betslip = BettingHistory::where('date', $date)
                ->where('status', 1)
                ->where('username', $username)
                ->selectRaw("provider_name as provider")
                ->selectRaw("p_code as p_code")
                ->selectRaw("ROUND(SUM(turnover),2) as total_turnover")
                ->selectRaw("ROUND(SUM(commission),2) as total_commission")
                ->selectRaw("ROUND(SUM(payout) - SUM(bet),2) as total_winloss")
                ->selectRaw("ROUND(SUM(payout) - SUM(bet),2) as total_profitloss")
                ->selectRaw("username")
                ->selectRaw("date")
                ->groupBy(['provider_name', 'p_code'])
                ->get();
            $total =  BettingHistory::where('date', $date)
                ->where('username', $username)
                ->where('status', 1)
                ->get();

            if ($betslip->count() > 0) {
                $date_winloss = $total->sum('payout') - $total->sum('bet');
                $date_profitloss = $total->sum('payout') - $total->sum('bet');
                $datetotal = [
                    "turnover" => round($total->sum('turnover'), 2),
                    "winloss" => round($date_winloss, 2),
                    "commission" => round($total->sum('commission'), 2),
                    "profitloss" => round($date_profitloss, 2)
                ];
            } else {
                $datetotal = [];
            }
            $datetotal["date"] = $date;
            array_push($data, [
                "data_by_date" => $datetotal,
                "provider_data" => $betslip,
            ]);
        }
        return view('super_admin.game_report.win_lose_bet_slip')->with([
            "bettings" => $data,
            "user" => $user
        ]);
    }

    public function userGameTransactionRecord(Request $request)
    {
        $data = [];
        $date = date('Y-m-d', strtotime($request->query('date')));
        $provider = $request->query('provider');
        $username = $request->query('username');
        $betting = BettingHistory::where('date', $date)
            ->where('username', $username)
            ->where('p_code', $provider)
            ->orderBy('created_at', 'DESC')
            ->where('status', 1)
            ->get();
        $p_name = GameProvider::where('p_code', $provider)->first();
        foreach ($betting as $bet) {
            $gamename = Game::where('provider_id', $p_name->id)->first();
            array_push($data, [
                "username" => $bet->username,
                "bet_time" => date('Y-m-d h:i A', strtotime($bet->start_time)),
                "biz_date" => $bet->match_time,
                "gametype" => $gamename->name,
                "bet" => $bet->bet,
                "turnover" => $bet->turnover,
                "win" => $bet->p_win,
                "winloss" => round($bet->payout - $bet->bet, 2),
                "commission" => round($bet->commission, 2),
                "profitloss" => round($bet->payout - $bet->bet, 2),
                "created_at" => $bet->created_at,
            ]);
        }

        return view("super_admin.game_report.transaction_record")->with([
            "date" => $date,
            "reports" => $data
        ]);
    }

    public function transaction_log()
    {
        return view('super_admin.game_report.transaction_log');
    }
}
