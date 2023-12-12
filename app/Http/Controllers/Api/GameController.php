<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Models\User;
use App\Models\GameCategory;
use App\Models\GameProvider;
use Illuminate\Http\Request;
use App\Models\BettingHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\FirebaseController;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function gameCategories(Request $request)
    {
        $res = array();
        $categories = DB::table('game_categories')->select('id', 'name', 'p_type', 'img')->get();
        foreach ($categories as $cat) {
            $game = DB::table('games')->select('id', 'name', 'img', 'g_code')->where('category_id', $cat->id)->first();
            if (!empty($game)) {
                array_push($res, $cat);
            }
        }
        return response()->json([
            "message" => "success",
            "categories" => $res
        ]);
    }

    public function gameProviders($p_type)
    {
        $res = [];
        $providerids = [];
        $get_category = GameCategory::where('p_type', $p_type)->first();
        if (!empty($get_category)) {
            $games = Game::where('category_id', $get_category->id)
                ->where('provider_id', '!=', 4) // Close Spade Gaming
                ->where('provider_id', '!=', 6) // Close Red Tiger
                ->get();
            if ($games->count() > 0) {
                foreach ($games as $key => $game) {
                    $providerids[] = $game->provider_id;
                    // $providers = GameProvider::where('id', $game->provider_id)->first();

                }

            }
        }
        $pids=array_values(array_unique($providerids));
        $ids=[3,17,1,2,4,5,6,7,8,9,10,11,12,13,14,15,16];
        $providers = GameProvider::wherein('id',  $pids)->orderByRaw('FIELD(id, '.implode(',', $ids).')')->get();;
        return $providers;
    }



    public function getGameByPro(Request $request)
    {
        $p_type = $request->query('p_type');
        $id = $request->query('id');
        $category = DB::table('game_categories')->where('p_type', $p_type)->first();
        if ($id === 'all') {
            $game_lists = [];
            $data = DB::table('games')->select('id', 'name', 'img', 'g_code', 'active', 'provider_id')->where('category_id', $category->id)->where('active', 1)->get();
            foreach ($data as $value) {
                $res = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'img' => filter_var($value->img, FILTER_VALIDATE_URL) ? $value->img : "/storage/games/" . $value->img,
                    'g_code' => $value->g_code,
                    'active' => $value->active,
                    'provider_id' => $value->provider_id
                ];
                $game_lists[] = $res;
            }
        } else {
            $game_lists = [];
            $data = DB::table('games')->select('id', 'name', 'img', 'g_code', 'active', 'provider_id')->where('category_id', $category->id)->where('provider_id', $id)->get();
            foreach ($data as $value) {
                $res = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'img' => filter_var($value->img, FILTER_VALIDATE_URL) ? $value->img : "/storage/games/" . $value->img,
                    'g_code' => $value->g_code,
                    'active' => $value->active,
                    'provider_id' => $value->provider_id
                ];
                $game_lists[] = $res;
            }
        }

        return response()->json($game_lists);
    }

    public function game_ui(Request $request, $name)
    {
        $id = $request->query('category_id');
        return games($name, $id);
    }

    public function search()
    {
        $games = Game::select('id', 'name', 'img', 'g_code')->where('active', 1)->get();
        return response()->json(json_encode($games));
    }

    public function providerMinimumAmount()
    {
        $provider_amount = DB::table('provider_minimum_amounts')->get(['provider_id', 'minimum_amount']);
        return response()->json($provider_amount, 200);
    }


    public function game_view(Request $request, $id)
    {
        if (gameMaintainance() == 1) {
            return response()->json([
                'message' => 'Game Sever Maintaince please wait 30 Minutes'
            ], 400);
        }
        $game_id = $request->query('game_id');
        $req = $request->query('req');
        $menus = [];
        if ($game_id != null) {
            $user = DB::table('users')->where('id', $id)->first();
            //return $user;
            if (!empty($user)) {
                $game = DB::table('games')->where('g_code', $game_id)->first();
                $provider = DB::table('game_providers')->where('id', $game->provider_id)->pluck('p_code')->first();
                $category = DB::table('game_categories')->where('id', $game->category_id)->pluck('p_type')->first();
                $g_code = $game->g_code;
                $md5 = md5('icmk' . 'alexaung57' . $provider . $category . $user->user_code . 'ce6f73f30d79fda780c9d2a82997b09c');
                $sig = strtoupper($md5);
                $url = 'http://gsmd.336699bet.com/launchGames.aspx';
                if ($req == 'web') {
                    $parameter = '?operatorcode=icmk&providercode=' . $provider . '&username=' . $user->user_code . '&password=' . 'alexaung57' . '&type=' . $category . '&gameid=' . $g_code . '&lang=en-US&html5=0&signature=' . $sig;
                } else {
                    $parameter = '?operatorcode=icmk&providercode=' . $provider . '&username=' . $user->user_code . '&password=' . 'alexaung57' . '&type=' . $category . '&gameid=' . $g_code . '&lang=en-US&html5=1&signature=' . $sig;
                }

                $re_url = $url . $parameter;
                $url_res = Http::get($re_url);
              
                $result = $url_res->json();

                if ($result['errCode'] == 0) {
                    if (strpos($result['gameUrl'], 'https') !== false) {
                        $res = $result['gameUrl'];
                    } else {
                        $res = str_replace('http', 'https', $result['gameUrl']);
                    }
                    $basedata = new FirebaseController();
                    $user_balance = $basedata->getValue($user->id);
                    $last_provider = $user_balance['last_provider'];
                    $withdraw_referenceid = date("YmdHis") . rand(111111, 999999);


                    $withdraw_amount = getbalance($last_provider, $user->user_code, 'alexaung57');

                    if ($withdraw_amount['errCode'] == 0) {
                        $wdamt = (int) $withdraw_amount['balance'];
                    } else {
                        $wdamt = 0;
                    }

                    $withdraw = transferFund([
                        "provider" => $last_provider,
                        "username" => $user->user_code,
                        "password" => 'alexaung57',
                        "referenceid" => $withdraw_referenceid,
                        "type" => "1",
                        "amount" => sprintf('%0.2f', $wdamt)
                    ]);

                    if ($withdraw == 0) {
                            $deposit_referenceid = date("YmdHis") . rand(111111, 999999);
                        $deposit = transferFund([
                        "provider" => $provider,
                        "username" => $user->user_code,
                        "password" => 'alexaung57',
                        "referenceid" => $deposit_referenceid,
                        "type" => "0",
                        "amount" => sprintf('%0.2f', $wdamt)
                       ]);
                       if ($withdraw == 0) {
                          gamelogs($user->id, $provider, "0", $deposit_referenceid, "success", $deposit, sprintf('%0.2f', $wdamt));
                        } else {
                          gamelogs($user->id, $provider, "0", $deposit_referenceid, "failed", $deposit, sprintf('%0.2f', $wdamt));
                       }
                       $basedata->updateProvider($provider, $user->id, $withdraw_amount['balance']);
                        gamelogs($user->id, $last_provider, "1", $withdraw_referenceid, "success", $withdraw, sprintf('%0.2f', $wdamt));
                    } else {
                        gamelogs($user->id, $last_provider, "1", $withdraw_referenceid, "failed", $withdraw, sprintf('%0.2f', $wdamt));
                    }
                

                 
                   } else {
                    $res = $result['errCode'];
                    return response()->json([
                        'message' => $res
                    ], 400);
                }

               

                return response()->json([
                    "res" => $res,
                    "amount" => sprintf('%0.2f', $wdamt),
                    "deposite_err" => $deposit,
                    "withdraw_err" => $withdraw,
                ]);
            }
        }
    }

    public function sport_game(Request $request, $id)
    {
        if (gameMaintainance() == 1) {
            return response()->json([
                'message' => 'Game Sever Maintaince please wait 30 Minutes'
            ], 400);
        }
        $p_code = $request->query('p_id');
        $req = $request->query('req');
        $menus = [];
        if ($p_code != null) {
            $user = DB::table('users')->where('id', $id)->first();

            if (!empty($user)) {
                $provider = DB::table('game_providers')->where('id', $p_code)->pluck('p_code')->first();
                $category = DB::table('game_categories')->where('id', 1)->pluck('p_type')->first();
                $md5 = md5('icmk' . 'alexaung57' . $provider . $category . $user->user_code . 'ce6f73f30d79fda780c9d2a82997b09c');
                $sig = strtoupper($md5);
                $url = 'http://gsmd.336699bet.com/launchGames.aspx';
                if ($req == 'web') {
                    $parameter = '?operatorcode=icmk&providercode=' . $provider . '&username=' . $user->user_code . '&password=' . 'alexaung57' . '&type=' . $category . '&gameid=0&lang=en-US&html5=0&signature=' . $sig;
                } else {
                    $parameter = '?operatorcode=icmk&providercode=' . $provider . '&username=' . $user->user_code . '&password=' . 'alexaung57' . '&type=' . $category . '&gameid=0&lang=en-US&html5=1&signature=' . $sig;
                }
                $re_url = $url . $parameter;
                $url_res = Http::get($re_url);
                $result = $url_res->json();

                if ($result['errCode'] == 0) {
                    $res = $result['gameUrl'];
                } else {
                    $res = $result['errCode'];
                }

                $basedata = new FirebaseController();
                $user_balance = $basedata->getValue($user->id);
                $last_provider = $user_balance['last_provider'];
                $withdraw_referenceid = date("YmdHis") . rand(111111, 999999);


                $withdraw_amount = getbalance($last_provider, $user->user_code, 'alexaung57');

                if ($withdraw_amount['errCode'] == 0) {
                    $wdamt = (int) $withdraw_amount['balance'];
                } else {
                    $wdamt = 0;
                }

                $withdraw = transferFund([
                    "provider" => $last_provider,
                    "username" => $user->user_code,
                    "password" => 'alexaung57',
                    "referenceid" => $withdraw_referenceid,
                    "type" => "1",
                    "amount" => sprintf('%0.2f', $wdamt)
                ]);

                if ($withdraw == 0) {
                    gamelogs($user->id, $last_provider, "1", $withdraw_referenceid, "success", $withdraw, sprintf('%0.2f', $wdamt));
                    $deposit_referenceid = date("YmdHis") . rand(111111, 999999);

                $deposit = transferFund([
                    "provider" => $provider,
                    "username" => $user->user_code,
                    "password" => 'alexaung57',
                    "referenceid" => $deposit_referenceid,
                    "type" => "0",
                    "amount" => sprintf('%0.2f', $wdamt)
                ]);

                if ($withdraw == 0) {
                    gamelogs($user->id, $provider, "0", $deposit_referenceid, "success", $deposit, sprintf('%0.2f', $wdamt));
                } else {
                    gamelogs($user->id, $provider, "0", $deposit_referenceid, "failed", $deposit, sprintf('%0.2f', $wdamt));
                }

                $basedata->updateProvider($provider, $user->id, $withdraw_amount['balance']);
                } else {
                    gamelogs($user->id, $last_provider, "1", $withdraw_referenceid, "failed", $withdraw, sprintf('%0.2f', $wdamt));
                }

                

                return response()->json([
                    "res" => $res,
                    'resurl' => $re_url,
                    "amount" => sprintf('%0.2f', $wdamt),
                    "deposite_err" => $deposit,
                    "withdraw_err" => $withdraw,
                ]);
            }
        }
    }

    public function livecasino_game(Request $request, $id)
    {
        if (gameMaintainance() == 1) {
            return response()->json([
                'message' => 'Game Sever Maintaince please wait 30 Minutes'
            ], 400);
        }
        $p_code = $request->query('p_id');
        $req = $request->query('req');
        $menus = [];
        if ($p_code != null) {
            $user = DB::table('users')->where('id', $id)->first();
            if (!empty($user)) {
                $provider = DB::table('game_providers')->where('id', $p_code)->pluck('p_code')->first();
                $category = DB::table('game_categories')->where('id', 4)->pluck('p_type')->first();
                $md5 = md5('icmk' . 'alexaung57' . $provider . $category . $user->user_code . 'ce6f73f30d79fda780c9d2a82997b09c');
                $sig = strtoupper($md5);
                $url = 'http://gsmd.336699bet.com/launchGames.aspx';
                if ($req == 'web') {
                    $parameter = '?operatorcode=icmk&providercode=' . $provider . '&username=' . $user->user_code . '&password=' . 'alexaung57' . '&type=' . $category . '&gameid=0&lang=en-US&html5=0&signature=' . $sig;
                } else {
                    $parameter = '?operatorcode=icmk&providercode=' . $provider . '&username=' . $user->user_code . '&password=' . 'alexaung57' . '&type=' . $category . '&gameid=0&lang=en-US&html5=1&signature=' . $sig;
                }
                $re_url = $url . $parameter;
                $url_res = Http::get($re_url);
                $result = $url_res->json();

                if ($result['errCode'] == 0) {
                    $res = $result['gameUrl'];
                } else {
                    $res = $result['errCode'];
                }

                $basedata = new FirebaseController();
                $user_balance = $basedata->getValue($user->id);
                $last_provider = $user_balance['last_provider'];
                $withdraw_referenceid = date("YmdHis") . rand(111111, 999999);


                $withdraw_amount = getbalance($last_provider, $user->user_code, 'alexaung57');

                if ($withdraw_amount['errCode'] == 0) {
                    $wdamt = (int) $withdraw_amount['balance'];
                } else {
                    $wdamt = 0;
                }

                $withdraw = transferFund([
                    "provider" => $last_provider,
                    "username" => $user->user_code,
                    "password" => 'alexaung57',
                    "referenceid" => $withdraw_referenceid,
                    "type" => "1",
                    "amount" => sprintf('%0.2f', $wdamt)
                ]);

                if ($withdraw == 0) {
                      $deposit_referenceid = date("YmdHis") . rand(111111, 999999);

                $deposit = transferFund([
                    "provider" => $provider,
                    "username" => $user->user_code,
                    "password" => 'alexaung57',
                    "referenceid" => $deposit_referenceid,
                    "type" => "0",
                    "amount" => sprintf('%0.2f', $wdamt)
                ]);

                if ($withdraw == 0) {
                    gamelogs($user->id, $provider, "0", $deposit_referenceid, "success", $deposit, sprintf('%0.2f', $wdamt));
                } else {
                    gamelogs($user->id, $provider, "0", $deposit_referenceid, "failed", $deposit, sprintf('%0.2f', $wdamt));
                }

                $basedata->updateProvider($provider, $user->id, $withdraw_amount['balance']);
                    gamelogs($user->id, $last_provider, "1", $withdraw_referenceid, "success", $withdraw, sprintf('%0.2f', $wdamt));
                } else {
                    gamelogs($user->id, $last_provider, "1", $withdraw_referenceid, "failed", $withdraw, sprintf('%0.2f', $wdamt));
                }

              

                return response()->json([
                    "res" => $res,
                    'res_url' => $re_url,
                    "amount" => sprintf('%0.2f', $wdamt),
                    "deposite_err" => $deposit,
                    "withdraw_err" => $withdraw,
                ]);
            }
        }
    }

    public function getBalance(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $get_balance = getbalance($request->provider, $user->user_code, 'alexaung57');
        if ($get_balance['errCode'] == 0) {
            $base = new FirebaseController();
            $value = $base->getValue($id);
            $base->updateProvider($value['last_provider'], $id, $get_balance['balance']);

            $message = [
                'message' => 'success',
                'balance' => round($get_balance['balance'], 2)
            ];
        } else {
            $message = [
                'message' => 'fail',
            ];
        }

        return response()->json($message);
    }

    public function getGameBalance($id)
    {
        $user = User::findOrFail($id);

        $get_balance = new FirebaseController();
        $user_balance = $get_balance->getValue($id);
        $provider = $user_balance['last_provider'];
        $get_balance = getbalance($provider, $user->user_code, 'icasino');
        // $get_balance = getbalance($request->provider, $user->user_code, 'alexaung57');

        return response()->json($get_balance);
    }

    public function transactionreports(Request $request)
    {
        $username = Auth::user()->user_code;

        $data = [];
        $dates = [];
        $from = date('Y-m-d', strtotime($request->query('start_date')));
        $to = date('Y-m-d', strtotime($request->query('end_date')));
        $bettings = BettingHistory::whereBetween('date', [$from, $to])
            ->where('username', $username)
            ->where('status', 1)
            ->get();
        foreach ($bettings as $betting) {
            array_push($dates, $betting->date);
        }
        $datefilter = array_values(array_unique($dates));
        foreach ($datefilter as $d) {
            $betslip = BettingHistory::where('date', $d)
                ->where('status', 1)
                ->where('username', $username)
                ->selectRaw("provider_name as provider")
                ->selectRaw("p_code as p_code")
                ->selectRaw("ROUND(SUM(turnover),2) as total_turnover")
                ->selectRaw("ROUND(SUM(commission),2) as total_commission")
                ->selectRaw("ROUND(SUM(payout) - SUM(bet),2) as total_winloss")
                ->selectRaw("ROUND(SUM(payout) - SUM(bet),2) as total_profitloss")
                ->groupBy(['provider_name', 'p_code'])
                ->get();
            $total = BettingHistory::where('date', $d)
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

            array_push($data, [
                "date" => $d,
                "provider_data" => $betslip,
                "totalbydate" => $datetotal
            ]);
        }

        $grand_winloss = $bettings->sum('payout') - $bettings->sum('bet');
        $grand_profitloss = $bettings->sum('payout') - $bettings->sum('bet');
        $grand_total = [
            "turnover" => round($bettings->sum('turnover'), 2),
            "winloss" => round($grand_winloss),
            "commission" => round($bettings->sum('commission'), 2),
            "profitloss" => round($grand_profitloss, 2)
        ];

        return [
            "data" => $data,
            "grand_total" => $grand_total
        ];
    }

    public function transactiondetail(Request $request)
    {
        $data = [];
        $date = date('Y-m-d', strtotime($request->query('date')));
        $provider = $request->query('provider');
        $username = $request->query('username');
        $betting = BettingHistory::where('date', $date)
            ->where('status', 1)
            ->where('username', $username)
            ->where('p_code', $provider)
            ->orderBy('created_at', 'DESC')
            ->get();
        $betting->load('game');
        foreach ($betting as $bet) {
            array_push($data, [
                "username" => $bet->username,
                "bet_time" => $bet->start_time,
                "biz_date" => $bet->match_time,
                "gametype" => $bet->game ? $bet->game->name : "-",
                "bet" => $bet->bet,
                "turnover" => $bet->turnover,
                "win" => $bet->p_win,
                "winloss" => round($bet->payout - $bet->bet, 2),
                "commission" => round($bet->commission, 2),
                "profitloss" => round($bet->payout - $bet->bet, 2),
                "created_at" => $bet->created_at,
            ]);
        }

        return response()->json($data);
    }
}