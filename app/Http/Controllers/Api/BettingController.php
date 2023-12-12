<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Agent;
use App\Helper\helper;
use App\Models\Betting;
use App\Models\Section;
use App\Models\UserBet;
use App\Invoker\invoke3D;
use App\Models\Betting1d;
use App\Models\Betting3d;
use App\Models\Section1d;
use App\Models\Section3d;
use App\Models\UserBet1d;
use App\Models\UserBet3d;
use App\Invoker\invokeAll;
use App\Models\Sectionc1d;
use App\Models\MasterAgent;
use Illuminate\Http\Request;
use App\Models\OverAllSetting;
use App\Models\BettingCrypto1d;
use App\Models\BettingCrypto2d;
use App\Models\SectionCrypto2d;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use App\Helper\searchAgentsModel;
use App\Models\CommissionHistory;
use App\Helper\percentAndCommission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class BettingController extends Controller
{
    public function betting(Request $request)
    {

        logger($request);
        $user = Auth::user();
        $timesection=date("H:i", strtotime($request->section));
        $over_all_setting = OverAllSetting::all();
        $get_latest_sec = Section::where("is_open", 1)->where('time_section', $timesection)->latest('id')->first();
        if (empty($get_latest_sec)) {
            return response()->json([
                'message' => 'wrong Section'
            ], 400);
          }
        $diffWithGMT=6*60*60+30*60; //myanmar time
        $dateFormat="H:i:s";
        $timeAndDate=gmdate($dateFormat, time()+$diffWithGMT);
       
          $two_d_odd = Section::where("is_open", 1)->where('time_section', $timesection)->first();
          $two_d_odd = $two_d_odd->odd;

        if ($get_latest_sec->close_time < $timeAndDate) {
            return response()->json([
                'message' => 'close betting'
            ], 400);
        }else{
            if ($user->balance > 0) {

                $date = Carbon::now();
                $req_time = strtotime($request->section);
                $cur_time = strtotime($date->toTimeString());
                $datetime = helper::currentDateTime();
    
                if ($req_time > $cur_time) {
    
                    $check_amount = 0;
                    foreach ($request->bet_obj as $value) {
                        $check_amount += $value['amount'];
                    }
    
                    if ($check_amount <= $user->balance) {
                        $user_bet = new UserBet();
                        $user_bet->user_id = $user->id;
                        $user_bet->section = $request->section;
                        $user_bet->date = Carbon::parse($datetime['date']);
    
                        if ($user_bet->save()) {
                            $total_amount = 0;
                            $bettings = $request->bet_obj;
                            foreach ($bettings as $key => $value) {
    
                                $betting = new Betting();
                                $betting->bet_id = $value['bet_id'];
                                $betting->bet_number = $value['bet_number'];
                                $betting->amount = $value['amount'];
                                $betting->odd = $two_d_odd;
                                $betting->category_id = $value['category_id'];
                                $betting->section = $value['section'];
                                $betting->date = Carbon::parse($datetime['date']);
                                $betting->save();
    
                                $user_bet->bettings()->attach($betting->id);
    
                                $total_amount += $betting->amount;
    
                                $user = User::find($user->id);
                                $user->balance = $user->balance - $betting->amount;
                                $user->update();
                            }
                            $user_bet = UserBet::findOrFail($user_bet->id);
                            $user_bet->total_amount = $total_amount;
                            $user_bet->total_bet = count($bettings);
                            $user_bet->update();
                        }
    
                        //referral commission
                        if ($user->agent_id) {
                            $type = '2D';
                            $agent = Agent::find($user->agent_id);
                            if (!empty($agent)) {
                                invokeAll::agent_referral_history($user->id, $agent->id, $total_amount, $request->section, $agent->twodpercent, $type);
                            }
                        }
    
                        //alert noti for all admin
                        $body = 'User Betting: Thai 2D';
                        invokeAll::adminAlertNoti($body);
    
                        return response()->json([
                            'result' => 1,
                            'status' => 200,
                            'message' => 'success',
                            'user_balance' => $user->balance
                        ]);
                    } else {
                        return response()->json([
                            'result' => 0,
                            'message' => 'not enough balance to bet'
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'result' => 0,
                        'message' => 'over section time!'
                    ], 400);
                }
            } else {
                return response()->json([
                    'result' => 0,
                    'message' => 'your balance is 0 Kyat!'
                ], 400);
            }

        }

        
    }

    public function betting_1d(Request $request)
    {
        $user = Auth::user();
        $timesection=date("H:i", strtotime($request->section));
        $over_all_setting = OverAllSetting::all();
        
        $get_latest_sec = Section1d::where("is_open", 1)->where('time_section', $timesection)->latest('id')->first();
        if (empty($get_latest_sec)) {
            return response()->json([
                'message' => 'wrong Section'
            ], 400);
          }
        $diffWithGMT=6*60*60+30*60; //myanmar time
        $dateFormat="H:i:s";
        $timeAndDate=gmdate($dateFormat, time()+$diffWithGMT);
       
      

        if ($get_latest_sec->close_time < $timeAndDate) {
            return response()->json([
                'message' => 'close betting'
            ], 400);
        }else{
            if ($user->balance > 0) {

                $date = Carbon::now();
                $req_time = strtotime($request->section);
                $cur_time = strtotime($date->toTimeString());
                $datetime = helper::currentDateTime();
    
                if ($req_time > $cur_time) {
    
                    $check_amount = 0;
                    foreach ($request->bet_obj as $value) {
                        $check_amount += $value['amount'];
                    }
    
                    if ($check_amount <= $user->balance) {
                        $user_bet = new UserBet1d();
                        $user_bet->user_id = $user->id;
                        $user_bet->section = $request->section;
                        $user_bet->date = Carbon::parse($datetime['date']);
    
                        if ($user_bet->save()) {
                            $total_amount = 0;
                            $bettings = $request->bet_obj;
                            foreach ($bettings as $key => $value) {
    
                                $betting = new Betting1d();
                                $betting->bet_id = $value['bet_id'];
                                $betting->bet_number = $value['bet_number'];
                                $betting->amount = $value['amount'];
                                $betting->odd = $over_all_setting[0]->over_all_odd_1d;
                                $betting->category_id = $value['category_id'];
                                $betting->section = $value['section'];
                                $betting->date = Carbon::parse($datetime['date']);
                                $betting->save();
    
                                $user_bet->bettings()->attach($betting->id);
    
                                $total_amount += $betting->amount;
    
                                $user = User::find($user->id);
                                $user->balance = $user->balance - $betting->amount;
                                $user->update();
                            }
                            $user_bet = UserBet1d::findOrFail($user_bet->id);
                            $user_bet->total_amount = $total_amount;
                            $user_bet->total_bet = count($bettings);
                            $user_bet->update();
                        }
    
                        //referral commission
                        if ($user->agent_id) {
                            $type = '2D';
                            $agent = Agent::find($user->agent_id);
                            if (!empty($agent)) {
                                invokeAll::agent_referral_history($user->id, $agent->id, $total_amount, $request->section, $agent->twodpercent, $type);
                            }
                        }
    
                        //alert noti for all admin
                        $body = 'User Betting: Thai 2D';
                        invokeAll::adminAlertNoti($body);
    
                        return response()->json([
                            'result' => 1,
                            'status' => 200,
                            'message' => 'success',
                            'user_balance' => $user->balance
                        ]);
                    } else {
                        return response()->json([
                            'result' => 0,
                            'message' => 'not enough balance to bet'
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'result' => 0,
                        'message' => 'over section time!'
                    ], 400);
                }
            } else {
                return response()->json([
                    'result' => 0,
                    'message' => 'your balance is 0 Kyat!'
                ], 400);
            }

        }

        
    }

    public function betting_c2d(Request $request)
    {
        $user = Auth::user();
        $over_all_setting = OverAllSetting::all();
        $timesection=date("H:i", strtotime($request->section));
        // $get_latest_sec = SectionCrypto2d::latest('id')->first();
        $get_latest_sec = SectionCrypto2d::where('time_section', $timesection)->latest('id')->first();
        if (empty($get_latest_sec)) {
            return response()->json([
                'message' => 'wrong Section'
            ], 400);
          }
        $diffWithGMT=6*60*60+30*60; //myanmar time
        $dateFormat="H:i:s";
        $timeAndDate=gmdate($dateFormat, time()+$diffWithGMT);

        if ($get_latest_sec->close_time < $timeAndDate) {
            return response()->json([
                'message' => 'close betting'
            ], 400);
        }

        if ($user->balance > 0) {
            $date = Carbon::now();
            $req_time = strtotime($request->section);
            $cur_time = strtotime($date->toTimeString());
            $datetime = helper::currentDateTime();

            if ($req_time > $cur_time) {

                $check_amount = 0;
                foreach ($request->bet_obj as $value) {
                    $check_amount += $value['amount'];
                }

                if ($check_amount <= $user->balance) {
                    $user_bet = new UserBetCrypto2d();
                    $user_bet->user_id = $user->id;
                    $user_bet->section = $request->section;
                    $user_bet->date = Carbon::parse($datetime['date']);

                    if ($user_bet->save()) {
                        $total_amount = 0;
                        $bettings = $request->bet_obj;
                        foreach ($bettings as $key => $value) {

                            $betting = new BettingCrypto2d();
                            $betting->bet_id = $value['bet_id'];
                            $betting->bet_number = $value['bet_number'];
                            $betting->amount = $value['amount'];
                            $betting->odd = $over_all_setting[0]->crypto_2d_odd;
                            $betting->category_id = $value['category_id'];
                            $betting->section = $value['section'];
                            $betting->date = Carbon::parse($datetime['date']);
                            $betting->save();

                            $user_bet->bettings_c2d()->attach($betting->id);

                            $total_amount += $betting->amount;

                            $user = User::find($user->id);
                            $user->balance = $user->balance - $betting->amount;
                            $user->update();
                        }
                        $user_bet = UserBetCrypto2d::findOrFail($user_bet->id);
                        $user_bet->total_amount = $total_amount;
                        $user_bet->total_bet = count($bettings);
                        $user_bet->update();
                    }

                    //referral commission
                    if ($user->agent_id) {
                        $type = 'c2D';
                        $agent = Agent::find($user->agent_id);
                        if (!empty($agent)) {
                            invokeAll::agent_referral_history($user->id, $agent->id, $total_amount, $request->section, $agent->cryptonpercent, $type);
                        }
                    }
                    //alert noti for all admin
                    $body = 'User Betting: Crypto 2D';
                    invokeAll::adminAlertNoti($body);
                    return response()->json([
                        'result' => 1,
                        'status' => 200,
                        'message' => 'success',
                        'user_balance' => $user->balance
                    ]);
                } else {
                    return response()->json([
                        'result' => 0,
                        'message' => 'not enough balance to bet'
                    ], 400);
                }
            } else {
                return response()->json([
                    'result' => 0,
                    'message' => 'over section time!'
                ], 400);
            }
        } else {
            return response()->json([
                'result' => 0,
                'message' => 'your balance is 0 Kyat!'
            ], 400);
        }
    }

    public function betting_c1d(Request $request)
    {
        $user = Auth::user();
        $over_all_setting = OverAllSetting::all();
        $timesection=date("H:i", strtotime($request->section));
        // $get_latest_sec = SectionCrypto2d::latest('id')->first();
        $get_latest_sec = Sectionc1d::where("is_open", 1)->where('time_section', $timesection)->latest('id')->first();
        if (empty($get_latest_sec)) {
            return response()->json([
                'message' => 'wrong Section'
            ], 400);
          }
        $diffWithGMT=6*60*60+30*60; //myanmar time
        $dateFormat="H:i:s";
        $timeAndDate=gmdate($dateFormat, time()+$diffWithGMT);

        if ($get_latest_sec->close_time < $timeAndDate) {
            return response()->json([
                'message' => 'close betting'
            ], 400);
        }

        if ($user->balance > 0) {
            $date = Carbon::now();
            $req_time = strtotime($request->section);
            $cur_time = strtotime($date->toTimeString());
            $datetime = helper::currentDateTime();

            if ($req_time > $cur_time) {

                $check_amount = 0;
                foreach ($request->bet_obj as $value) {
                    $check_amount += $value['amount'];
                }

                if ($check_amount <= $user->balance) {
                    $user_bet = new UserBetCrypto1d();
                    $user_bet->user_id = $user->id;
                    $user_bet->section = $request->section;
                    $user_bet->date = Carbon::parse($datetime['date']);

                    if ($user_bet->save()) {
                        $total_amount = 0;
                        $bettings = $request->bet_obj;
                        foreach ($bettings as $key => $value) {

                            $betting = new BettingCrypto1d();
                            $betting->bet_id = $value['bet_id'];
                            $betting->bet_number = $value['bet_number'];
                            $betting->amount = $value['amount'];
                            $betting->odd = $over_all_setting[0]->crypto_1d_odd;
                            $betting->category_id = $value['category_id'];
                            $betting->section = $value['section'];
                            $betting->date = Carbon::parse($datetime['date']);
                            $betting->save();

                            $user_bet->bettings()->attach($betting->id);

                            $total_amount += $betting->amount;

                            $user = User::find($user->id);
                            $user->balance = $user->balance - $betting->amount;
                            $user->update();
                        }
                        $user_bet = UserBetCrypto1d::findOrFail($user_bet->id);
                        $user_bet->total_amount = $total_amount;
                        $user_bet->total_bet = count($bettings);
                        $user_bet->update();
                    }

                    //referral commission
                    if ($user->agent_id) {
                        $type = 'c2D';
                        $agent = Agent::find($user->agent_id);
                        if (!empty($agent)) {
                            invokeAll::agent_referral_history($user->id, $agent->id, $total_amount, $request->section, $agent->cryptonpercent, $type);
                        }
                    }
                    //alert noti for all admin
                    $body = 'User Betting: Crypto 1D';
                    invokeAll::adminAlertNoti($body);
                    return response()->json([
                        'result' => 1,
                        'status' => 200,
                        'message' => 'success',
                        'user_balance' => $user->balance
                    ]);
                } else {
                    return response()->json([
                        'result' => 0,
                        'message' => 'not enough balance to bet'
                    ], 400);
                }
            } else {
                return response()->json([
                    'result' => 0,
                    'message' => 'over section time!'
                ], 400);
            }
        } else {
            return response()->json([
                'result' => 0,
                'message' => 'your balance is 0 Kyat!'
            ], 400);
        }
    }

    public function betting_3d(Request $request)
    {
        $user = Auth::user();
        $over_all_setting = OverAllSetting::all();
        $datetime = helper::currentDateTime();
        $sections = Section3d::pluck('date');
        $close_times = Section3d::pluck('close_time');
        $now_date = date('d', strtotime($datetime['date']));

        $diffWithGMT=6*60*60+30*60; //myanmar time
        $dateFormat="H:i:s";
        $timeAndDate=gmdate($dateFormat, time()+$diffWithGMT);

        // Checking 3d close section
        if ($sections[0] == $now_date || $sections[1] == $now_date) {
            if ($sections[0] == $now_date) {
                if ($close_times[0] < $timeAndDate) {
                    return response()->json([
                        'message' => 'close betting'
                    ], 400);
                }
            } else {
                if ($close_times[1] < $timeAndDate) {
                    return response()->json([
                        'message' => 'close betting'
                    ], 400);
                }
            }
        }
        if ($user->balance > 0) {
            $check_amount = 0;
            foreach ($request->bet_obj as $value) {
                $check_amount += $value['amount'];
            }

            if ($check_amount <= $user->balance) {

                $bet_date = invoke3D::betDate3d($datetime['date']);
                $date3d = str_pad((string)substr($bet_date, 8), 2, '0', STR_PAD_LEFT);
                $user_bet = new UserBet3d();
                $user_bet->user_id = $user->id;
                $user_bet->date_3d = $date3d;
                $user_bet->section = $request->section;
                $user_bet->date = Carbon::parse($datetime['date']);
                $user_bet->bet_date = $bet_date;

                if ($user_bet->save()) {
                    $total_amount = 0;
                    $bettings = $request->bet_obj;
                    foreach ($bettings as $key => $value) {

                        $betting = new Betting3d();
                        // $betting->bet_id = $value['bet_id'];
                        $betting->bet_number = $value['bet_number'];
                        $betting->amount = $value['amount'];
                        $betting->odd = $over_all_setting[0]->odd_3d;
                        $betting->tot_odd = $over_all_setting[0]->tot_3d;
                        $betting->category_id = $value['category_id'];
                        $betting->date_3d = $date3d;
                        $betting->section = $value['section'];
                        $betting->date = Carbon::parse($datetime['date']);
                        $betting->bet_date = $bet_date;
                        $betting->save();

                        $user_bet->bettings_3d()->attach($betting->id);

                        $total_amount += $betting->amount;

                        $user = User::find($user->id);
                        $user->balance = $user->balance - $betting->amount;
                        $user->update();
                    }
                    $user_bet = UserBet3d::findOrFail($user_bet->id);
                    $user_bet->total_amount = $total_amount;
                    $user_bet->total_bet = count($bettings);
                    $user_bet->update();
                }

                //referral commission
                if ($user->referral_id) {
                    $type = '3D';
                    invokeAll::user_referral_history_3d($user->id, $user->referral_id, $total_amount, $date3d, $over_all_setting[0]->referral_3d, $type, $bet_date);
                }

                //alert noti for all admin
                $body = 'User Betting: Thai 3D';
                invokeAll::adminAlertNoti($body);

                return response()->json([
                    'result' => 1,
                    'status' => 200,
                    'message' => 'success',
                    'user_balance' => $user->balance
                ]);
            } else {
                return response()->json([
                    'result' => 0,
                    'message' => 'not enough balance to bet'
                ], 400);
            }
        } else {
            return response()->json([
                'result' => 0,
                'message' => 'your balance is 0 Kyat!'
            ], 400);
        }
    }
}
