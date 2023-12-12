<?php

namespace App\Helper;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Agent;
use App\Models\CashIn;
use App\Models\Betting;
use App\Models\UserBet;
use App\Models\Betting1d;
use App\Models\UserBet1d;
use App\Models\LuckyNumber;
use App\Models\MasterAgent;
use App\Models\LotteryOffDay;
use App\Models\OverAllSetting;
use App\Models\CommissionHistory;
use Illuminate\Support\Facades\DB;


class SmaOne{
    public static function sma_record_daily($date, $column, $sma_id, $req_who){
        $diffWithGMT=6*60*60+30*60;
        $now = gmdate('H:i', time()+$diffWithGMT);
        $dateplus = gmdate('Y-m-d', time()+$diffWithGMT);
        $result = [];
            if($req_who === "senior"){
                if($column === 'master_agent_id'){
                    $hists = helper::getHistSma($column, $date, $sma_id, $req_who);
                    foreach($hists as $hist){
                        $ma = MasterAgent::where('id','=', $hist->master_agent_id)->first();
                        if($ma->senior_agent_id !== NULL){
                            $ma_user_value = helper::find_master_user($ma->id, $date);
                            $credit = $hist->credit;
                            $record = $credit * $ma->percent / 100;
                            $left_credit = $credit - $record;
                            $total_sum = $ma_user_value;
                            $res = [
                                "id" => $ma->id,
                                "name" => $ma->name,
                                "phone" => $ma->phone,
                                "percent" => $ma->percent,
                                "to_get" => $credit,
                                "commission" => $record,
                                "to_pay" => (float) $total_sum,
                                "result" => $left_credit - $total_sum
                            ];
                            array_push($result, $res);
                        }
                    }
                } else if($column === 'agent_id'){
                    $data = SmaOne::findAgent($date, $sma_id, 'senior_agent_id');
                    $result = $data;
                } else {
                    $sma_users = User::where('senior_agent_id', $sma_id)->get();
                    foreach($sma_users as $sma_user){
                        $bettings = UserBet1d::where('user_id', $sma_user->id)->where('date', $date)->get();
                        if($bettings->count() > 0){
                            $reward = $bettings->sum('reward_amount');
                            $bet_amount = $bettings->sum('total_amount');
                            $res = [
                                "id" => $sma_user->id,
                                "name" => $sma_user->name,
                                "phone" => $sma_user->phone,
                                "reward" => (float) $reward,
                            ];
                        } else {
                            $res = [];
                        }

                    array_push($result, $res);
                    }
                }
            } else if($req_who === 'master'){
                if($column !== 'user'){
                    $data = SmaOne::findAgent($date, $sma_id, 'master_agent_id');
                    $result = $data;
                } else {
                    $sma_users = User::where('master_agent_id', $sma_id)->get();
                    foreach($sma_users as $sma_user){
                        $bettings = UserBet1d::where('user_id', $sma_user->id)->where('date', $date)->get();
                        if($bettings->count() > 0){
                            $reward = $bettings->sum('reward_amount');
                            $bet_amount = $bettings->sum('total_amount');
                            $res = [
                                "id" => $sma_user->id,
                                "name" => $sma_user->name,
                                "phone" => $sma_user->phone,
                                "reward" => (float) $reward,
                            ];
                        } else {
                            $res = [];
                        }

                        array_push($result, $res);
                    }
                }
            } else {
                $sma_users = User::where('agent_id', $sma_id)->get();
                foreach($sma_users as $sma_user){
                    $bettings = UserBet1d::where('user_id', $sma_user->id)->where('date', $date)->get();
                    if($bettings->count() > 0){
                        $reward = $bettings->sum('reward_amount');
                        $bet_amount = $bettings->sum('total_amount');
                        $res = [
                            "id" => $sma_user->id,
                            "name" => $sma_user->name,
                            "phone" => $sma_user->phone,
                            "reward" => (float) $reward,
                        ];
                    } else {
                        $res = [];
                    }

                    array_push($result, $res);
            }
        }
        return response()->json([
            "message" => "success",
            "result" => array_filter($result)
        ]);
    }

    public static function findAgent($date, $sma_id, $agent_column){
        $result = [];
        $agents = Agent::where($agent_column, $sma_id)->get();
        foreach($agents as $a){
            $cashin = CashIn::where('agent_id', $a->id)->where('date', $date)->first();
            if(!empty($cashin)){
                $a_user_value = helper::find_user($a->id, $date, 'agent_id');
                $record = $a_user_value['cashin'] * $a->percent / 100;
                $left_credit = $a_user_value['cashin'] - $record;
                $total_sum = $a_user_value['reward'];
                $res = [
                    "id" => $a->id,
                    "name" => $a->name,
                    "phone" => $a->phone,
                    "percent" => $a->percent,
                    "to_get" => (float) $a_user_value['cashin'],
                    "commission" => $record,
                    "to_pay" => (float) $total_sum,
                    "result" => $left_credit - $total_sum
                ];
                array_push($result, $res);
            }
        }
        return $result;
    }

