<?php

namespace App\Invoker;

use App\Models\User;
use App\Models\SpinWheel;
use App\Models\PromoBonus;
use App\Models\ActivityLog;
use App\Models\PromoCashIn;
use App\Models\LotteryOffDay;
use App\Models\SpinWheelUser;
use App\Models\SpinWheelRange;
use App\Models\UserReferHistory;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FirebaseController;
use App\Models\PromotionLog;
use App\Models\SuperAdmin;

class invokeAll
{

    public static function generateReferralCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code . $character;
        }

        if (User::where('referral', $code)->exists()) {
            return (new self)->generateReferralCode();
        }

        return $code;
    }

    public static function user_referral_history($user_id, $referral_id, $total_amount, $section, $over_all_referral, $type)
    {
        $amount = ($total_amount * $over_all_referral) / 100;
        $refer_history = new UserReferHistory();
        $refer_history->user_id = $user_id;
        $refer_history->referral_id = $referral_id;
        $refer_history->amount = $amount;
        $refer_history->section = $section;
        $refer_history->type = $type;
        $refer_history->save();

        $user = User::find($referral_id);
        $user->balance = $user->balance + $refer_history->amount;
        $user->update();
    }

    public static function user_referral_history_3d($user_id, $referral_id, $total_amount, $section, $over_all_referral, $type, $bet_date_3d)
    {
        $amount = ($total_amount * $over_all_referral) / 100;
        $refer_history = new UserReferHistory();
        $refer_history->user_id = $user_id;
        $refer_history->referral_id = $referral_id;
        $refer_history->amount = $amount;
        $refer_history->section = $section;
        $refer_history->type = $type;
        $refer_history->bet_date_3d = $bet_date_3d;
        $refer_history->save();

        $user = User::find($referral_id);
        $user->balance = $user->balance + $refer_history->amount;
        $user->update();
    }

    public static function winLuckyNumberNoti($user_id, $lucky_number)
    {
        $firebaseToken = User::where('id', $user_id)->pluck('device_token')->all();
        if ($firebaseToken !== null) {
            $SERVER_API_KEY = 'AAAAY9kKSiQ:APA91bFLTAiseWMlnFx4Zyuyp0WTjthUQsXq54v4sfdM8nUpPh2i2Q0Cz7BK2c_zugvoCvzZFkRxxXvV_RM08yVluZtxQefa4n7KbvKVoDDsYPrwOosrmHmlkAgpFS1hd05qkUumPr87';

            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => 'MYVIP',
                    "body" => 'Congratulation! Lucky number is ' . $lucky_number,
                    "icon" => asset("backend/noti.png"),
                ],
                "data" => [
                    "title" => 'MYVIP',
                    "body" =>  'Congratulation! Lucky number is ' . $lucky_number,
                    "icon" => asset("backend/noti.png"),
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
        }
    }

    public static function activityCheck($url_path)
    {
        $log_id = Auth::guard('super_admin')->user()->id;
        $log_name = $url_path;

        if ($log_name == 'super_admin/dashboard') {
            $log_name = '2D Dashboard';
        }

        if ($log_name == 'super_admin/dashboard_3d') {
            $log_name = '3D Dashboard';
        }

        if ($log_name == 'game/dashboard_game') {
            $log_name = 'Game Dashboard';
        }

        $activity = new ActivityLog();
        $activity->super_admin_id = $log_id;
        $activity->log_name = $log_name;
        $activity->save();
    }

    public static function spinWheelPercentRate()
    {
        $wheels = SpinWheel::get(['id', 'percent'])->toArray();
        $dist = collect($wheels);

        $rand  = rand(1, $dist->sum('percent'));
        $accum = 0;
        $idx   = -1;
        $dist->each(function ($d, $k) use (&$accum, &$idx, $rand) {
            $idx   = $k;
            $accum += $d['percent'];
            if ($accum >= $rand) {
                return false;
            }
        });

        return $dist[$idx];
    }

    public static function spinWheelUserCount($user_id, $amount)
    {
        $check_user = SpinWheelUser::where('user_id', $user_id)->first();

        $lowest = SpinWheelRange::orderBy('start_amount')->first();
        $highest = SpinWheelRange::orderBy('end_amount', 'DESC')->first();
        $range = SpinWheelRange::where('start_amount', '<=', $amount)->where('end_amount', '>=', $amount)->first();

        if ($lowest || $highest || $range) {
            if ($lowest->start_amount > $amount) {
                $count = 0;
            } elseif ($highest->end_amount < $amount) {
                $count = $highest->count;
            } else {
                $count = $range->count;
            }

            if (!$check_user) {
                $s_w_user = new SpinWheelUser();
                $s_w_user->user_id = $user_id;
                $s_w_user->count = $count;
                $s_w_user->update_time = date('Y-m-d-H:i:s');
                $s_w_user->save();
                return true;
            }

            $check_user->count = $check_user->count + $count;
            $check_user->update_time = date('Y-m-d-H:i:s');
            $check_user->update();
        }
    }

    public static function holidayCheck()
    {
        $day = strtolower(date('l'));
        $lot_off = LotteryOffDay::where('category_id', 1)->whereDate('off_day', date('Y-m-d'))->first();

        if ($day == 'sunday' ||  $day == 'saturday') {
            $check = 0;
        } elseif ($lot_off) {
            $check = 0;
        } else {
            $check = 1;
        }

        return $check;
    }

    public static function holidayCheckC2D()
    {
        // Category_id: 3, Crypto 2D
        $lot_off = LotteryOffDay::where('category_id', 3)->whereDate('off_day', date('Y-m-d'))->first();

        if ($lot_off) {
            $check = 0;
        } else {
            $check = 1;
        }

        return $check;
    }

    public static function promoTurnover($cash_in)
    {
        $user_id = $cash_in->user_id;
        $promo_id = $cash_in->promo_id;
        $cash = $cash_in->amount;

        $get_promo = PromoCashIn::find($promo_id);
        if (empty($cash_in->payment_method->exchange_rate)) {
            $game_balance = (($cash * $get_promo->percent) / 100) + $cash;
        } else {
            // for thai banking
            $thai_cash = $cash * $cash_in->payment_method->exchange_rate;
            $game_balance = (($thai_cash * $get_promo->percent) / 100) + $thai_cash;
        }
        $promo_balance = (int)$game_balance;
        $turnover = $game_balance * $get_promo->turnover;
        $update_time = date('Y-m-d H:i:s');

        $user = User::where('id', $user_id)->first();

        $get_balance = new FirebaseController();
        $user_balance = $get_balance->getValue($user_id);
        $provider = $user_balance['last_provider'];
        $referenceid = date("YmdHis") . rand(111111, 999999);

        $array = transferFund([
            "provider" => $provider,
            "username" => $user->user_code,
            "password" => "alexaung57",
            "referenceid" => $referenceid,
            "type" => "0",
            "amount" => $promo_balance
        ]);

        $lastbalance = getbalance($provider, $user->user_code, 'alexaung57');
        $get_balance->updateProvider($provider, $user_id, $lastbalance['balance']);

        $tran_in = new PromotionLog();
        $tran_in->user_id = $user_id;
        $tran_in->balance = $user->balance;
        $tran_in->game_balance = $lastbalance['balance'];
        $tran_in->transfer_balance = $promo_balance;
        $tran_in->referenceid = $referenceid;
        $tran_in->message = 'success';
        $tran_in->error_code = $array;
        $tran_in->save();

        $user->turnover = $turnover;
        $user->update();
    }

    public static function adminAlertNoti($body)
    {
        $firebaseToken = SuperAdmin::whereNotNull("device_token")
            ->pluck('device_token')
            ->all();
        if ($firebaseToken !== null) {
            foreach ($firebaseToken as $index => $token) {
                if (!empty($token)) {
                    $firebaseToken[$index] = json_decode($token);
                }
            }
            $SERVER_API_KEY = 'AAAAY9kKSiQ:APA91bFLTAiseWMlnFx4Zyuyp0WTjthUQsXq54v4sfdM8nUpPh2i2Q0Cz7BK2c_zugvoCvzZFkRxxXvV_RM08yVluZtxQefa4n7KbvKVoDDsYPrwOosrmHmlkAgpFS1hd05qkUumPr87';

            $data = [
                "registration_ids" => array_merge(...$firebaseToken),
                "notification" => [
                    "title" => 'MYVIP',
                    "body" => $body,
                    "icon" => asset("backend/noti.png"),
                ],
                "data" => [
                    "title" => 'MYVIP',
                    "body" =>  $body,
                    "icon" => asset("backend/noti.png"),
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
        }
    }

    public static function userAlertNoti($title, $body, $user_id)
    {
        $firebaseToken = User::where('id', $user_id)->pluck('device_token')->all();
        if ($firebaseToken !== null) {
            $SERVER_API_KEY = 'AAAAY9kKSiQ:APA91bFLTAiseWMlnFx4Zyuyp0WTjthUQsXq54v4sfdM8nUpPh2i2Q0Cz7BK2c_zugvoCvzZFkRxxXvV_RM08yVluZtxQefa4n7KbvKVoDDsYPrwOosrmHmlkAgpFS1hd05qkUumPr87';

            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                    "icon" => asset("backend/noti.png"),
                ],
                "data" => [
                    "title" => $title,
                    "body" =>  $body,
                    "icon" => asset("backend/noti.png"),
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
        }
    }
}
