<?php

namespace App\Http\Controllers\Api;

use App\Msg;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\UserBet;
use App\Models\OtpRecord;
use App\Helper\checkPhone;
use App\Invoker\invokeAll;
use App\Models\UserLevel2;
use App\Models\VersionKey;
use App\Models\LuckyNumber;
use App\Models\GameProvider;
use Illuminate\Http\Request;
use App\Models\BettingHistory;
use App\Models\OverAllSetting;
use App\Models\UserReferHistory;
use Illuminate\Support\Facades\DB;
use CasinoGames\Facade\CasinoGames;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\ClaimGamerReferHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\Api\GameController;
use Illuminate\Support\Facades\Log;
use PyaeSoneAung\MyanmarPhoneValidationRules\MyanmarPhone;


class UserController extends Controller
{
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', new MyanmarPhone],
            'title' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'  => false,
                'message' => 'Wrong phone number'
            ], 400);
        }

        $del_otp = OtpRecord::where('otp_phone', $request->phone)->delete();

        $otp = rand(100000, 999999);
        $message = "$request->title: $otp";

        $Msg = new Msg();
        $msgResponse = $Msg->sendSMS($request->phone, $message);

        if ($msgResponse['error']) {
            $response['message'] = 'Fail OTP!';
            $response['success'] = false;
        } else {
            $create_otp = new OtpRecord();
            $create_otp->otp = $otp;
            $create_otp->otp_time = time();
            $create_otp->otp_phone = $request->phone;
            $create_otp->save();

            $response['message'] = 'OTP Sent';
            $response['success'] = true;
        }

        return response()->json($response);
    }

    public function verify_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required'],
            'phone' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'  => false,
                'message' => 'Wrong phone number'
            ], 400);
        } else {
            $otp = OtpRecord::where('otp_phone', $request->phone)->first();
            if ($otp->otp == $request->code) {
                User::where('phone', $request->phone)->update(['isVerified' => 1]);
                $response['error'] = 0;
                $response['isVerified'] = 1;
                $response['loggedIn'] = 1;
                $response['message'] = "Your Number is Verified.";
            } else {
                $response['error'] = 1;
                $response['isVerified'] = 0;
                $response['loggedIn'] = 1;
                $response['message'] = "OTP does not match.";
            }
            return json_encode($response);
        }
    }

     

    public function verify_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', new MyanmarPhone],
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success'  => false,
                'message' => 'Wrong phone number'
            ], 400);
        }

        // phone number unique
        $phones = User::pluck('phone')->toarray();
        $reqphone=$request->phone;
        if (in_array($reqphone, $phones,TRUE)){
            return response()->json([
                'success'  => false,
                'message' => 'phone number already exit'
            ], 400);
         }
        if (checkPhone::number() == 0) {

            if ($request->referral) {
                $refer_check = User::where('referral', $request->referral)->first();
                if ($refer_check) {
                    $referral_id = $refer_check->id;
                } else {
                    return response()->json([
                        'message' => 'referral not correct'
                    ], 400);
                }
            }
            $welcome_bonus = Setting::where('key', 'welcome_bonus')->first();
            $user_code = substr(md5($request->phone), 0, 10);
            $referral = invokeAll::generateReferralCode();
            $user = new User();
            $user->super_admin_id = 1;
            $user->name = $request->name;
            $user->user_code = $user_code;
            $user->balance = $welcome_bonus->value;
            $user->referral = $referral;
            if ($request->referral) {
                $user->referral_id = $referral_id;
                //for refer count
                $user_refer = User::find($referral_id);
                $user_refer->refer_count = $user_refer->refer_count + 1;
                $user_refer->update();
            }
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            if($request->side){
                $user->side = $request->side;
            }

            create_table($user->user_code);
            $user->save();

            $user_info = User::findOrFail($user->id);

            $token = $user->createToken('register_token')->accessToken;

            //alert noti for all admin
            $body = 'New User Registered';
            invokeAll::adminAlertNoti($body);

            return response()->json([
                'status' => 200,
                'user_info' => $user_info,
                'token' => $token,
                'message' => 'Create user successfully'
            ]);
        } else {
            return response()->json([
                'result' => 0,
                'status' => 400,
                'message' => 'Phone number already exit!'
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $phone = User::where('phone', $request->phone)->first();

        if ($phone) {
            if ($phone->block == 0) {
                if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
                    $user_id = Auth::user()->id;
                    $user = User::findOrFail($user_id);

                    $tokens = DB::table('oauth_access_tokens')->where('user_id', $user_id)->delete();

                    $token = $user->createToken('auth_token')->accessToken;
                    $token_id = $user->createToken('auth_token');
                    // game balance
                    return response()->json([
                        'result' => 1,
                        'status' => 200,
                        'message' => 'success',
                        'data' => $user,
                        'token' => $token,
                        'token_id' => $token_id->token->id,
                    ]);
                } else {
                    return response()->json([
                        'result' => 0,
                        'status' => 401,
                        'message' => 'Password is incorrect!'
                    ], 401);
                }
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'Block User!'
                ], 403);
            }
        } else {
            return response()->json([
                'result' => 0,
                'status' => 401,
                'message' => 'This number not register'
            ], 401);
        }
    }

    public function profile()
    {
        $user_id = Auth::user()->id;
        $iniuser = User::findOrFail($user_id);
        
        $user = User::findOrFail($user_id);

        if ($user->block == 1) {
            return response()->json([
                'message' => 'Block User!'
            ], 401);
        }

        if (!$user->image) {
            $image = 'https://ui-avatars.com/api/?size=128';
        } else {
            $image = $user->image;
        }

        return response()->json([
            'result' => 1,
            'status' => 200,
            'message' => 'success',
            'image' => $image,
            'data' => $user
        ]);
    }

    public function new_password(Request $request)
    {
        $this->validate($request, [
            'old_password' => ['required'],
            'password' => ['required'],
        ]);

        $id = Auth::user()->id;
        $old_password = User::findOrFail($id)->password;
        if (Hash::check($request->old_password, $old_password)) {

            $request->password = $request->password ? Hash::make($request->password) : $old_password;
            $user = User::findOrFail($id);
            $user->password = $request->password;
            $user->update();

            return response()->json([
                'result' => 1,
                'status' => 200,
                'message' => 'success'
            ]);
        } else {
            return response()->json([
                'result' => 1,
                'status' => 400,
                'message' => 'your password is incorrect'
            ], 400);
        }
    }

    public function profile_update(Request $request)
    {
        $request->validate([
            'phone' => ['nullable', new MyanmarPhone],
        ]);

        $id = Auth::user()->id;
        $user = User::findOrFail($id);

        //name
        $user->name = $request->name ? $request->name : $user->name;

        //phone number
        if ($request->phone) {
            if (checkPhone::number() == 0) {
                //password check
                if (Hash::check($request->password, $user->password)) {
                    $user->phone = $user->phone;
                } else {
                    return response()->json([
                        'result' => 1,
                        'status' => 400,
                        'message' => 'your password is incorrect'
                    ], 400);
                }
            } else {
                return response()->json([
                    'result' => 0,
                    'status' => 400,
                    'message' => 'Phone number already exit!'
                ], 400);
            }
        } else {
            $user->phone = $user->phone;
        }

        //image
        if ($request->hasFile('image')) {
            Storage::delete($user->image);
            $user->image =  $request->file('image') ? $request->file('image')->store('user') : $user->image;
        } else {
            $user->image = $user->image;
        }

        //email
        if ($request->email) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'email is unique or correct format'
                ], 400);
            }
            $user->email = $request->email;
        }

        //birthday
        if ($request->birthday) {
            $user->birthday = $request->birthday;
        }

        //referral
        if ($request->referral_id) {
            
            if (!$user->referral_id) {
                $refer_check = User::where('referral', $request->referral_id)->first();
                if ($refer_check) {
                    $user->referral_id = $refer_check->id;

                    //for refer count
                    $user_refer = User::find($refer_check->id);
                    $user_refer->refer_count = $user_refer->refer_count + 1;
                    $user_refer->update();
                } else {
                    return response()->json([
                        'message' => 'referral not correct'
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'already used referral'
                ], 400);
            }
        }

        //lvl2
        if ($request->image_lvl_2) {
            $validator = Validator::make($request->all(), [
                'image_lvl_2' => 'required|mimes:jpeg,png,jpg',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success'  => false,
                ], 400);
            }

            $check = UserLevel2::where('user_id', $id)->first();

            if ($check) {
                if ($check->status == 'approve') {
                    return response()->json([
                        'success'  => 'you are level 2 user',
                    ], 400);
                }
                if ($check->status == 'pending') {
                    return response()->json([
                        'success'  => 'please wait admin approve',
                    ], 400);
                }
                if ($check->status == 'reject') {
                    $lvl_2 = UserLevel2::find($check->id);
                    $lvl_2->user_id = $id;
                    Storage::delete($check->image);
                    $lvl_2->image =  $request->file('image_lvl_2')->store('user');
                    $lvl_2->status = 'pending';
                    $lvl_2->update();
                    return response()->json([
                        'success'  => 'please wait admin approve',
                    ]);
                }
            }

            if ($request->hasFile('image_lvl_2')) {
                $lvl_2 = new UserLevel2();
                $lvl_2->user_id = $id;
                $lvl_2->image = $request->file('image_lvl_2')->store('user');
                $lvl_2->status = 'pending';
                $lvl_2->save();
            }
            return response()->json([
                'success'  => 'thanks for your update',
            ]);
        }

        $user->update();

        return response()->json([
            'result' => 1,
            'status' => 200,
            'message' => 'success'
        ]);
    }

    // public function logout()
    // {
    //     Auth::user()->token()->revoke();
    //     return response()->json([
    //         'result' => 1,
    //         'status' => 200,
    //         'message' => 'success'
    //     ]);
    // }

    public function block()
    {
        $user = Auth::user();
        if ($user->block == 0) {
            return response()->json([
                'block' => $user->block
            ], 200);
        } else {
            return response()->json([
                'block' => $user->block
            ], 403);
        }
    }

    public function win_message(Request $request, $id)
    {
        $date = $request->query('date');
        $time = $request->query('time');
        $bet = UserBet::where('date', $date)
            ->where('section', $time)
            ->where('user_id', $id)
            ->where('win', 1)
            ->where('noti_on', 0)->first();

        $lucky = LuckyNumber::where('create_date', $date)->where('section', $time)->first();

        if (!empty($bet)) {
            return [
                "user_id" => 1,
                "status" => 1,
                "bet_id" => $bet->id,
                "lucky_number" => $lucky->lucky_number,
            ];
        }

        return [];
    }

    public function noti_status($bet_id)
    {
        $status = UserBet::where('id', $bet_id)->first();
        $status->noti_on = 1;
        $status->save();

        return response()->json([
            "message" => "success"
        ]);
    }

    public function getToken(Request $request)
    {
        $get_token = DB::table('oauth_access_tokens')->where('id', $request->token)->get();
        if (count($get_token) > 0) {
            return ['token' => 'yes'];
        } else {
            return ['token' => 'no'];
        }
    }

    public function forget_password(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $user = User::findOrFail($user->id);
            $user->password = Hash::make($request->password);
            $user->update();
            return response()->json([
                "message" => "success"
            ]);
        } else {
            return response()->json([
                "message" => "Incorrect phone number"
            ], 400);
        }
    }

    public function lvl_2(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'  => false,
            ], 400);
        }

        $id = Auth::user()->id;

        $check = UserLevel2::where('user_id', $id)->first();

        if ($check) {
            if ($check->status == 'approve') {
                return response()->json([
                    'success'  => 'you are level 2 user',
                ], 400);
            }
            if ($check->status == 'pending') {
                return response()->json([
                    'success'  => 'please wait admin approve',
                ], 400);
            }
            if ($check->status == 'reject') {
                $lvl_2 = UserLevel2::find($check->id);
                $lvl_2->user_id = $id;
                Storage::delete($check->image);
                $lvl_2->image =  $request->file('image')->store('user');
                $lvl_2->status = 'pending';
                $lvl_2->update();
                return response()->json([
                    'success'  => 'please wait admin approve',
                ]);
            }
        }

        if ($request->hasFile('image')) {
            $lvl_2 = new UserLevel2();
            $lvl_2->user_id = $id;
            $lvl_2->image = $request->file('image')->store('user');
            $lvl_2->status = 'pending';
            $lvl_2->save();
        }
        return response()->json([
            'success'  => 'thanks for your update',
        ]);
    }

    public function check_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'real phone number required'
            ], 400);
        }
        $phone = User::where('phone', $request->phone)->first();
        if ($phone) {
            return response()->json([
                'success'  => 'phone number already exit',
            ], 200);
        } else {
            return response()->json([
                'success'  => 'ok',
            ], 404);
        }
    }

    public function my_referral_his(Request $request)
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

        if ($request->type) {
            $type = $request->type;
        } else {
            $type = '2D';
        }

        $id = Auth::user()->id;
        $get = UserReferHistory::with(['user', 'ref_user'])
            ->where('referral_id', $id)
            ->where('type', $type)
            ->whereBetween('created_at', [$start . " 00:00:00", $end . " 23:59:59"])
            ->get(['amount', 'section', 'bet_date_3d', 'referral_id', 'user_id', 'created_at']);

        $total_amount = UserReferHistory::with(['user', 'ref_user'])
            ->where('referral_id', $id)
            ->where('type', $type)
            ->whereBetween('created_at', [$start . " 00:00:00", $end . " 23:59:59"])
            ->sum('amount');

        return response()->json([
            'total_amount' => number_format((float)$total_amount, 2, '.', ''),
            'data' => $get,
        ]);
    }

    public function mark_histories()
    {
        $tickets = "";
        $version = VersionKey::where('id', 1)->first();
        if (!empty($version)) {
            $v = $version->version_key;
        } else {
            $v = '0';
        }
        $url = 'http://gslog.336699bet.com/fetchbykey.aspx?operatorcode=icmk&versionkey=' . $v . '&signature=6EE46D66A7AC741626DEF1E03C75A295';
        $response = Http::get($url);
        $jsonData = $response->json();
      
       
       
        $find = VersionKey::where('id', 1)->first();
       
        $json = json_decode($jsonData['result'], true);
      
       
        if ($jsonData['errMsg'] == "SUCCESS") {
            foreach ($json as $datum) {
                if ($tickets == "") {
                    $tickets .= strval($datum['id']);
                } else {
                    $tickets .= ',' . strval($datum['id']);
                }

                //Start: Betting History Save to DB
                $provider = GameProvider::where('p_code', $datum['site'])->first();
                $new_history = new BettingHistory();
                $new_history->username = $datum['member'];
                $new_history->p_win = $datum['p_win'];
                $new_history->p_share = $datum['p_share'];
                $new_history->turnover = round($datum['turnover'], 2);
                $new_history->game_id = $datum['game_id'];
                $new_history->gamename = $datum['bet_detail'];
                $new_history->bet = round($datum['bet'], 2);
                $new_history->provider_name = $provider->name;
                $new_history->payout = round($datum['payout'], 2);
                $new_history->commission = round($datum['commission'], 2);
                $new_history->status = $datum['status'];
                $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT: 6:30 ( Myanmar Time Zone )
                $new_history->date = date('Y-m-d', strtotime($datum['start_time']) + $diffWithGMT);
                $new_history->start_time = $datum['start_time'];
                $new_history->match_time = $datum['match_time'];
                $new_history->end_time = $datum['end_time'];
                $new_history->p_code = $datum['site'];
                $new_history->p_type = $datum['product'];
                $new_history->save();
                // End

                //for promo turnover
                $user = User::where('user_code', $new_history->username)->first();
                if (!empty($user)) {
                    if ($user->turnover > 0) {
                        $promo_turn = $user->turnover - $new_history->turnover;
                        if ($promo_turn > 0) {
                            $user->turnover = $promo_turn;
                        } else {
                            $user->turnover = 0;
                        }
                        $user->update();
                    }

                    //for game referral amount
                    if ($user->referral_id) {

                        $game_profit = $new_history->payout - $new_history->bet;

                        $setting = OverAllSetting::first();
                        $game_refer = $setting->game_refer;
                        $refer_amt = ($game_profit * $game_refer) / 100;
                        $refer_amt = $refer_amt * (-1);

                        $user_game_refer = User::find($user->referral_id);
                        $user_game_refer->game_refer_amt = $user_game_refer->game_refer_amt + ($refer_amt);
                        $user_game_refer->update();
                    }
                }
            }

            $markurl = 'http://gslog.336699bet.com/markbyjson.aspx';
            $response = Http::post($markurl, [
                "ticket" => $tickets,
                "operatorcode" => "icmk",
                "signature" => "6EE46D66A7AC741626DEF1E03C75A295"
            ]);
            if (empty($find)) {
                $new_version_key = new VersionKey();
                $new_version_key->version_key = $jsonData['lastversionkey'];
                $new_version_key->save();
            } else {
                $find->version_key = $jsonData['lastversionkey'];
                $find->save();
            }

            return $response;
        }
    }

    public function claim_game_refer_amt()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $game_ref = $user->game_refer_amt;

        if ($game_ref <= 0) {
            return response()->json([
                'message' => 'Game Refer is transfer more than 0',
            ], 400);
        }

        $user->game_refer_amt = $user->game_refer_amt - $game_ref;
        $user->balance = $user->balance + $game_ref;
        $user->update();

        $refer_hist = new ClaimGamerReferHistory();
        $refer_hist->user_id = $id;
        $refer_hist->amount = $game_ref;
        $refer_hist->save();

        return response()->json([
            'message' => $game_ref . ' Transfer To Main Wallet',
        ], 200);
    }
}