    public static function findAgentSection($time,$date, $id){
        $a = Agent::where('id', $id)->first();
        $a_user_value = helper::find_user_by_section($a->id, $date, 'agent_id', $time);
        $record = $a_user_value['cashin'] * $a->percent / 100;
        $left_credit = $a_user_value['cashin'] - $record;
        $total_sum = $a_user_value['reward'];
        $res = [
            "id" => $a->id,
            "name" => $a->name,
            "phone" => $a->phone,
            "percent" => $a->percent,
            "to_get" => (float) $a_user_value['cashin'],
            "commission" => $record,
            "to_pay" => (float) $total_sum,
            "result" => $left_credit - $total_sum
        ];
        return $res;
    }

    public static function sma_record_section($time,$date, $column, $sma_id, $req_who, $id){
        $diffWithGMT=6*60*60+30*60;
        $now = gmdate('H:i', time()+$diffWithGMT);
        $dateplus = gmdate('Y-m-d', time()+$diffWithGMT);
        $result = [];
        if($req_who === "senior"){
            if($column === 'master_agent_id'){
                $hists = helper::getHistSmaSection($column,$time, $date, $sma_id, $req_who);
                foreach($hists as $hist){
                    $ma = MasterAgent::where('id','=', $hist->master_agent_id)->first();
                    if($ma->senior_agent_id !== NULL){
                        $ma_user_value = helper::find_master_user_by_section($ma->id, $date,$time);
                        $credit = $hist->credit;
                        $record = $credit * $ma->percent / 100;
                        $left_credit = $credit - $record;
                        $total_sum = $ma_user_value;
                        $res = [
                            "id" => $ma->id,
                            "name" => $ma->name,
                            "phone" => $ma->phone,
                            "percent" => $ma->percent,
                            "to_get" => $credit,
                            "commission" => $record,
                            "to_pay" => (float) $total_sum,
                            "result" => $left_credit - $total_sum
                        ];
                        array_push($result, $res);
                    }
                }
            } else if($column === 'agent_id'){
                $data = SmaOne::findAgentSection($time,$date, $id);
                array_push($result, $data);
            } else {
                $sma_users = User::where('senior_agent_id', $sma_id)->get();
                foreach($sma_users as $sma_user){
                    $bettings = UserBet1d::where('user_id', $sma_user->id)->where('date', $date)->where('section', $time)->get();
                    if($bettings->count() > 0){
                        $reward = $bettings->sum('reward_amount');
                        $bet_amount = $bettings->sum('total_amount');
                        $res = [
                            "name" => $sma_user->name,
                            "phone" => $sma_user->phone,
                            "reward" => (float) $reward,
                        ];
                    } else {
                        $res = [];
                    }

                array_push($result, $res);
                }
            }
        } else if($req_who === 'master'){
            if($column !== 'user'){
                $data = SmaOne::findAgentSection($time,$date, $id);
                array_push($result, $data);
            } else {
                $sma_users = User::where('master_agent_id', $sma_id)->get();
                foreach($sma_users as $sma_user){
                    $bettings = UserBet1d::where('user_id', $sma_user->id)->where('date', $date)->where('section', $time)->get();
                    if($bettings->count() > 0){
                        $reward = $bettings->sum('reward_amount');
                        $bet_amount = $bettings->sum('total_amount');
                        $res = [
                            "name" => $sma_user->name,
                            "phone" => $sma_user->phone,
                            "reward" => (float) $reward,
                        ];
                    } else {
                        $res = [];
                    }

                    array_push($result, $res);
                }
            }
        } else {
            $sma_users = User::where('agent_id', $sma_id)->get();
            foreach($sma_users as $sma_user){
                $bettings = UserBet1d::where('user_id', $sma_user->id)->where('date', $date)->where('section', $time)->get();
                if($bettings->count() > 0){
                    $reward = $bettings->sum('reward_amount');
                    $bet_amount = $bettings->sum('total_amount');
                    $res = [
                        "name" => $sma_user->name,
                        "phone" => $sma_user->phone,
                        "reward" => (float) $reward,
                    ];
                } else {
                    $res = [];
                }

                array_push($result, $res);
            }
        }

        return response()->json([
            "message" => "success",
            "result" => array_filter($result)
        ]);
    }

