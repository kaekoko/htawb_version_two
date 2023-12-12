<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\helper;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\BettingHistory;
use App\Models\GameProvider;
use App\Models\Game;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FirebaseController;
use App\Models\GameTransferLog;
use App\Models\TransferIn;
use App\Models\TransferOut;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class MemberInfoController extends Controller
{
    public function player_list(Request $request)
    {
         if ($request->ajax()) {
            // $firebase = new FirebaseController();
            // $all_balances = $firebase->getAllValues();
            $query = User::orderBy('created_at', 'DESC')->get();
            $table = DataTables::of($query)
                ->addIndexColumn();
            $table->editColumn('user_code', function ($row) {
                return $row->user_code ? $row->user_code : "-";
            });
            $table->editColumn('balance', function ($row) {
                return $row->balance ? $row->balance : "-";
            });
            $table->editColumn('created_at', function ($row) {
                return date('d-m-Y', strtotime($row->created_at));
            });
            $table->editColumn('name', function ($row) {
                $player_detail = url("game/player_detail?user_code=$row->user_code");
                return "<a href='$player_detail'>$row->name</a>";
            });
            $table->editColumn('game_balance', function ($row) {
                return $row->game_balance ? $row->game_balance : "-";
            });
            // $table->editColumn('game_balance', function ($row) use ($all_balances) {
            //     if (!empty($all_balances[$row->id])) {
            //         return $all_balances[$row->id]['last_balance'] ? $all_balances[$row->id]['last_balance'] : 0.00;
            //     } else {
            //         return "-";
            //     }
            // });
            $table->editColumn('game_transfer_log', function ($row) {
                $url = url("game/transfer_logs/$row->id");
                return "<a href='$url'>Logs</a>";
            });
            $table->rawColumns(['name', 'game_transfer_log']);
            return $table->make(true);
        }
        return view('super_admin.player_list.index');
    }

    public function playerDetail(Request $request)
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
        $username = $request->user_code;
        $player_detail = BettingHistory::orderBy('created_at', 'DESC')
            ->whereBetween('date', [$start, $end])
            ->where('status', 1)
            ->where('username', $username)
            ->get()
            ->groupBy('date');
        $groupWithDate = $player_detail->map(function ($group) {
            $total_winloss = round($group->sum('payout') - $group->sum('bet'), 2);
            return [
                'total_payout' => round($group->sum('payout'), 2),
                'total_turnover' => round($group->sum('turnover'), 2),
                'total_bet' => round($group->sum('bet'), 2),
                'total_winloss' => $total_winloss,
                'total_profitloss' => $total_winloss,
                'total_commission' => round($group->sum('commission'), 2),
                'provider_by_date' => $group->groupBy('p_code')->map(function ($provider) {
                    $total_winloss = round($provider->sum('payout') - $provider->sum('bet'), 2);
                    return [
                        'date' => $provider[0]->date,
                        'total_turnover' => round($provider->sum('turnover'), 2),
                        'total_payout' => round($provider->sum('payout'), 2),
                        'total_bet' => round($provider->sum('bet'), 2),
                        'total_commission' => round($provider->sum('commission'), 2),
                        'total_winloss' => $total_winloss,
                        'total_profitloss' => $total_winloss
                    ];
                })
            ];
        });
        return view("super_admin.player_list.detail")
            ->with([
                "player_detail" => $groupWithDate,
                "user_code" => $username
            ]);
    }

    public function transactionRecord(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->query('date')));
        $provider = $request->query('provider');
        $username = $request->query('username');
        if ($request->ajax()) {
            $query = BettingHistory::where('date', $date)
                ->where('username', $username)
                ->where('p_code', $provider)
                ->where('status', 1)
                ->orderBy('created_at', 'DESC')
                ->get();
            $query->load('game');
            $table = Datatables::of($query)
                ->addIndexColumn();
            $table->editColumn('game_type', function ($row) {
                return $row['game'] ? $row['game']['name'] : "-";
            });
            $table->editColumn('bet_time', function ($row) {
                $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT: 6:30 ( Myanmar Time Zone )
                return date('Y-m-d h:i A', strtotime($row['start_time']) + $diffWithGMT);
            });
            $table->editColumn('commission', function ($row) {
                return round($row['commission'], 2);
            });
            $table->editColumn('win_loss', function ($row) {
                $win_loss = round($row['payout'] - $row['bet'], 2);
                if ($win_loss < 0)
                    return "<span class='badge badge-danger'>$win_loss</span>";
                else
                    return "<span class='badge badge-success'>$win_loss</span>";
            });
            $table->editColumn('profit_loss', function ($row) {
                $profit_loss = round($row['payout'] - $row['bet'], 2);
                if ($profit_loss < 0)
                    return "<span class='badge badge-danger'>$profit_loss</span>";
                else
                    return "<span class='badge badge-success'>$profit_loss</span>";
            });
            $table->rawColumns(['win_loss', 'profit_loss']);
            return $table->make(true);
        }
        return view("super_admin.player_list.transaction_record")->with([
            "date" => $date,
            "provider" => $provider,
            "username" => $username
        ]);
    }

    public function active_member()
    {
        return view('super_admin.active_member.index');
    }

    public function inactive_member()
    {
        return view('super_admin.inactive_member.index');
    }

    public function transfer_log(Request $request, User $user)
    {
        
        if ($request->ajax()) {
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
            $query = GameTransferLog::orderBy('id', 'DESC')
                ->whereBetween('created_at', [$start . " 00:00:00", $end . " 23:59:59"])
                ->where('user_id', $user->id)
                ->get();
                
            $table = DataTables::of($query)->addIndexColumn();
            $table->editColumn('created_at', function ($row) {
                return date('Y-m-d h:i A', strtotime($row->created_at));
            });
            $table->editColumn('type', function ($row) {
                return $row->type == 1 ? "Withdraw" : "Deposit";
            });
            $table->editColumn('error_code', function ($row) {
                return $row->error_code . " : " . helper::game_errorcode()[$row->error_code];
            });
            return $table->make(true);
        }
        return view('super_admin.player_list.game_transfer_log')->with([
            "user" => $user
        ]);
    }
    public function playerDetailExternal(Request $request)
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
        $username = $request->user_code;
        $player_detail = BettingHistory::orderBy('created_at', 'DESC')
            ->whereBetween('date', [$start, $end])
            ->where('status', 1)
            ->where('username', $username)
            ->get()
            ->groupBy('date');
        $groupWithDate = $player_detail->map(function ($group) {
            $total_winloss = round($group->sum('payout') - $group->sum('bet'), 2);
            return [
                'total_payout' => round($group->sum('payout'), 2),
                'total_turnover' => round($group->sum('turnover'), 2),
                'total_bet' => round($group->sum('bet'), 2),
                'total_winloss' => $total_winloss,
                'total_profitloss' => $total_winloss,
                'total_commission' => round($group->sum('commission'), 2),
                'provider_by_date' => $group->groupBy('p_code')->map(function ($provider) {
                    $total_winloss = round($provider->sum('payout') - $provider->sum('bet'), 2);
                    return [
                        'date' => $provider[0]->date,
                        'total_turnover' => round($provider->sum('turnover'), 2),
                        'total_payout' => round($provider->sum('payout'), 2),
                        'total_bet' => round($provider->sum('bet'), 2),
                        'total_commission' => round($provider->sum('commission'), 2),
                        'total_winloss' => $total_winloss,
                        'total_profitloss' => $total_winloss
                    ];
                })
            ];
        });
        return view("super_admin.player_list.detail_external")
            ->with([
                "player_detail" => $groupWithDate,
                "user_code" => $username
            ]);
    }
    
    public function transactionRecordExternal(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->query('date')));
        $provider = $request->query('provider');
        $username = $request->query('username');
        if ($request->ajax()) {
            $query = BettingHistory::where('date', $date)
                ->where('username', $username)
                ->where('p_code', $provider)
                ->where('status', 1)
                ->orderBy('created_at', 'DESC')
                ->get();
            $query->load('game');
            $table = Datatables::of($query)
                ->addIndexColumn();
            $table->editColumn('game_type', function ($row) {
                return $row['game'] ? $row['game']['name'] : "-";
            });
            $table->editColumn('bet_time', function ($row) {
                $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT: 6:30 ( Myanmar Time Zone )
                return date('Y-m-d h:i A', strtotime($row['start_time']) + $diffWithGMT);
            });
            $table->editColumn('commission', function ($row) {
                return round($row['commission'], 2);
            });
            $table->editColumn('win_loss', function ($row) {
                $win_loss = round($row['payout'] - $row['bet'], 2);
                if ($win_loss < 0)
                    return "<span class='badge badge-danger'>$win_loss</span>";
                else
                    return "<span class='badge badge-success'>$win_loss</span>";
            });
            $table->editColumn('profit_loss', function ($row) {
                $profit_loss = round($row['payout'] - $row['bet'], 2);
                if ($profit_loss < 0)
                    return "<span class='badge badge-danger'>$profit_loss</span>";
                else
                    return "<span class='badge badge-success'>$profit_loss</span>";
            });
            $table->rawColumns(['win_loss', 'profit_loss']);
            return $table->make(true);
        }
        return view("super_admin.player_list.transaction_record_external")->with([
            "date" => $date,
            "provider" => $provider,
            "username" => $username
        ]);
    }
}