    public static function sma_grant_numbers($time, $date, $id, $column){
        $res = [];
        $betslip = [];
        $message = 0;
        $numbers = helper::numbers();
        if($column == 'senior_agent_id'){
            $master_agents = MasterAgent::where($column, $id)->pluck('id')->toArray();
            $agents = Agent::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $m_user = User::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $s_user = User::where('senior_agent_id', $id)->pluck('id')->toArray();
            $agent_direct = Agent::where('senior_agent_id', $id)->pluck('id')->toArray();
            $a_direct_user = User::where('agent_id', $agent_direct)->pluck('id')->toArray();

            $last_users = array_merge($agent_user, $m_user, $s_user,$a_direct_user);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->where('section', $time)->with('bettings')->get();
            if($userbets->count() > 0){
                foreach($userbets as $bet){
                    foreach($bet->bettings as $b){
                        array_push($betslip, $b->id);
                    }
                }

                foreach($numbers as $num){
                    $betting_amount = Betting1d::whereIn('id', $betslip)->get();
                    $total_amount = $betting_amount->where('bet_number', $num->bet_number)->sum('amount');
                    $overall = OverAllSetting::first();
                    if($num->hot_amout_limit > 0 ){
                        if($num->hot_amout_limit <= $total_amount){
                            $message = 1;
                        }  else {
                            $message = 0;
                        }
                    } else {
                        if($overall->over_all_amount_1d <= $total_amount){
                            $message = 1;
                        } else {
                            $message = 0;
                        }
                    }
                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "over_amount_message" => $message,
                        "hot_amount_limit" => $num->hot_amout_limit,
                        "amount" => $total_amount
                    ]);
                }

            } else {
                foreach($numbers as $num){
                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "over_amount_message" => $message,
                        "hot_amount_limit" => $num->hot_amout_limit,
                        "amount" => 0
                    ]);
                }
            }
        } else if($column == 'master_agent_id'){
            $agents = Agent::where($column, $id)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $users = User::where($column, $id)->pluck('id')->toArray();
            $last_users = array_merge($agent_user, $users);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->where('section', $time)->with('bettings')->get();
            if($userbets->count() > 0){
                foreach($userbets as $bet){
                    foreach($bet->bettings as $b){
                        array_push($betslip, $b->id);
                    }
                }

                foreach($numbers as $num){
                    $betting_amount = Betting1d::whereIn('id', $betslip)->get();
                    $total_amount = $betting_amount->where('bet_number', $num->bet_number)->sum('amount');
                    $overall = OverAllSetting::first();
                    if($num->hot_amout_limit > 0 ){
                        if($num->hot_amout_limit <= $total_amount){
                            $message = 1;
                        }  else {
                            $message = 0;
                        }
                    } else {
                        if($overall->over_all_amount_1d <= $total_amount){
                            $message = 1;
                        } else {
                            $message = 0;
                        }
                    }
                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "over_amount_message" => $message,
                        "hot_amount_limit" => $num->hot_amout_limit,
                        "amount" => $total_amount
                    ]);
                }
            } else {
                foreach($numbers as $num){
                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "over_amount_message" => $message,
                        "hot_amount_limit" => $num->hot_amout_limit,
                        "amount" => 0
                    ]);
                }
            }
        } else {
            $last_users = User::where($column, $id)->pluck('id')->toArray();
            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->where('section', $time)->with('bettings')->get();
            if($userbets->count() > 0){
                foreach($userbets as $bet){
                    foreach($bet->bettings as $b){
                        array_push($betslip, $b->id);
                    }
                }

                foreach($numbers as $num){
                    $betting_amount = Betting1d::whereIn('id', $betslip)->get();
                    $total_amount = $betting_amount->where('bet_number', $num->bet_number)->sum('amount');
                    $overall = OverAllSetting::first();
                    if($num->hot_amout_limit > 0 ){
                        if($num->hot_amout_limit <= $total_amount){
                            $message = 1;
                        }  else {
                            $message = 0;
                        }
                    } else {
                        if($overall->over_all_amount_1d <= $total_amount){
                            $message = 1;
                        } else {
                            $message = 0;
                        }
                    }
                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "over_amount_message" => $message,
                        "hot_amount_limit" => $num->hot_amout_limit,
                        "amount" => $total_amount
                    ]);
            }
            } else {
                foreach($numbers as $num){
                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "over_amount_message" => $message,
                        "hot_amount_limit" => $num->hot_amout_limit,
                        "amount" => 0
                    ]);
                }
            }
        }

        return response()->json([
            "message" => "success",
            "status" => "open",
            "data" => $res
        ]);
    }

    public static function sma_grant_numbers_daily($date, $id, $column){
        $res = [];
        $betslip = [];
        $numbers = helper::numbers();
        if($column == 'senior_agent_id'){
            $master_agents = MasterAgent::where($column, $id)->pluck('id')->toArray();
            $agents = Agent::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $m_user = User::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $s_user = User::where('senior_agent_id', $id)->pluck('id')->toArray();
            $agent_direct = Agent::where('senior_agent_id', $id)->pluck('id')->toArray();
            $a_direct_user = User::where('agent_id', $agent_direct)->pluck('id')->toArray();

            $last_users = array_merge($agent_user, $m_user, $s_user, $a_direct_user);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->with('bettings')->get();
            if($userbets->count() > 0){
                foreach($userbets as $bet){
                    foreach($bet->bettings as $b){
                        array_push($betslip, $b->id);
                    }
                }

                foreach($numbers as $num){
                        $betting_amount = Betting1d::whereIn('id', $betslip)->get();
                        $total_amount = $betting_amount->where('bet_number', $num->bet_number)->sum('amount');

                        array_push($res, [
                            "id" => $num->id,
                            "bet_number" => $num->bet_number,
                            "close_number" => $num->close_number,
                            "amount" => $total_amount
                        ]);
                }

            } else {
                foreach($numbers as $num){
                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "amount" => 0
                    ]);
                }
            }
        } else if($column == 'master_agent_id'){
            $agents = Agent::where($column, $id)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $users = User::where($column, $id)->pluck('id')->toArray();
            $last_users = array_merge($agent_user, $users);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->with('bettings')->get();
            if($userbets->count() > 0){
                foreach($userbets as $bet){
                    foreach($bet->bettings as $b){
                        array_push($betslip, $b->id);
                    }
                }

                foreach($numbers as $num){
                    $betting_amount = Betting1d::whereIn('id', $betslip)->get();
                    $total_amount = $betting_amount->where('bet_number', $num->bet_number)->sum('amount');

                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "amount" => $total_amount
                    ]);
                }
            } else {
                foreach($numbers as $num){
                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "amount" => 0
                    ]);
                }
            }
        } else {
            $last_users = User::where($column, $id)->pluck('id')->toArray();
            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->with('bettings')->get();
            if($userbets->count() > 0){
                foreach($userbets as $bet){
                    foreach($bet->bettings as $b){
                        array_push($betslip, $b->id);
                    }
                }

                foreach($numbers as $num){
                    $betting_amount = Betting1d::whereIn('id', $betslip)->get();
                    $total_amount = $betting_amount->where('bet_number', $num->bet_number)->sum('amount');

                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "amount" => $total_amount
                    ]);
            }
            } else {
                foreach($numbers as $num){
                    array_push($res, [
                        "id" => $num->id,
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "amount" => 0
                    ]);
                }
            }
        }

        return response()->json([
            "message" => "success",
            "status" => "open",
            "data" => $res
        ]);
    }

    public static function sma_single_detail($time, $date, $number,$column, $id){
        $final = [];
        $betslip = [];

        if($column == 'senior_agent_id'){
            $arr = [
                [
                "id" => 'senior_agent_id',
                "model" => 'senior_agents'
                ],
                [
                "id" => 'master_agent_id',
                "model" => 'master_agents'
                ],
                [
                "id" => 'agent_id',
                "model" => 'agents'
                ],
            ];

            $master_agents = MasterAgent::where($column, $id)->pluck('id')->toArray();
            $agents = Agent::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $m_user = User::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $s_user = User::where('senior_agent_id', $id)->pluck('id')->toArray();
            $agent_direct = Agent::where('senior_agent_id', $id)->pluck('id')->toArray();
            $a_direct_user = User::where('agent_id', $agent_direct)->pluck('id')->toArray();

            $last_users = array_merge($agent_user, $m_user, $s_user, $a_direct_user);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('section', $time)->where('date', $date)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }

            $bettings = Betting1d::whereIn('id', $betslip)->where('bet_number', $number)->with('user_bets')->get();
        } else if($column == 'master_agent_id'){
            $arr = [
                [
                "id" => 'master_agent_id',
                "model" => 'master_agents'
                ],
                [
                "id" => 'agent_id',
                "model" => 'agents'
                ],
            ];

            $agents = Agent::where($column, $id)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $users = User::where($column, $id)->pluck('id')->toArray();
            $last_users = array_merge($agent_user, $users);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('section', $time)->where('date', $date)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }

            $bettings = Betting1d::whereIn('id', $betslip)->where('bet_number', $number)->with('user_bets')->get();
        } else {
            $arr = [
                [
                "id" => 'agent_id',
                "model" => 'agents'
                ],
            ];
            $last_users = User::where($column, $id)->pluck('id')->toArray();
            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('section', $time)->where('date', $date)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }

            $bettings = Betting1d::whereIn('id', $betslip)->where('bet_number', $number)->with('user_bets')->get();
        }

        if($bettings->count() > 0){
            foreach($bettings as $betting){
                foreach($betting->user_bets as $bet){
                    $ub = UserBet1d::where('id',$bet->id)->with('user')->first();
                    foreach($arr as $r){
                        $parentid = $r['id'];
                        if($ub->user->$parentid){
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final,$bt);
                        }
                    }
                }
            }
        }

        return $final;
    }

    public static function sma_daily_detail($date, $number,$column, $id){
        $final = [];
        $betslip = [];

        if($column == 'senior_agent_id'){
            $arr = [
                [
                "id" => 'senior_agent_id',
                "model" => 'senior_agents'
                ],
                [
                "id" => 'master_agent_id',
                "model" => 'master_agents'
                ],
                [
                "id" => 'agent_id',
                "model" => 'agents'
                ],
            ];

            $master_agents = MasterAgent::where($column, $id)->pluck('id')->toArray();
            $agents = Agent::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $m_user = User::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $s_user = User::where('senior_agent_id', $id)->pluck('id')->toArray();
            $agent_direct = Agent::where('senior_agent_id', $id)->pluck('id')->toArray();
            $a_direct_user = User::where('agent_id', $agent_direct)->pluck('id')->toArray();
            $last_users = array_merge($agent_user, $m_user, $s_user, $a_direct_user);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }
            $bettings = Betting1d::whereIn('id', $betslip)->where('bet_number', $number)->with('user_bets')->get();
        } else if($column == 'master_agent_id'){
            $arr = [
                [
                "id" => 'master_agent_id',
                "model" => 'master_agents'
                ],
                [
                "id" => 'agent_id',
                "model" => 'agents'
                ],
            ];

            $agents = Agent::where($column, $id)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $users = User::where($column, $id)->pluck('id')->toArray();
            $last_users = array_merge($agent_user, $users);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }

            $bettings = Betting1d::whereIn('id', $betslip)->where('bet_number', $number)->with('user_bets')->get();
        } else {
            $arr = [
                [
                "id" => 'agent_id',
                "model" => 'agents'
                ],
            ];
            $last_users = User::where($column, $id)->pluck('id')->toArray();
            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }

            $bettings = Betting1d::whereIn('id', $betslip)->where('bet_number', $number)->with('user_bets')->get();
        }


        if($bettings->count() > 0){
            foreach($bettings as $betting){
                foreach($betting->user_bets as $bet){
                    $ub = UserBet1d::where('id',$bet->id)->with('user')->first();
                    foreach($arr as $r){
                        $parentid = $r['id'];
                        if($ub->user->$parentid){
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final,$bt);
                        }
                    }
                }
            }
        }

        return $final;
    }

    public static function sma_section_statics($time,$date,$column, $id){
        $betslip = [];
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        if($column == 'senior_agent_id'){
            $master_agents = MasterAgent::where($column, $id)->pluck('id')->toArray();
            $agents = Agent::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $m_user = User::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $s_user = User::where('senior_agent_id', $id)->pluck('id')->toArray();
            $agent_direct = Agent::where('senior_agent_id', $id)->pluck('id')->toArray();
            $a_direct_user = User::where('agent_id', $agent_direct)->pluck('id')->toArray();

            $last_users = array_merge($agent_user, $m_user, $s_user, $a_direct_user);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date_format)->where('section', $time)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }
            $bet_counts = Betting1d::where('section', $time)->where('date', $date_format)->whereIn('id', $betslip)->get();
            $bet_amounts = $userbets->sum('total_amount');
            $reward_amounts = $userbets->sum('reward_amount');

            if($bet_counts->count() > 0){
                foreach($bet_counts as $count){
                    $key = $count->bet_number ;
                        if (isset($res[$key])){
                            if ($res[$key]['bet_number'] === $count->bet_number){
                                $res[$key]['amount'] += $count->amount;
                            }
                        } else {
                            $res[$key] = $count;
                        }
                }

                $r_key = array_values($res);
                foreach($r_key as $k){
                    array_push($amt, [
                        "amount" => (float) $k['amount'],
                        "bet_number" => $k['bet_number']
                    ]);
                }
                $total_bet_numbers = count( $r_key);
                $max_bet_amount = max($amt);
            } else {
                $total_bet_numbers = 0;
                $max_bet_amount = 0;
            }

            $commit = CommissionHistory::where('section', $time)
            ->where($column, $id)
            ->whereDate('created_at', $date_format)
            ->get();
            $total_commit = $commit->sum('total_commission');
            $senior_commit = $commit->sum('senior_agent_commission');
            $get_com = $total_commit - $senior_commit;
            $com = (float) $get_com;
            $sum_cost = $com + $reward_amounts;
            if($sum_cost > 0){
                $profit = $bet_amounts - $sum_cost;
            } else {
                $profit = $bet_amounts;
            }

            $lucky = LuckyNumber::where('create_date',$date_format)->where('section', $time)->first();
            if(!empty($lucky)){
                $luck = $lucky->lucky_number;
            } else {
                $luck = '-';
            }
            $now = Carbon::now();
            $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
            if(HolidayHelper::isTheDayHoliday($date_format, $lot_off) === 1){
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "commission_total" => 0,
                    "his_commission" => 0,
                    "profit" => $profit,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            } else {
                if(HolidayHelper::isThatWeekend($date_format) == 1){
                    return response()->json([
                        "all_bet_amounts" => 0,
                        "total_reward" => 0,
                        "commission_total" => 0,
                        "his_commission" => $senior_commit,
                        "profit" => $profit,
                        "total_bet_number" => 0,
                        "most_amount_number" => 0,
                        "lucky_number" => '-',
                    ]);
                }
                return response()->json([
                    "all_bet_amounts" => (float) $bet_amounts,
                    "total_reward" => (float) $reward_amounts,
                    "commission_total" => $com,
                    "his_commission" => $senior_commit,
                    "profit" => $profit,
                    "total_bet_number" => $total_bet_numbers,
                    "most_amount_number" => $max_bet_amount,
                    "lucky_number" => $luck,
                ]);
            }
        } else if($column == 'master_agent_id'){
            $agents = Agent::where($column, $id)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $users = User::where($column, $id)->pluck('id')->toArray();
            $last_users = array_merge($agent_user, $users);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('section', $time)->where('date', $date_format)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }

            $bet_counts = Betting1d::where('section', $time)->where('date', $date_format)->whereIn('id', $betslip)->get();
            $bet_amounts = $userbets->sum('total_amount');
            $reward_amounts = $userbets->sum('reward_amount');

            if($bet_counts->count() > 0){
                foreach($bet_counts as $count){
                    $key = $count->bet_number ;
                        if (isset($res[$key])){
                            if ($res[$key]['bet_number'] === $count->bet_number){
                                $res[$key]['amount'] += $count->amount;
                            }
                        } else {
                            $res[$key] = $count;
                        }
                }

                $r_key = array_values($res);
                foreach($r_key as $k){
                    array_push($amt, [
                        "amount" => (float) $k['amount'],
                        "bet_number" => $k['bet_number']
                    ]);
                }
                $total_bet_numbers = count( $r_key);
                $max_bet_amount = max($amt);
            } else {
                $total_bet_numbers = 0;
                $max_bet_amount = 0;
            }

            $commit = CommissionHistory::where('section', $time)
            ->where($column, $id)
            ->whereDate('created_at', $date_format)
            ->get();
            $senior_commit = $commit->sum('senior_agent_commission');
            $minus_commit = $commit->sum('total_commission');
            $total_commit = $minus_commit - $senior_commit;
            $his_commit = $commit->sum('master_agent_commission');
            $get_com = $total_commit - $his_commit;
            $com = (float) $get_com;
            $sum_cost = $com + $reward_amounts;
            if($sum_cost > 0){
                $profit = $bet_amounts - $sum_cost;
            } else {
                $profit = $bet_amounts;
            }

            $lucky = LuckyNumber::where('create_date',$date_format)->where('section', $time)->first();
            if(!empty($lucky)){
                $luck = $lucky->lucky_number;
            } else {
                $luck = '-';
            }
            $now = Carbon::now();
            $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
            if(HolidayHelper::isTheDayHoliday($date_format, $lot_off) === 1){
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "commission_total" => 0,
                    "his_commission" => 0,
                    "profit" => $profit,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            } else {
                if(HolidayHelper::isThatWeekend($date_format) == 1){
                    return response()->json([
                        "all_bet_amounts" => 0,
                        "total_reward" => 0,
                        "commission_total" => 0,
                        "his_commission" => 0,
                        "profit" => $profit,
                        "total_bet_number" => 0,
                        "most_amount_number" => 0,
                        "lucky_number" => '-',
                    ]);
                }
                return response()->json([
                    "all_bet_amounts" => (float) $bet_amounts,
                    "total_reward" => (float) $reward_amounts,
                    "commission_total" => $com,
                    "his_commission" => $his_commit,
                    "profit" => $profit,
                    "total_bet_number" => $total_bet_numbers,
                    "most_amount_number" => $max_bet_amount,
                    "lucky_number" => $luck,
                ]);
            }
        } else {
            $last_users = User::where($column, $id)->pluck('id')->toArray();
            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->where('section', $time)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }
            $bet_counts = Betting1d::where('section', $time)->where('date', $date_format)->whereIn('id', $betslip)->get();
            $bet_amounts = $userbets->sum('total_amount');
            $reward_amounts = $userbets->sum('reward_amount');

            if($bet_counts->count() > 0){
                foreach($bet_counts as $count){
                    $key = $count->bet_number ;
                        if (isset($res[$key])){
                            if ($res[$key]['bet_number'] === $count->bet_number){
                                $res[$key]['amount'] += $count->amount;
                            }
                        } else {
                            $res[$key] = $count;
                        }
                }

                $r_key = array_values($res);
                foreach($r_key as $k){
                    array_push($amt, [
                        "amount" => (float) $k['amount'],
                        "bet_number" => $k['bet_number']
                    ]);
                }
                $total_bet_numbers = count( $r_key);
                $max_bet_amount = max($amt);
            } else {
                $total_bet_numbers = 0;
                $max_bet_amount = 0;
            }

            $commit = CommissionHistory::where('section', $time)
            ->where($column, $id)
            ->whereDate('created_at', $date_format)
            ->get();
            $his_commit = $commit->sum('agent_commission');

            $sum_cost = $reward_amounts;
            if($sum_cost > 0){
                $profit = $bet_amounts - $sum_cost;
            } else {
                $profit = $bet_amounts;
            }

            $lucky = LuckyNumber::where('create_date',$date)->where('section', $time)->first();
            if(!empty($lucky)){
                $luck = $lucky->lucky_number;
            } else {
                $luck = '-';
            }
            $now = Carbon::now();
            $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
            if(HolidayHelper::isTheDayHoliday($date, $lot_off) === 1){
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "his_commission" => 0,
                    "profit" => $profit,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            } else {
                if(HolidayHelper::isThatWeekend($date) == 1){
                    return response()->json([
                        "all_bet_amounts" => 0,
                        "total_reward" => 0,
                        "his_commission" => 0,
                        "profit" => $profit,
                        "total_bet_number" => 0,
                        "most_amount_number" => 0,
                        "lucky_number" => '-',
                    ]);
                }
                return response()->json([
                    "all_bet_amounts" => (float) $bet_amounts,
                    "total_reward" => (float) $reward_amounts,
                    "his_commission" => $his_commit,
                    "profit" => $profit,
                    "total_bet_number" => $total_bet_numbers,
                    "most_amount_number" => $max_bet_amount,
                    "lucky_number" => $luck,
                ]);
            }
        }

    }

    public static function sma_daily_statics($date,$column, $id){
        $betslip = [];
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        if($column == 'senior_agent_id'){
            $master_agents = MasterAgent::where($column, $id)->pluck('id')->toArray();
            $agents = Agent::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $m_user = User::whereIn('master_agent_id', $master_agents)->pluck('id')->toArray();
            $s_user = User::where('senior_agent_id', $id)->pluck('id')->toArray();
            $agent_direct = Agent::where('senior_agent_id', $id)->pluck('id')->toArray();
            $a_direct_user = User::where('agent_id', $agent_direct)->pluck('id')->toArray();
            $last_users = array_merge($agent_user, $m_user, $s_user, $a_direct_user);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date_format)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }
            $bet_counts = Betting1d::where('date', $date_format)->whereIn('id', $betslip)->get();
            $bet_amounts = $userbets->sum('total_amount');
            $reward_amounts = $userbets->sum('reward_amount');

            if($bet_counts->count() > 0){
                foreach($bet_counts as $count){
                    $key = $count->bet_number ;
                        if (isset($res[$key])){
                            if ($res[$key]['bet_number'] === $count->bet_number){
                                $res[$key]['amount'] += $count->amount;
                            }
                        } else {
                            $res[$key] = $count;
                        }
                }

                $r_key = array_values($res);
                foreach($r_key as $k){
                    array_push($amt, [
                        "amount" => (float) $k['amount'],
                        "bet_number" => $k['bet_number']
                    ]);
                }
                $total_bet_numbers = count( $r_key);
                $max_bet_amount = max($amt);
            } else {
                $total_bet_numbers = 0;
                $max_bet_amount = 0;
            }

            $commit = CommissionHistory::where($column, $id)
            ->whereDate('created_at', $date_format)
            ->get();
            $total_commit = $commit->sum('total_commission');
            $senior_commit = $commit->sum('senior_agent_commission');
            $get_com = $total_commit - $senior_commit;
            $com = (float) $get_com;
            $sum_cost = $com + $reward_amounts;
            if($sum_cost > 0){
                $profit = $bet_amounts - $sum_cost;
            } else {
                $profit = $bet_amounts;
            }

            $lucky = LuckyNumber::where('create_date',$date)->get();
            if($lucky->count() > 0){
                $luck = $lucky;
            } else {
                $luck = '-';
            }
            $now = Carbon::now();
            $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
            if(HolidayHelper::isTheDayHoliday($date, $lot_off) === 1){
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "commission_total" => 0,
                    "his_commission" => 0,
                    "profit" => $profit,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            } else {
                if(HolidayHelper::isThatWeekend($date) == 1){
                    return response()->json([
                        "all_bet_amounts" => 0,
                        "total_reward" => 0,
                        "commission_total" => 0,
                        "his_commission" => $senior_commit,
                        "profit" => $profit,
                        "total_bet_number" => 0,
                        "most_amount_number" => 0,
                        "lucky_number" => '-',
                    ]);
                }
                return response()->json([
                    "all_bet_amounts" => (float) $bet_amounts,
                    "total_reward" => (float) $reward_amounts,
                    "commission_total" => $com,
                    "his_commission" => $senior_commit,
                    "profit" => $profit,
                    "total_bet_number" => $total_bet_numbers,
                    "most_amount_number" => $max_bet_amount,
                    "lucky_number" => $luck,
                ]);
            }
        } else if($column == 'master_agent_id'){
            $agents = Agent::where($column, $id)->pluck('id')->toArray();
            $agent_user = User::whereIn('agent_id', $agents)->pluck('id')->toArray();
            $users = User::where($column, $id)->pluck('id')->toArray();
            $last_users = array_merge($agent_user, $users);

            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }

            $bet_counts = Betting1d::where('date', $date_format)->whereIn('id', $betslip)->get();
            $bet_amounts = $userbets->sum('total_amount');
            $reward_amounts = $userbets->sum('reward_amount');

            if($bet_counts->count() > 0){
                foreach($bet_counts as $count){
                    $key = $count->bet_number ;
                        if (isset($res[$key])){
                            if ($res[$key]['bet_number'] === $count->bet_number){
                                $res[$key]['amount'] += $count->amount;
                            }
                        } else {
                            $res[$key] = $count;
                        }
                }

                $r_key = array_values($res);
                foreach($r_key as $k){
                    array_push($amt, [
                        "amount" => (float) $k['amount'],
                        "bet_number" => $k['bet_number']
                    ]);
                }
                $total_bet_numbers = count( $r_key);
                $max_bet_amount = max($amt);
            } else {
                $total_bet_numbers = 0;
                $max_bet_amount = 0;
            }

            $commit = CommissionHistory::where($column, $id)
            ->whereDate('created_at', $date_format)
            ->get();
            $total_commit = $commit->sum('total_commission');
            $his_commit = $commit->sum('master_agent_commission');
            $get_com = $total_commit - $his_commit;

            $com = (float) $get_com;
            $sum_cost = $com + $reward_amounts;
            if($sum_cost > 0){
                $profit = $bet_amounts - $sum_cost;
            } else {
                $profit = $bet_amounts;
            }

            $lucky = LuckyNumber::where('create_date',$date)->get();
            if($lucky->count() > 0){
                $luck = $lucky;
            } else {
                $luck = '-';
            }
            $now = Carbon::now();
            $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
            if(HolidayHelper::isTheDayHoliday($date, $lot_off) === 1){
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "commission_total" => 0,
                    "his_commission" => 0,
                    "profit" => $profit,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            } else {
                if(HolidayHelper::isThatWeekend($date) == 1){
                    return response()->json([
                        "all_bet_amounts" => 0,
                        "total_reward" => 0,
                        "commission_total" => 0,
                        "his_commission" => 0,
                        "profit" => $profit,
                        "total_bet_number" => 0,
                        "most_amount_number" => 0,
                        "lucky_number" => '-',
                    ]);
                }
                return response()->json([
                    "all_bet_amounts" => (float) $bet_amounts,
                    "total_reward" => (float) $reward_amounts,
                    "commission_total" => $com,
                    "his_commission" => $his_commit,
                    "profit" => $profit,
                    "total_bet_number" => $total_bet_numbers,
                    "most_amount_number" => $max_bet_amount,
                    "lucky_number" => $luck,
                ]);
            }
        } else {
            $last_users = User::where($column, $id)->pluck('id')->toArray();
            $userbets = UserBet1d::whereIn('user_id', $last_users)->where('date', $date)->with('bettings')->get();
            foreach($userbets as $bet){
                foreach($bet->bettings as $b){
                    array_push($betslip, $b->id);
                }
            }
            $bet_counts = Betting1d::where('date', $date_format)->whereIn('id', $betslip)->get();
            $bet_amounts = $userbets->sum('total_amount');
            $reward_amounts = $userbets->sum('reward_amount');

            if($bet_counts->count() > 0){
                foreach($bet_counts as $count){
                    $key = $count->bet_number ;
                        if (isset($res[$key])){
                            if ($res[$key]['bet_number'] === $count->bet_number){
                                $res[$key]['amount'] += $count->amount;
                            }
                        } else {
                            $res[$key] = $count;
                        }
                }

                $r_key = array_values($res);
                foreach($r_key as $k){
                    array_push($amt, [
                        "amount" => (float) $k['amount'],
                        "bet_number" => $k['bet_number']
                    ]);
                }
                $total_bet_numbers = count( $r_key);
                $max_bet_amount = max($amt);
            } else {
                $total_bet_numbers = 0;
                $max_bet_amount = 0;
            }

            $commit = CommissionHistory::where($column, $id)
            ->whereDate('created_at', $date_format)
            ->get();
            $his_commit = $commit->sum('agent_commission');

            $sum_cost = $reward_amounts;
            if($sum_cost > 0){
                $profit = $bet_amounts - $sum_cost;
            } else {
                $profit = $bet_amounts;
            }

            $lucky = LuckyNumber::where('create_date',$date)->get();
            if(!empty($lucky)){
                $luck = $lucky;
            } else {
                $luck = '-';
            }
            $now = Carbon::now();
            $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
            if(HolidayHelper::isTheDayHoliday($date, $lot_off) === 1){
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "his_commission" => 0,
                    "profit" => $profit,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            } else {
                if(HolidayHelper::isThatWeekend($date) == 1){
                    return response()->json([
                        "all_bet_amounts" => 0,
                        "total_reward" => 0,
                        "his_commission" => 0,
                        "profit" => $profit,
                        "total_bet_number" => 0,
                        "most_amount_number" => 0,
                        "lucky_number" => '-',
                    ]);
                }
                return response()->json([
                    "all_bet_amounts" => (float) $bet_amounts,
                    "total_reward" => (float) $reward_amounts,
                    "his_commission" => $his_commit,
                    "profit" => $profit,
                    "total_bet_number" => $total_bet_numbers,
                    "most_amount_number" => $max_bet_amount,
                    "lucky_number" => $luck,
                ]);
            }
        }

    }
}

