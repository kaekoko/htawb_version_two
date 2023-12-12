<?php

namespace App\Helper;

use Carbon\Carbon;
use App\Models\OneD;
use App\Models\TwoD;
use App\Models\User;
use App\Models\Agent;
use App\Models\CashIn;
use App\Models\Betting;
use App\Models\UserBet;
use App\Models\Betting1d;
use App\Models\UserBet1d;
use App\Models\CryptoOneD;
use App\Models\CryptoTwoD;
use App\Models\LiveRecord;
use App\Models\SuperAdmin;
use App\Events\NumberEvent;
use App\Models\LuckyNumber;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use App\Helper\HolidayHelper;
use App\Models\CreditHistory;
use App\Models\LotteryOffDay;
use App\Models\OverAllSetting;
use App\Models\BettingCrypto1d;
use App\Models\BettingCrypto2d;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use App\Models\UserReferHistory;
use App\Models\CommissionHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\SuperAdmin\LiveController;

class helper
{

    public static function tingo()
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('H:i:s', time() + $diffWithGMT);
        $nowdate = gmdate('Y-m-d h:i A', time() + $diffWithGMT);
        $date = gmdate('Y-m-d', time() + $diffWithGMT);
        $ex_date = explode(' ', $nowdate);
        $now_hour = gmdate('H:i', time() + $diffWithGMT);
        $now_stamp = strtotime($now);
        $curtime = gmdate('h:i A', time() + $diffWithGMT);

        $ch = curl_init();
        $url = 'https://api.tiingo.com/tiingo/fx/top?tickers=xauusd,usdxau&token=2c1b8b2e6211821e392f02cff64731f0a0bf09e4';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);

        $de = json_decode($data);

        $now = date('Y-m-d H:i:s');
        $onlydate = date('Y-m-d');

        $bidprice =  $de[0]->bidPrice;

        $ex_one = explode('.', $bidprice);
        if (count($ex_one) === 1) {
            $first = '0';
        } else {
            $check_one = strlen($ex_one[1]);
            if ($check_one === 2) {
                $first = substr($ex_one[1], -1);
            } else {
                $first = '0';
            }
        }


        $askprice =  $de[0]->askPrice;
        $ex_two = explode('.', $askprice);
        if (count($ex_two) === 1) {
            $second = '0';
        } else {
            $check_two = strlen($ex_two[1]);
            if ($check_two === 2) {
                $second = substr($ex_two[1], -1);
            } else {
                $second = '0';
            }
        }
        $new_tingo = LiveRecord::where('id', 1)->first();


        if ($new_tingo->twod != $second . $first) {
            $res_data = (new LiveController)->live();
            event(new NumberEvent($res_data->original['text'], $res_data->original));
        }

        $new_tingo->buy = $de[0]->askPrice;
        $new_tingo->sell = $de[0]->bidPrice;
        $new_tingo->twod =  $second . $first;
        $new_tingo->time =  date($now, time());
        if (date($now, time()) == $onlydate . " 24:00:00") {
            $new_tingo->request_count = 0;
        } else {
            $new_tingo->request_count = $new_tingo->request_count + 1;
        }
        $new_tingo->save();


        Log::info('Tingo working again.');
    }

    public static function numbers()
    {
        $result = TwoD::get();
        return $result;
    }

    public static function numbers_1d()
    {
        $result = OneD::get();
        return $result;
    }

    public static function crypto_2d_numbers()
    {
        $result = CryptoTwoD::get();
        return $result;
    }

    public static function crypto_1d_numbers()
    {
        $result = CryptoOneD::get();
        return $result;
    }

    public static function time_filter($time, $date)
    {
        $grab = array();
        $data = array();
        $res = array();
        $queries = UserBet::where('section', $time)->where('date', $date)->with('bettings')->get();
        $message = 0;
        if ($queries->count() > 0) {
            foreach ($queries as $query) {
                foreach ($query->bettings as $datum) {
                    $array = [
                        "bet_number" => $datum->bet_number,
                        "amount" => (int) $datum->amount,
                    ];
                    array_push($grab, $array);
                }
            }

            $numbers = helper::numbers();
            foreach ($grab as $g) {
                $key = $g['bet_number'];
                if (isset($data[$key])) {
                    if ($data[$key]['bet_number'] === $g['bet_number']) {
                        $data[$key]['amount'] += $g['amount'];
                    }
                } else {
                    $data[$key] = $g;
                }
            }

            foreach ($numbers as $num) {
                $overall = OverAllSetting::first();
                $betting_amount = Betting::where('date', $date)->where('section', $time)->where('bet_number', $num->bet_number)->sum('amount');
                if ($num->hot_amout_limit > 0) {
                    if ($num->hot_amout_limit <= $betting_amount) {
                        $message = 1;
                    } else {
                        $message = 0;
                    }
                } else {
                    if ($overall->over_all_amount <= $betting_amount) {
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
                    "amount" => (int) $betting_amount
                ]);
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        } else {
            $numbers = helper::numbers();
            foreach ($numbers as $num) {
                array_push($res, [
                    "id" => $num->id,
                    "bet_number" => $num->bet_number,
                    "close_number" => $num->close_number,
                    "over_amount_message" => $message,
                    "hot_amount_limit" => $num->hot_amout_limit,
                    "amount" => 0
                ]);
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        }
    }

    public static function time_filter_1d($time, $date)
    {
        $grab = array();
        $data = array();
        $res = array();
        $queries = UserBet1d::where('section', $time)->where('date', $date)->with('bettings')->get();
        $message = 0;
        if ($queries->count() > 0) {
            foreach ($queries as $query) {
                foreach ($query->bettings as $datum) {
                    $array = [
                        "bet_number" => $datum->bet_number,
                        "amount" => (int) $datum->amount,
                    ];
                    array_push($grab, $array);
                }
            }

            $numbers = helper::numbers_1d();
            foreach ($grab as $g) {
                $key = $g['bet_number'];
                if (isset($data[$key])) {
                    if ($data[$key]['bet_number'] === $g['bet_number']) {
                        $data[$key]['amount'] += $g['amount'];
                    }
                } else {
                    $data[$key] = $g;
                }
            }

            foreach ($numbers as $num) {
                $overall = OverAllSetting::first();
                $betting_amount = Betting1d::where('date', $date)->where('section', $time)->where('bet_number', $num->bet_number)->sum('amount');
                if ($num->hot_amout_limit > 0) {
                    if ($num->hot_amout_limit <= $betting_amount) {
                        $message = 1;
                    } else {
                        $message = 0;
                    }
                } else {
                    if ($overall->over_all_amount_1d <= $betting_amount) {
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
                    "amount" => (int) $betting_amount
                ]);
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        } else {
            $numbers = helper::numbers_1d();
            foreach ($numbers as $num) {
                array_push($res, [
                    "id" => $num->id,
                    "bet_number" => $num->bet_number,
                    "close_number" => $num->close_number,
                    "over_amount_message" => $message,
                    "hot_amount_limit" => $num->hot_amout_limit,
                    "amount" => 0
                ]);
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        }
    }

    public static function time_filter_c2d($time, $date)
    { // for cryto 2D
        $grab = array();
        $data = array();
        $res = array();
        $queries = UserBetCrypto2d::where('section', $time)->where('date', $date)->with('bettings_c2d')->get();
        $message = 0;
        if ($queries->count() > 0) {
            foreach ($queries as $query) {
                foreach ($query->bettings_c2d as $datum) {
                    $array = [
                        "bet_number" => $datum->bet_number,
                        "amount" => (int) $datum->amount,
                    ];
                    array_push($grab, $array);
                }
            }

            $numbers = helper::crypto_2d_numbers();
            foreach ($grab as $g) {
                $key = $g['bet_number'];
                if (isset($data[$key])) {
                    if ($data[$key]['bet_number'] === $g['bet_number']) {
                        $data[$key]['amount'] += $g['amount'];
                    }
                } else {
                    $data[$key] = $g;
                }
            }

            foreach ($numbers as $num) {
                $overall = OverAllSetting::first();
                $betting_amount = BettingCrypto2d::where('date', $date)->where('section', $time)->where('bet_number', $num->bet_number)->sum('amount');
                if ($num->hot_amout_limit > 0) {
                    if ($num->hot_amout_limit <= $betting_amount) {
                        $message = 1;
                    } else {
                        $message = 0;
                    }
                } else {
                    if ($overall->over_all_amount <= $betting_amount) {
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
                    "amount" => (int) $betting_amount
                ]);
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        } else {
            $numbers = helper::crypto_2d_numbers();
            foreach ($numbers as $num) {
                array_push($res, [
                    "id" => $num->id,
                    "bet_number" => $num->bet_number,
                    "close_number" => $num->close_number,
                    "over_amount_message" => $message,
                    "hot_amount_limit" => $num->hot_amout_limit,
                    "amount" => 0
                ]);
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        }
    }


    public static function time_filter_c1d($time, $date)
    { // for cryto 2D
        $grab = array();
        $data = array();
        $res = array();
        $queries = UserBetCrypto1d::where('section', $time)->where('date', $date)->with('bettings')->get();
        $message = 0;
        if ($queries->count() > 0) {
            foreach ($queries as $query) {
                foreach ($query->bettings as $datum) {
                    $array = [
                        "bet_number" => $datum->bet_number,
                        "amount" => (int) $datum->amount,
                    ];
                    array_push($grab, $array);
                }
            }

            $numbers = helper::crypto_1d_numbers();
            foreach ($grab as $g) {
                $key = $g['bet_number'];
                if (isset($data[$key])) {
                    if ($data[$key]['bet_number'] === $g['bet_number']) {
                        $data[$key]['amount'] += $g['amount'];
                    }
                } else {
                    $data[$key] = $g;
                }
            }

            foreach ($numbers as $num) {
                $overall = OverAllSetting::first();
                $betting_amount = BettingCrypto1d::where('date', $date)->where('section', $time)->where('bet_number', $num->bet_number)->sum('amount');
                if ($num->hot_amout_limit > 0) {
                    if ($num->hot_amout_limit <= $betting_amount) {
                        $message = 1;
                    } else {
                        $message = 0;
                    }
                } else {
                    if ($overall->over_all_amount_crypto_1d <= $betting_amount) {
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
                    "amount" => (int) $betting_amount
                ]);
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        } else {
            $numbers = helper::crypto_1d_numbers();
            foreach ($numbers as $num) {
                array_push($res, [
                    "id" => $num->id,
                    "bet_number" => $num->bet_number,
                    "close_number" => $num->close_number,
                    "over_amount_message" => $message,
                    "hot_amount_limit" => $num->hot_amout_limit,
                    "amount" => 0
                ]);
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        }
    }

    public static function daily_filter($date)
    {
        $grab = array();
        $data = array();
        $res = array();
        $finalize = array();
        $bet_data = array();
        $queries = UserBet::where('date', $date)->with('bettings')->get();
        if ($queries->count() > 0) {
            foreach ($queries as $query) {
                foreach ($query->bettings as $datum) {
                    $array = [
                        "bet_number" => $datum->bet_number,
                        "amount" => (int) $datum->amount
                    ];
                    array_push($grab, $array);
                }
            }

            $numbers = helper::numbers();
            foreach ($grab as $g) {
                $key = $g['bet_number'];
                if (isset($data[$key])) {
                    if ($data[$key]['bet_number'] === $g['bet_number']) {
                        $data[$key]['amount'] += $g['amount'];
                    }
                } else {
                    $data[$key] = $g;
                }
            }
            foreach ($numbers as $num) {
                $val = $data[(string) $num->bet_number] ?? NULL;
                if ($val !== NULL) {
                    array_push($res, $val);
                } else {
                    array_push($res, [
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "amount" => 0
                    ]);
                }
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        } else {
            $numbers = helper::numbers();
            foreach ($numbers as $num) {
                array_push($res, $num);
            }
            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        }
    }

    public static function daily_filter_1d($date)
    {
        $grab = array();
        $data = array();
        $res = array();
        $finalize = array();
        $bet_data = array();
        $queries = UserBet1d::where('date', $date)->with('bettings')->get();
        if ($queries->count() > 0) {
            foreach ($queries as $query) {
                foreach ($query->bettings as $datum) {
                    $array = [
                        "bet_number" => $datum->bet_number,
                        "amount" => (int) $datum->amount
                    ];
                    array_push($grab, $array);
                }
            }

            $numbers = helper::numbers_1d();
            foreach ($grab as $g) {
                $key = $g['bet_number'];
                if (isset($data[$key])) {
                    if ($data[$key]['bet_number'] === $g['bet_number']) {
                        $data[$key]['amount'] += $g['amount'];
                    }
                } else {
                    $data[$key] = $g;
                }
            }
            foreach ($numbers as $num) {
                $val = $data[(string) $num->bet_number] ?? NULL;
                if ($val !== NULL) {
                    array_push($res, $val);
                } else {
                    array_push($res, [
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "amount" => 0
                    ]);
                }
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        } else {
            $numbers = helper::numbers_1d();
            foreach ($numbers as $num) {
                array_push($res, $num);
            }
            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        }
    }

    public static function daily_filter_c2d($date)
    {
        $grab = array();
        $data = array();
        $res = array();
        $queries = UserBetCrypto2d::where('date', $date)->with('bettings_c2d')->get();
        if ($queries->count() > 0) {
            foreach ($queries as $query) {
                foreach ($query->bettings_c2d as $datum) {
                    $array = [
                        "bet_number" => $datum->bet_number,
                        "amount" => (int) $datum->amount
                    ];
                    array_push($grab, $array);
                }
            }

            $numbers = helper::crypto_2d_numbers();
            foreach ($grab as $g) {
                $key = $g['bet_number'];
                if (isset($data[$key])) {
                    if ($data[$key]['bet_number'] === $g['bet_number']) {
                        $data[$key]['amount'] += $g['amount'];
                    }
                } else {
                    $data[$key] = $g;
                }
            }
            foreach ($numbers as $num) {
                $val = $data[(string) $num->bet_number] ?? NULL;
                if ($val !== NULL) {
                    array_push($res, $val);
                } else {
                    array_push($res, [
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "amount" => 0
                    ]);
                }
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        } else {
            $numbers = helper::crypto_2d_numbers();
            foreach ($numbers as $num) {
                array_push($res, $num);
            }
            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        }
    }

    public static function daily_filter_c1d($date)
    {
        $grab = array();
        $data = array();
        $res = array();
        $queries = UserBetCrypto1d::where('date', $date)->with('bettings')->get();
        if ($queries->count() > 0) {
            foreach ($queries as $query) {
                foreach ($query->bettings as $datum) {
                    $array = [
                        "bet_number" => $datum->bet_number,
                        "amount" => (int) $datum->amount
                    ];
                    array_push($grab, $array);
                }
            }

            $numbers = helper::crypto_1d_numbers();
            foreach ($grab as $g) {
                $key = $g['bet_number'];
                if (isset($data[$key])) {
                    if ($data[$key]['bet_number'] === $g['bet_number']) {
                        $data[$key]['amount'] += $g['amount'];
                    }
                } else {
                    $data[$key] = $g;
                }
            }
            foreach ($numbers as $num) {
                $val = $data[(string) $num->bet_number] ?? NULL;
                if ($val !== NULL) {
                    array_push($res, $val);
                } else {
                    array_push($res, [
                        "bet_number" => $num->bet_number,
                        "close_number" => $num->close_number,
                        "amount" => 0
                    ]);
                }
            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        } else {
            $numbers = helper::crypto_1d_numbers();
            foreach ($numbers as $num) {
                array_push($res, $num);
            }
            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        }
    }

    public static function dashboard_section($time, $date)
    {
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        $bet_amounts = UserBet::where('section', $time)->where('date', $date_format)->sum('total_amount');
        $reward_amounts = UserBet::where('section', $time)->where('date', $date_format)->sum('reward_amount');

        $bet_counts = Betting::where('section', $time)->where('date', $date_format)->get();
        // $total_bet_numbers = $bet_counts->count();

        if ($bet_counts->count() > 0) {
            foreach ($bet_counts as $count) {
                $key = $count->bet_number;
                if (isset($res[$key])) {
                    if ($res[$key]['bet_number'] === $count->bet_number) {
                        $res[$key]['amount'] += $count->amount;
                    }
                } else {
                    $res[$key] = $count;
                }
            }

            $r_key = array_values($res);
            foreach ($r_key as $k) {
                array_push($amt, [
                    "amount" => (float) $k['amount'],
                    "bet_number" => $k['bet_number']
                ]);
            }
            $total_bet_numbers = count($r_key);
            $max_bet_amount = max($amt);
        } else {
            $total_bet_numbers = 0;
            $max_bet_amount = 0;
        }

        $get_com = CommissionHistory::where('section', $time)->whereDate('created_at', $date_format)->sum('total_commission');
        $com = (float) $get_com;

        $user_refer_history = UserReferHistory::where('section', $time)->where('type', '2D')->whereDate('created_at', $date_format)->sum('amount');
        $user_refer = (float) $user_refer_history;

        $sum_cost = $com + $reward_amounts + $user_refer;
        if ($sum_cost > 0) {
            $profit = $bet_amounts - $sum_cost;
        } else {
            $profit = $bet_amounts;
        }

        $lucky = LuckyNumber::where('create_date', $date)->where('section', $time)->where('category_id', 1)->first();
        if (!empty($lucky)) {
            $luck = $lucky->lucky_number;
        } else {
            $luck = '-';
        }
        $now = Carbon::now();
        $lot_off = LotteryOffDay::where('category_id', 1)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "all_bet_amounts" => 0,
                "total_reward" => 0,
                "commission_total" => 0,
                "user_refer_total" => 0,
                "profit" => 0,
                "total_bet_number" => 0,
                "most_amount_number" => 0,
                "lucky_number" => '-',
            ]);
        } else {
            if (HolidayHelper::isThatWeekend($date) == 1) {
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "commission_total" => 0,
                    "user_refer_total" => 0,
                    "profit" => 0,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            }
            return response()->json([
                "all_bet_amounts" => (float) $bet_amounts,
                "total_reward" => (float) $reward_amounts,
                "commission_total" => $com,
                "user_refer_total" => $user_refer,
                "profit" => $profit,
                "total_bet_number" => $total_bet_numbers,
                "most_amount_number" => $max_bet_amount,
                "lucky_number" => $luck,
            ]);
        }
    }

    public static function dashboard_section_1d($time, $date)
    {
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        $bet_amounts = UserBet1d::where('section', $time)->where('date', $date_format)->sum('total_amount');
        $reward_amounts = UserBet1d::where('section', $time)->where('date', $date_format)->sum('reward_amount');

        $bet_counts = Betting1d::where('section', $time)->where('date', $date_format)->get();
        // $total_bet_numbers = $bet_counts->count();

        if ($bet_counts->count() > 0) {
            foreach ($bet_counts as $count) {
                $key = $count->bet_number;
                if (isset($res[$key])) {
                    if ($res[$key]['bet_number'] === $count->bet_number) {
                        $res[$key]['amount'] += $count->amount;
                    }
                } else {
                    $res[$key] = $count;
                }
            }

            $r_key = array_values($res);
            foreach ($r_key as $k) {
                array_push($amt, [
                    "amount" => (float) $k['amount'],
                    "bet_number" => $k['bet_number']
                ]);
            }
            $total_bet_numbers = count($r_key);
            $max_bet_amount = max($amt);
        } else {
            $total_bet_numbers = 0;
            $max_bet_amount = 0;
        }

        $get_com = CommissionHistory::where('section', $time)->whereDate('created_at', $date_format)->sum('total_commission');
        $com = (float) $get_com;

        $user_refer_history = UserReferHistory::where('section', $time)->where('type', '2D')->whereDate('created_at', $date_format)->sum('amount');
        $user_refer = (float) $user_refer_history;

        $sum_cost = $com + $reward_amounts + $user_refer;
        if ($sum_cost > 0) {
            $profit = $bet_amounts - $sum_cost;
        } else {
            $profit = $bet_amounts;
        }

        $lucky = LuckyNumber::where('create_date', $date)->where('section', $time)->where('category_id', 4)->first();
        if (!empty($lucky)) {
            $luck = $lucky->lucky_number;
        } else {
            $luck = '-';
        }
        $now = Carbon::now();
        $lot_off = LotteryOffDay::where('category_id', 1)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "all_bet_amounts" => 0,
                "total_reward" => 0,
                "commission_total" => 0,
                "user_refer_total" => 0,
                "profit" => 0,
                "total_bet_number" => 0,
                "most_amount_number" => 0,
                "lucky_number" => '-',
            ]);
        } else {
            if (HolidayHelper::isThatWeekend($date) == 1) {
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "commission_total" => 0,
                    "user_refer_total" => 0,
                    "profit" => 0,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            }
            return response()->json([
                "all_bet_amounts" => (float) $bet_amounts,
                "total_reward" => (float) $reward_amounts,
                "commission_total" => $com,
                "user_refer_total" => $user_refer,
                "profit" => $profit,
                "total_bet_number" => $total_bet_numbers,
                "most_amount_number" => $max_bet_amount,
                "lucky_number" => $luck,
            ]);
        }
    }

    public static function dashboard_c2d_section($time, $date) // for crypto 2D overall all statistics
    {
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        $bet_amounts = UserBetCrypto2d::where('section', $time)->where('date', $date_format)->sum('total_amount');
        $reward_amounts = UserBetCrypto2d::where('section', $time)->where('date', $date_format)->sum('reward_amount');

        $bet_counts = BettingCrypto2d::where('section', $time)->where('date', $date_format)->get();

        if ($bet_counts->count() > 0) {
            foreach ($bet_counts as $count) {
                $key = $count->bet_number;
                if (isset($res[$key])) {
                    if ($res[$key]['bet_number'] === $count->bet_number) {
                        $res[$key]['amount'] += $count->amount;
                    }
                } else {
                    $res[$key] = $count;
                }
            }

            $r_key = array_values($res);
            foreach ($r_key as $k) {
                array_push($amt, [
                    "amount" => (float) $k['amount'],
                    "bet_number" => $k['bet_number']
                ]);
            }
            $total_bet_numbers = count($r_key);
            $max_bet_amount = max($amt);
        } else {
            $total_bet_numbers = 0;
            $max_bet_amount = 0;
        }

        $get_com = CommissionHistory::where('section', $time)->whereDate('created_at', $date_format)->sum('total_commission');
        $com = (float) $get_com;

        $user_refer_history = UserReferHistory::where('section', $time)->where('type', 'C2D')->whereDate('created_at', $date_format)->sum('amount');
        $user_refer = (float) $user_refer_history;

        $sum_cost = $com + $reward_amounts + $user_refer;
        if ($sum_cost > 0) {
            $profit = $bet_amounts - $sum_cost;
        } else {
            $profit = $bet_amounts;
        }
        // category_id: 3, Crypto 2D
        $lucky = LuckyNumber::where('create_date', $date)->where('section', $time)->where('category_id', 3)->first();
        if (!empty($lucky)) {
            $luck = $lucky->lucky_number;
        } else {
            $luck = '-';
        }
        // category_id: 3, Crypto 2D
        $lot_off = LotteryOffDay::where('category_id', 3)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "all_bet_amounts" => 0,
                "total_reward" => 0,
                "commission_total" => 0,
                "user_refer_total" => 0,
                "profit" => 0,
                "total_bet_number" => 0,
                "most_amount_number" => 0,
                "lucky_number" => '-',
            ]);
        }
        return response()->json([
            "all_bet_amounts" => (float) $bet_amounts,
            "total_reward" => (float) $reward_amounts,
            "commission_total" => $com,
            "user_refer_total" => $user_refer,
            "profit" => $profit,
            "total_bet_number" => $total_bet_numbers,
            "most_amount_number" => $max_bet_amount,
            "lucky_number" => $luck,
        ]);
    }

    public static function dashboard_c1d_section($time, $date) // for crypto 2D overall all statistics
    {
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        $bet_amounts = UserBetCrypto1d::where('section', $time)->where('date', $date_format)->sum('total_amount');
        $reward_amounts = UserBetCrypto1d::where('section', $time)->where('date', $date_format)->sum('reward_amount');

        $bet_counts = BettingCrypto1d::where('section', $time)->where('date', $date_format)->get();

        if ($bet_counts->count() > 0) {
            foreach ($bet_counts as $count) {
                $key = $count->bet_number;
                if (isset($res[$key])) {
                    if ($res[$key]['bet_number'] === $count->bet_number) {
                        $res[$key]['amount'] += $count->amount;
                    }
                } else {
                    $res[$key] = $count;
                }
            }

            $r_key = array_values($res);
            foreach ($r_key as $k) {
                array_push($amt, [
                    "amount" => (float) $k['amount'],
                    "bet_number" => $k['bet_number']
                ]);
            }
            $total_bet_numbers = count($r_key);
            $max_bet_amount = max($amt);
        } else {
            $total_bet_numbers = 0;
            $max_bet_amount = 0;
        }

        $get_com = CommissionHistory::where('section', $time)->whereDate('created_at', $date_format)->sum('total_commission');
        $com = (float) $get_com;

        $user_refer_history = UserReferHistory::where('section', $time)->where('type', 'C2D')->whereDate('created_at', $date_format)->sum('amount');
        $user_refer = (float) $user_refer_history;

        $sum_cost = $com + $reward_amounts + $user_refer;
        if ($sum_cost > 0) {
            $profit = $bet_amounts - $sum_cost;
        } else {
            $profit = $bet_amounts;
        }
        // category_id: 3, Crypto 2D
        $lucky = LuckyNumber::where('create_date', $date)->where('section', $time)->where('category_id', 5)->first();
        if (!empty($lucky)) {
            $luck = $lucky->lucky_number;
        } else {
            $luck = '-';
        }
        // category_id: 3, Crypto 2D
        $lot_off = LotteryOffDay::where('category_id', 5)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "all_bet_amounts" => 0,
                "total_reward" => 0,
                "commission_total" => 0,
                "user_refer_total" => 0,
                "profit" => 0,
                "total_bet_number" => 0,
                "most_amount_number" => 0,
                "lucky_number" => '-',
            ]);
        }
        return response()->json([
            "all_bet_amounts" => (float) $bet_amounts,
            "total_reward" => (float) $reward_amounts,
            "commission_total" => $com,
            "user_refer_total" => $user_refer,
            "profit" => $profit,
            "total_bet_number" => $total_bet_numbers,
            "most_amount_number" => $max_bet_amount,
            "lucky_number" => $luck,
        ]);
    }

    public static function daily_statics($date)
    {
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        $bet_amounts = UserBet::where('date', $date_format)->sum('total_amount');
        $reward_amounts = UserBet::where('date', $date_format)->sum('reward_amount');

        $bet_counts = Betting::where('date', $date_format)->get();
        // $total_bet_numbers = $bet_counts->count();

        if ($bet_counts->count() > 0) {
            foreach ($bet_counts as $count) {
                $key = $count->bet_number;
                if (isset($res[$key])) {
                    if ($res[$key]['bet_number'] === $count->bet_number) {
                        $res[$key]['amount'] += $count->amount;
                    }
                } else {
                    $res[$key] = $count;
                }
            }

            $r_key = array_values($res);

            foreach ($r_key as $k) {
                array_push($amt, [
                    "amount" => (float) $k['amount'],
                    "bet_number" => $k['bet_number']
                ]);
            }
            $total_bet_numbers = count($r_key);
            $max_bet_amount = max($amt);
        } else {
            $total_bet_numbers = 0;
            $max_bet_amount = 0;
        }
        // Category_id: 1, 2D
        $lknumbers = LuckyNumber::where('create_date', $date_format)->where('category_id', 1)->get();
        if ($lknumbers->count() > 0) {
            $luck = $lknumbers;
        } else {
            $luck = '-';
        }
        $get_com = CommissionHistory::whereDate('created_at', $date_format)->sum('total_commission');
        $com = (float) $get_com;

        //user refer hist sum
        $user_refer_history = UserReferHistory::where('type', '2D')->whereDate('created_at', $date_format)->sum('amount');
        $user_refer = (float) $user_refer_history;

        $sum_cost = $com + $reward_amounts + $user_refer;
        if ($sum_cost > 0) {
            $profit = $bet_amounts - $sum_cost;
        } else {
            $profit = $bet_amounts;
        }

        $now = Carbon::now();
        $lot_off = LotteryOffDay::where('category_id', 1)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "all_bet_amounts" => 0,
                "total_reward" => 0,
                "commission_total" => 0,
                "user_refer_total" => 0,
                "profit" => 0,
                "total_bet_number" => 0,
                "most_amount_number" => 0,
                "lucky_number" => '-',
            ]);
        } else {
            if (HolidayHelper::isThatWeekend($date) == 1) {
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "commission_total" => 0,
                    "user_refer_total" => 0,
                    "profit" => 0,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            }
            return response()->json([
                "all_bet_amounts" => (float) $bet_amounts,
                "total_reward" => (float) $reward_amounts,
                "commission_total" => $com,
                "user_refer_total" => $user_refer,
                "profit" => $profit,
                "total_bet_number" => $total_bet_numbers,
                "most_amount_number" => $max_bet_amount,
                "lucky_number" => $luck,
            ]);
        }
    }

    public static function daily_statics_1d($date)
    {
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        $bet_amounts = UserBet1d::where('date', $date_format)->sum('total_amount');
        $reward_amounts = UserBet1d::where('date', $date_format)->sum('reward_amount');

        $bet_counts = Betting1d::where('date', $date_format)->get();
        // $total_bet_numbers = $bet_counts->count();

        if ($bet_counts->count() > 0) {
            foreach ($bet_counts as $count) {
                $key = $count->bet_number;
                if (isset($res[$key])) {
                    if ($res[$key]['bet_number'] === $count->bet_number) {
                        $res[$key]['amount'] += $count->amount;
                    }
                } else {
                    $res[$key] = $count;
                }
            }

            $r_key = array_values($res);

            foreach ($r_key as $k) {
                array_push($amt, [
                    "amount" => (float) $k['amount'],
                    "bet_number" => $k['bet_number']
                ]);
            }
            $total_bet_numbers = count($r_key);
            $max_bet_amount = max($amt);
        } else {
            $total_bet_numbers = 0;
            $max_bet_amount = 0;
        }
        // Category_id: 1, 2D
        $lknumbers = LuckyNumber::where('create_date', $date_format)->where('category_id', 4)->get();
        if ($lknumbers->count() > 0) {
            $luck = $lknumbers;
        } else {
            $luck = '-';
        }
        $get_com = CommissionHistory::whereDate('created_at', $date_format)->sum('total_commission');
        $com = (float) $get_com;

        //user refer hist sum
        $user_refer_history = UserReferHistory::where('type', '2D')->whereDate('created_at', $date_format)->sum('amount');
        $user_refer = (float) $user_refer_history;

        $sum_cost = $com + $reward_amounts + $user_refer;
        if ($sum_cost > 0) {
            $profit = $bet_amounts - $sum_cost;
        } else {
            $profit = $bet_amounts;
        }

        $now = Carbon::now();
        $lot_off = LotteryOffDay::where('category_id', 1)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "all_bet_amounts" => 0,
                "total_reward" => 0,
                "commission_total" => 0,
                "user_refer_total" => 0,
                "profit" => 0,
                "total_bet_number" => 0,
                "most_amount_number" => 0,
                "lucky_number" => '-',
            ]);
        } else {
            if (HolidayHelper::isThatWeekend($date) == 1) {
                return response()->json([
                    "all_bet_amounts" => 0,
                    "total_reward" => 0,
                    "commission_total" => 0,
                    "user_refer_total" => 0,
                    "profit" => 0,
                    "total_bet_number" => 0,
                    "most_amount_number" => 0,
                    "lucky_number" => '-',
                ]);
            }
            return response()->json([
                "all_bet_amounts" => (float) $bet_amounts,
                "total_reward" => (float) $reward_amounts,
                "commission_total" => $com,
                "user_refer_total" => $user_refer,
                "profit" => $profit,
                "total_bet_number" => $total_bet_numbers,
                "most_amount_number" => $max_bet_amount,
                "lucky_number" => $luck,
            ]);
        }
    }

    public static function daily_statics_c2d($date) // for crypto 2D daily statics
    {
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        $bet_amounts = UserBetCrypto2d::where('date', $date_format)->sum('total_amount');
        $reward_amounts = UserBetCrypto2d::where('date', $date_format)->sum('reward_amount');

        $bet_counts = BettingCrypto2d::where('date', $date_format)->get();

        if ($bet_counts->count() > 0) {
            foreach ($bet_counts as $count) {
                $key = $count->bet_number;
                if (isset($res[$key])) {
                    if ($res[$key]['bet_number'] === $count->bet_number) {
                        $res[$key]['amount'] += $count->amount;
                    }
                } else {
                    $res[$key] = $count;
                }
            }

            $r_key = array_values($res);

            foreach ($r_key as $k) {
                array_push($amt, [
                    "amount" => (float) $k['amount'],
                    "bet_number" => $k['bet_number']
                ]);
            }
            $total_bet_numbers = count($r_key);
            $max_bet_amount = max($amt);
        } else {
            $total_bet_numbers = 0;
            $max_bet_amount = 0;
        }
        // Category_id: 3, Crypto 2D
        $lknumbers = LuckyNumber::where('create_date', $date_format)->where('category_id', 3)->get();
        if ($lknumbers->count() > 0) {
            $luck = $lknumbers;
        } else {
            $luck = '-';
        }
        $get_com = CommissionHistory::whereDate('created_at', $date_format)->sum('total_commission');
        $com = (float) $get_com;

        //user refer hist sum
        $user_refer_history = UserReferHistory::where('type', 'C2D')->whereDate('created_at', $date_format)->sum('amount');
        $user_refer = (float) $user_refer_history;

        $sum_cost = $com + $reward_amounts + $user_refer;
        if ($sum_cost > 0) {
            $profit = $bet_amounts - $sum_cost;
        } else {
            $profit = $bet_amounts;
        }

        // Category_id: 3, Crypto 2D
        $lot_off = LotteryOffDay::where('category_id', 3)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "all_bet_amounts" => 0,
                "total_reward" => 0,
                "commission_total" => 0,
                "user_refer_total" => 0,
                "profit" => 0,
                "total_bet_number" => 0,
                "most_amount_number" => 0,
                "lucky_number" => '-',
            ]);
        }
        return response()->json([
            "all_bet_amounts" => (float) $bet_amounts,
            "total_reward" => (float) $reward_amounts,
            "commission_total" => $com,
            "user_refer_total" => $user_refer,
            "profit" => $profit,
            "total_bet_number" => $total_bet_numbers,
            "most_amount_number" => $max_bet_amount,
            "lucky_number" => $luck,
        ]);
    }

    public static function daily_statics_c1d($date) // for crypto 2D daily statics
    {
        $date_format = date('Y-m-d', strtotime($date));
        $res = [];
        $amt = [];
        $bet_amounts = UserBetCrypto1d::where('date', $date_format)->sum('total_amount');
        $reward_amounts = UserBetCrypto1d::where('date', $date_format)->sum('reward_amount');

        $bet_counts = BettingCrypto1d::where('date', $date_format)->get();

        if ($bet_counts->count() > 0) {
            foreach ($bet_counts as $count) {
                $key = $count->bet_number;
                if (isset($res[$key])) {
                    if ($res[$key]['bet_number'] === $count->bet_number) {
                        $res[$key]['amount'] += $count->amount;
                    }
                } else {
                    $res[$key] = $count;
                }
            }

            $r_key = array_values($res);

            foreach ($r_key as $k) {
                array_push($amt, [
                    "amount" => (float) $k['amount'],
                    "bet_number" => $k['bet_number']
                ]);
            }
            $total_bet_numbers = count($r_key);
            $max_bet_amount = max($amt);
        } else {
            $total_bet_numbers = 0;
            $max_bet_amount = 0;
        }
        // Category_id: 3, Crypto 2D
        $lknumbers = LuckyNumber::where('create_date', $date_format)->where('category_id', 5)->get();
        if ($lknumbers->count() > 0) {
            $luck = $lknumbers;
        } else {
            $luck = '-';
        }
        $get_com = CommissionHistory::whereDate('created_at', $date_format)->sum('total_commission');
        $com = (float) $get_com;

        //user refer hist sum
        $user_refer_history = UserReferHistory::where('type', 'C2D')->whereDate('created_at', $date_format)->sum('amount');
        $user_refer = (float) $user_refer_history;

        $sum_cost = $com + $reward_amounts + $user_refer;
        if ($sum_cost > 0) {
            $profit = $bet_amounts - $sum_cost;
        } else {
            $profit = $bet_amounts;
        }

        // Category_id: 3, Crypto 2D
        $lot_off = LotteryOffDay::where('category_id', 5)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "all_bet_amounts" => 0,
                "total_reward" => 0,
                "commission_total" => 0,
                "user_refer_total" => 0,
                "profit" => 0,
                "total_bet_number" => 0,
                "most_amount_number" => 0,
                "lucky_number" => '-',
            ]);
        }
        return response()->json([
            "all_bet_amounts" => (float) $bet_amounts,
            "total_reward" => (float) $reward_amounts,
            "commission_total" => $com,
            "user_refer_total" => $user_refer,
            "profit" => $profit,
            "total_bet_number" => $total_bet_numbers,
            "most_amount_number" => $max_bet_amount,
            "lucky_number" => $luck,
        ]);
    }

    public static function single_detail($time, $date, $number)
    {
        $final = [];
        $arr = [
            [
                "id" => 'super_admin_id',
                "model" => 'super_admins'
            ],
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

        $bettings = Betting::where('section', $time)->where('bet_number', $number)->where('date', $date)->with('user_bets')->get();
        if ($bettings->count() > 0) {
            foreach ($bettings as $betting) {
                foreach ($betting->user_bets as $bet) {
                    $ub = UserBet::where('id', $bet->id)->with('user')->first();
                    foreach ($arr as $r) {
                        $parentid = $r['id'];
                        if ($ub->user->$parentid) {
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final, $bt);
                        }
                    }
                }
            }
        }

        return $final;
    }

    public static function single_detail_1d($time, $date, $number)
    {
        $final = [];
        $arr = [
            [
                "id" => 'super_admin_id',
                "model" => 'super_admins'
            ],
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

        $bettings = Betting1d::where('section', $time)->where('bet_number', $number)->where('date', $date)->with('user_bets')->get();
        if ($bettings->count() > 0) {
            foreach ($bettings as $betting) {
                foreach ($betting->user_bets as $bet) {
                    $ub = UserBet1d::where('id', $bet->id)->with('user')->first();
                    foreach ($arr as $r) {
                        $parentid = $r['id'];
                        if ($ub->user->$parentid) {
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final, $bt);
                        }
                    }
                }
            }
        }

        return $final;
    }

    public static function single_detail_c2d($time, $date, $number)
    {
        $final = [];
        $arr = [
            [
                "id" => 'super_admin_id',
                "model" => 'super_admins'
            ],
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

        $bettings = BettingCrypto2d::where('section', $time)->where('bet_number', $number)->where('date', $date)->with('user_bets_c2d')->get();
        if ($bettings->count() > 0) {
            foreach ($bettings as $betting) {
                foreach ($betting->user_bets_c2d as $bet) {
                    $ub = UserBetCrypto2d::where('id', $bet->id)->with('user')->first();
                    foreach ($arr as $r) {
                        $parentid = $r['id'];
                        if ($ub->user->$parentid) {
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final, $bt);
                        }
                    }
                }
            }
        }

        return $final;
    }


    public static function single_detail_c1d($time, $date, $number)
    {
        $final = [];
        $arr = [
            [
                "id" => 'super_admin_id',
                "model" => 'super_admins'
            ],
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

        $bettings = BettingCrypto1d::where('section', $time)->where('bet_number', $number)->where('date', $date)->with('user_bets')->get();
        if ($bettings->count() > 0) {
            foreach ($bettings as $betting) {
                foreach ($betting->user_bets as $bet) {
                    $ub = UserBetCrypto1d::where('id', $bet->id)->with('user')->first();
                    foreach ($arr as $r) {
                        $parentid = $r['id'];
                        if ($ub->user->$parentid) {
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final, $bt);
                        }
                    }
                }
            }
        }

        return $final;
    }

    public static function daily_detail($date, $number)
    {
        $final = [];
        $arr = [
            [
                "id" => 'super_admin_id',
                "model" => 'super_admins'
            ],
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

        $bettings = Betting::where('bet_number', $number)->where('date', $date)->with('user_bets')->get();
        if ($bettings->count() > 0) {
            foreach ($bettings as $betting) {
                foreach ($betting->user_bets as $bet) {
                    $ub = UserBet::where('id', $bet->id)->with('user')->first();
                    foreach ($arr as $r) {
                        $parentid = $r['id'];
                        if ($ub->user->$parentid) {
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final, $bt);
                        }
                    }
                }
            }
        }
        return $final;
    }

    public static function daily_detail_1d($date, $number)
    {
        $final = [];
        $arr = [
            [
                "id" => 'super_admin_id',
                "model" => 'super_admins'
            ],
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

        $bettings = Betting1d::where('bet_number', $number)->where('date', $date)->with('user_bets')->get();
        if ($bettings->count() > 0) {
            foreach ($bettings as $betting) {
                foreach ($betting->user_bets as $bet) {
                    $ub = UserBet1d::where('id', $bet->id)->with('user')->first();
                    foreach ($arr as $r) {
                        $parentid = $r['id'];
                        if ($ub->user->$parentid) {
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final, $bt);
                        }
                    }
                }
            }
        }
        return $final;
    }

    public static function daily_detail_c2d($date, $number)
    {
        $final = [];
        $arr = [
            [
                "id" => 'super_admin_id',
                "model" => 'super_admins'
            ],
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

        $bettings = BettingCrypto2d::where('bet_number', $number)->where('date', $date)->with('user_bets_c2d')->get();
        if ($bettings->count() > 0) {
            foreach ($bettings as $betting) {
                foreach ($betting->user_bets_c2d as $bet) {
                    $ub = UserBetCrypto2d::where('id', $bet->id)->with('user')->first();
                    foreach ($arr as $r) {
                        $parentid = $r['id'];
                        if ($ub->user->$parentid) {
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final, $bt);
                        }
                    }
                }
            }
        }
        return $final;
    }

    public static function daily_detail_c1d($date, $number)
    {
        $final = [];
        $arr = [
            [
                "id" => 'super_admin_id',
                "model" => 'super_admins'
            ],
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

        $bettings = BettingCrypto1d::where('bet_number', $number)->where('date', $date)->with('user_bets')->get();
        if ($bettings->count() > 0) {
            foreach ($bettings as $betting) {
                foreach ($betting->user_bets as $bet) {
                    $ub = UserBetCrypto1d::where('id', $bet->id)->with('user')->first();
                    foreach ($arr as $r) {
                        $parentid = $r['id'];
                        if ($ub->user->$parentid) {
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $betting->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final, $bt);
                        }
                    }
                }
            }
        }
        return $final;
    }


    public static function checkStatus($date, $time)
    {
        $lucky = LuckyNumber::where('create_date', $date)->where('section', $time)->first();
        $userbets = Betting::where('date', $date)->where('section', $time)->with('user_bets')->get();
        foreach ($userbets as $bet) {
            if ($bet->bet_number == $lucky->lucky_number) {
                $bet->win = 1;
                $bet->save();
            } else {
                $bet->win = 2;
                $bet->save();
            }
        }

        $bettings = UserBet::where('date', $date)->where('section', $time)->with('bettings')->get();
        foreach ($bettings as $b) {
            $check = $b->bettings->where('win', '=', 1)->first();
            if (!empty($check)) {
                $b->win = 1;
                $b->reward_amount = $check->amount * $check->odd;
                $b->save();

                // $firebaseToken = User::where('id', $b->user_id)->pluck('device_token')->first();
                $firebaseToken = User::where('id', $b->user_id)->pluck('device_token')->all();
                if ($firebaseToken !== null) {
                    $SERVER_API_KEY = 'AAAAY9kKSiQ:APA91bFLTAiseWMlnFx4Zyuyp0WTjthUQsXq54v4sfdM8nUpPh2i2Q0Cz7BK2c_zugvoCvzZFkRxxXvV_RM08yVluZtxQefa4n7KbvKVoDDsYPrwOosrmHmlkAgpFS1hd05qkUumPr87';

                    $data = [
                        "registration_ids" => $firebaseToken,
                        "notification" => [
                            "title" => 'MYVIP',
                            "body" => 'Congratulation! Lucky number is ' . $lucky->lucky_number,
                            "icon" => asset("backend/logo.png"),
                        ],
                        "data" => [
                            "title" => 'MYVIP',
                            "body" =>  'Congratulation! Lucky number is ' . $lucky->lucky_number,
                            "icon" => asset("backend/logo.png"),
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
            } else {
                $b->win = 2;
                $b->save();
            }
        }
    }

    public static function spadmin_daily_pay_record($date, $column)
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('H:i', time() + $diffWithGMT);
        $dateplus = gmdate('Y-m-d', time() + $diffWithGMT);
        $result = [];
        if ($column === 'senior_agent_id') {
            $hists = helper::getHist($column, $date);
            foreach ($hists as $hist) {
                $sa = SeniorAgent::where('id', '=', $hist->senior_agent_id)->first();
                $credit = (float) $hist->credit;
                $sa_user_value = helper::find_user($sa->id, $date, 'senior_agent_id');
                $credit_sum = $credit + (int) $sa_user_value['cashin'];
                $record = $credit_sum * $sa->percent / 100;
                $left_credit = $credit_sum - $record;
                $ma_user_value = helper::find_master_user($sa->id, $date);
                $a_user_value = helper::find_agent_user($sa->id, $date);
                $total_sum = $sa_user_value['reward'] + $ma_user_value + $a_user_value;
                $res = [
                    "id" => $sa->id,
                    "name" => $sa->name,
                    "phone" => $sa->phone,
                    "percent" => $sa->percent,
                    "to_get" => $credit_sum,
                    "commission" => $record,
                    "to_pay" => (float) $total_sum,
                    "result" => $left_credit - $total_sum
                ];
                array_push($result, $res);
            }
        } else if ($column === 'master_agent_id') {
            $hists = helper::getHist($column, $date);
            foreach ($hists as $hist) {
                $ma = MasterAgent::where('id', '=', $hist->master_agent_id)->first();
                if ($ma->super_admin_id !== NULL) {
                    $ma_user_value = helper::find_user($ma->id, $date, 'master_agent_id');
                    $credit = (float) $hist->credit;
                    $credit_sum = $credit + $ma_user_value['cashin'];
                    $record = $credit_sum * $ma->percent / 100;
                    $left_credit = $credit_sum - $record;
                    $a_user_value = helper::find_agent_user($ma->id, $date);
                    $total_sum = $ma_user_value['reward'] + $a_user_value;

                    $res = [
                        "id" => $ma->id,
                        "name" => $ma->name,
                        "phone" => $ma->phone,
                        "percent" => $ma->percent,
                        "to_get" => $credit_sum,
                        "commission" => $record,
                        "to_pay" => (float) $total_sum,
                        "result" => $left_credit - $total_sum
                    ];
                    array_push($result, $res);
                }
            }
        } else if ($column === 'agent_id') {
            $hists = helper::getHist($column, $date);
            foreach ($hists as $hist) {
                $users = User::where('agent_id', '=', $hist->id)->get();
                foreach ($users as $user) {
                    $bets = UserBet::where('user_id', $user->id)->where('date', $date)->get();
                    $cashin = CashIn::where('user_id', $user->id)->where('date', $date)->sum('amount');
                    $record = $cashin * $hist->percent / 100;
                    $left_credit = $cashin - $record;
                    if ($cashin > 0 || $bets->sum('reward_amount') > 0) {
                        $res = [
                            "id" => $hist->id,
                            "name" => $hist->name,
                            "phone" => $hist->phone,
                            "percent" => $hist->percent,
                            "to_get" => $cashin,
                            "commission" => $record,
                            "to_pay" => $bets->sum('reward_amount'),
                            "result" => $left_credit - $bets->sum('reward_amount')
                        ];
                        array_push($result, $res);
                    }
                }
            }
        } else {
            $sp_users = User::where('super_admin_id', 1)->get();
            foreach ($sp_users as $sp_user) {
                $bettings = UserBet::where('user_id', $sp_user->id)->where('date', $date)->get();
                if ($bettings->count() > 0) {
                    $reward = $bettings->sum('reward_amount');
                    $bet_amount = $bettings->sum('total_amount');
                    $res = [
                        "id" => $sp_user->id,
                        "name" => $sp_user->name,
                        "phone" => $sp_user->phone,
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

    public static function spadmin_section_pay_record($date, $column, $time, $id)
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('H:i', time() + $diffWithGMT);
        $result = [];
        $define_time = strtotime($time);
        $now_stamp = strtotime($now);
        // if($now_stamp >= $define_time){
        if ($column === 'senior_agent_id') {
            $hist = helper::getHistSection($column, $date, $time, $id);
            $sa = SeniorAgent::where('id', '=', $id)->first();
            $sa_user_value = helper::find_user_by_section($sa->id, $date, 'senior_agent_id', $time);
            $credit = $hist + $sa_user_value['cashin'];
            $record = $credit * $sa->percent / 100;
            $left_credit = $credit - $record;
            $ma_user_value = helper::find_master_user_by_section($sa->id, $date, $time);
            $a_user_value = helper::find_agent_user_by_section($sa->id, $date, $time);
            $total_sum = $sa_user_value['reward'] + $a_user_value + $ma_user_value;
            $res = [
                "name" => $sa->name,
                "phone" => $sa->phone,
                "percent" => $sa->percent,
                "to_get" => $credit,
                "commission" => $record,
                "to_pay" => (float) $total_sum,
                "result" => $left_credit - $total_sum,
            ];
            array_push($result, $res);
        } else if ($column === 'master_agent_id') {
            $hist = helper::getHistSection($column, $date, $time, $id);
            $ma = MasterAgent::where('id', '=', $id)->first();
            if ($ma->super_admin_id !== NULL) {
                $ma_user_value = helper::find_user_by_section($ma->id, $date, 'master_agent_id', $time);
                $credit = $hist + $ma_user_value['cashin'];
                $record = $credit * $ma->percent / 100;
                $left_credit = $credit - $record;
                $a_user_value = helper::find_agent_user_by_section($ma->id, $date, $time);
                $total_sum = $ma_user_value['reward'] + $a_user_value;

                $res = [
                    "name" => $ma->name,
                    "phone" => $ma->phone,
                    "percent" => $ma->percent,
                    "to_get" => $credit,
                    "commission" => $record,
                    "to_pay" => (float) $total_sum,
                    "result" => $left_credit - $total_sum,
                ];
                array_push($result, $res);
            }
        } else if ($column === 'agent_id') {
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
            array_push($result, $res);
        } else {
            $sp_users = User::where('super_admin_id', 1)->get();
            foreach ($sp_users as $sp_user) {
                $bettings = UserBet::where('user_id', $sp_user->id)->where('date', $date)->where('section', $time)->get();
                if ($bettings->count() > 0) {
                    $reward = $bettings->sum('reward_amount');
                    $bet_amount = $bettings->sum('total_amount');
                    $res = [
                        "name" => $sp_user->name,
                        "phone" => $sp_user->phone,
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
        // } else {
        //     return response()->json([
        //         "message" => "time is not yet",
        //         "result" => $result
        //     ]);
        // }
    }

    public static function find_user($id, $date, $column)
    {
        $users = User::where($column, $id)->get();
        $res = [];
        $cash = [];
        foreach ($users as $user) {
            $query = UserBet::where('user_id', $user->id)->where('date', $date)->sum('reward_amount');
            $cashin = CashIn::where('user_id', $user->id)->where('date', $date)->sum('amount');
            array_push($res, $query);
            array_push($cash, $cashin);
        }
        $sum = array_sum($res);
        $cash_sum = array_sum($cash);

        return [
            "reward" => $sum,
            "cashin" => $cash_sum
        ];
    }

    public static function find_master_user($id, $date)
    {
        $res = [];
        $mas = MasterAgent::where('senior_agent_id', '=', $id)->get();
        foreach ($mas as $ma) {
            $sum = helper::find_user($ma->id, $date, 'master_agent_id');
            $child_sum = helper::search_agent($ma->id, $date);
            $final_sum = $sum['reward'] + $child_sum;
            array_push($res, $final_sum);
        }
        $total_sum = array_sum($res);

        return $total_sum;
    }

    public static function find_agent_user($id, $date)
    {
        $res = [];
        $res = [];
        $as = Agent::where('senior_agent_id', '=', $id)->get();
        foreach ($as as $a) {
            $sum = helper::find_user($a->id, $date, 'agent_id');
            array_push($res, $sum);
        }
        $total_sum = array_sum($res);

        return $total_sum;
    }

    public static function search_agent($id, $date)
    {
        $res = [];
        $findagents = Agent::where('master_agent_id', $id)->get();
        foreach ($findagents as $agent) {
            $users = User::where('agent_id', $agent->id)->get();
            foreach ($users as $user) {
                $bet = UserBet::where('user_id', $user->id)->where('date', $date)->sum('reward_amount');
                array_push($res, $bet);
            }
        }

        $total_sum = array_sum($res);

        return $total_sum;
    }

    public static function find_user_by_section($id, $date, $column, $time)
    {
        $users = User::where($column, $id)->get();
        $res = [];
        $cash = [];
        foreach ($users as $user) {
            $query = UserBet::where('user_id', $user->id)->where('date', $date)->where('section', $time)->sum('reward_amount');
            $time_filter = helper::timeCheck($time);
            $cashin = CashIn::where('user_id', $user->id)->where('date', $date)
                ->whereTime('time', '>=', $time_filter['start'])
                ->whereTime('time', '<=', $time_filter['end'])
                ->sum('amount');
            array_push($res, $query);
            array_push($cash, $cashin);
        }
        $sum = array_sum($res);
        $sum_cash = array_sum($cash);
        return [
            "reward" => $sum,
            "cashin" => $sum_cash
        ];
    }

    public static function find_master_user_by_section($id, $date, $time)
    {
        $res = [];
        $mas = MasterAgent::where('senior_agent_id', '=', $id)->get();
        foreach ($mas as $ma) {
            $sum = helper::find_user_by_section($ma->id, $date, 'master_agent_id', $time);
            $child_sum = helper::search_agent_by_section($ma->id, $date, $time);
            $final_sum = $sum['reward'] + $child_sum;
            array_push($res, $final_sum);
        }
        $total_sum = array_sum($res);

        return $total_sum;
    }

    public static function find_agent_user_by_section($id, $date, $time)
    {
        $res = [];
        $as = Agent::where('senior_agent_id', '=', $id)->get();
        foreach ($as as $a) {
            $sum = helper::find_user_by_section($a->id, $date, 'agent_id', $time);
            array_push($res, $sum);
        }
        $total_sum = array_sum($res);

        return $total_sum;
    }

    public static function search_agent_by_section($id, $date, $time)
    {
        $res = [];
        $findagents = Agent::where('master_agent_id', $id)->get();
        foreach ($findagents as $agent) {
            $users = User::where('agent_id', $agent->id)->get();
            foreach ($users as $user) {
                $bet = UserBet::where('user_id', $user->id)->where('date', $date)->where('section', $time)->sum('reward_amount');
                array_push($res, $bet);
            }
        }

        $total_sum = array_sum($res);

        return $total_sum;
    }

    public static function currentDateTime()
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $time = gmdate('H:i', time() + $diffWithGMT);
        $date = gmdate('Y-m-d', time() + $diffWithGMT);

        return [
            "date" => $date,
            "time" => $time
        ];
    }

    public static function getHistSection($column, $date, $time, $id)
    {
        if ($column === 'senior_agent_id') {
            $time_filter = helper::timeCheck($time);
            $hists = DB::table('credit_histories')
                ->where('super_admin_id', '=', NULL)->where($column, '=', $id)
                ->whereDate('date', $date)
                ->whereTime('time', '>=', $time_filter['start'])
                ->whereTime('time', '<=', $time_filter['end'])
                ->sum('credit');

            return $hists;
        } else if ($column === 'master_agent_id') {
            $time_filter = helper::timeCheck($time);
            $hists = DB::table('credit_histories')
                ->where('super_admin_id', '=', NULL)->where('senior_agent_id', '=', NULL)->where($column, '=', $id)
                ->whereDate('date', $date)
                ->whereTime('time', '>=', $time_filter['start'])
                ->whereTime('time', '<=', $time_filter['end'])
                ->sum('credit');

            return $hists;
        } else {
            $hists = DB::table('agents')
                ->where('super_admin_id', '!=', NULL)
                ->get();
            return $hists;
        }
    }

    public static function getHist($column, $date)
    {
        if ($column === 'senior_agent_id') {
            $hists = DB::table('credit_histories')
                ->where('super_admin_id', '=', NULL)->where($column, '!=', NULL)
                ->whereDate('date', $date)
                ->groupBy([$column])
                ->selectRaw('sum(credit) as credit,' . $column)
                ->get();
            return $hists;
        } else if ($column === 'master_agent_id') {
            $hists = DB::table('credit_histories')
                ->where('super_admin_id', '=', NULL)->where('senior_agent_id', '=', NULL)->where($column, '!=', NULL)
                ->whereDate('date', $date)
                ->groupBy([$column])
                ->selectRaw('sum(credit) as credit,' . $column)
                ->get();
            return $hists;
        } else {
            $hists = DB::table('agents')
                ->where('super_admin_id', '!=', NULL)
                ->get();
            return $hists;
        }
    }

    public static function getHistSma($column, $date, $sma_id, $req_who)
    {
        if ($req_who === 'senior') {
            if ($column === 'master_agent_id') {
                $hists = DB::table('credit_histories')
                    ->where('super_admin_id', '=', NULL)
                    ->where('senior_agent_id', '=', NULL)->where($column, '!=', NULL)
                    ->whereDate('date', $date)
                    ->groupBy([$column])
                    ->selectRaw('sum(credit) as credit,' . $column)
                    ->get();
            }
            return $hists;
        } else if ($req_who === 'master') {
            $hists = DB::table('credit_histories')
                ->where('master_agent_id', '=', $sma_id)->where($column, '!=', NULL)
                ->whereDate('date', $date)
                ->groupBy([$column])
                ->selectRaw('sum(credit) as credit,' . $column)
                ->get();
            return $hists;
        }
    }

    public static function getHistSmaSection($column, $time, $date, $sma_id, $req_who)
    {
        if ($req_who === 'senior') {
            if ($column === 'master_agent_id') {
                $time_filter = helper::timeCheck($time);
                $hists = DB::table('credit_histories')
                    ->where('super_admin_id', '=', NULL)
                    ->where('senior_agent_id', '=', NULL)->where($column, '!=', NULL)
                    ->whereDate('date', $date)
                    ->whereTime('time', '>=', $time_filter['start'])
                    ->whereTime('time', '<=', $time_filter['end'])
                    ->groupBy([$column])
                    ->selectRaw('sum(credit) as credit,' . $column)
                    ->get();
                return $hists;
            }
        } else if ($req_who === 'master') {
            $time_filter = helper::timeCheck($time);
            $hists = DB::table('credit_histories')
                ->where('master_agent_id', '=', $sma_id)->where($column, '!=', NULL)
                ->whereDate('date', $date)
                ->groupBy([$column])
                ->whereTime('time', '>=', $time_filter['start'])
                ->whereTime('time', '<=', $time_filter['end'])
                ->selectRaw('sum(credit) as credit,' . $column)
                ->get();
            return $hists;
        }
    }

    public static function timeCheck($time)
    {
        if ($time === "9:30 AM") {
            $start = "00:01";
            $end = "09:30";
        } else if ($time === "12:00 PM") {
            $start = "09:31";
            $end = "12:00";
        } else if ($time === "2:00 PM") {
            $start = "12:01";
            $end = "14:00";
        } else if ($time === "4:30 PM") {
            $start = "14:01";
            $end = "16:30";
        } else if ($time === "8:00 PM") {
            $start = "16:31";
            $end = "20:00";
        }

        return [
            "start" => $start,
            "end" => $end
        ];
    }

    public static function game_errorcode()
    {
        $array = [
            '0' => 'SUCCESS',
            '61' => 'CURRENCY_NOT_SUPPORT',
            '70' => 'INSUFFICIENT_KIOSK_BALANCE ',
            '71' => 'INVALID_REFERENCE_ID  ',
            '72' => 'INSUFFICIENT_BALANCE  ',
            '73' => 'INVALID_TRANSFER_AMOUNT ',
            '74' => 'INVALID_TRANSFER_AMOUNT_TWO_DECIMAL_ONLY',
            '81' => 'MEMBER_NOT_FOUND',
            '82' => 'MEMBER_EXISTED',
            '83' => 'OPERATOR_EXISTED',
            '90' => 'INVALID_PARAMETER',
            '91' => 'INVALID_OPERATOR',
            '92' => 'INVALID_PROVIDERCODE',
            '93' => 'INVALID_PARAMETER_TYPE',
            '94' => 'INVALID_PARAMETER_USERNAME',
            '95' => 'INVALID_PARAMETER_PASSWORD',
            '96' => 'INVALID_PARAMETER_OPASSWORD',
            '97' => 'INVALID_PARAMETER_EMPTY_DOMAINNAME',
            '98' => 'INVALID_USERNAME_OR_PASSWORD',
            '99' => 'INVALID_SIGNATURE',
            '600' => 'pre-check stage FAILED, deposit/withdraw transaction IGNORED',
            '601' => 'DEPO_APIREQ_BLOCKED_FOR_THIS_PRODUCT_TILL_FURTHER_NOTICE',
            '602' => 'WITH_APIREQ_BLOCKED_FOR_THIS_PRODUCT_TILL_FURTHER_NOTICE',
            '603' => 'Going to perform an online maintenance, Deposit/Withdraw API is DISABLED temporarily (disabled duration 5~ 10 minutes, will release earlier when done earlier)',
            '992' => 'INVALID_PARAMETER_PRODUCT_NOT_SUPPORTED_GAMETYPE',
            '991' => 'OPERATOR_STATUS_INACTIVE',
            '994' => 'ACCESS_PROHIBITED',
            '995' => 'PRODUCT_NOT_ACTIVATED',
            '996' => 'PRODUCT_NOT_AVAILABLE',
            '998' => 'PLEASE_CONTACT_CSD',
            '999' => 'UNDER_MAINTENENCE',
            '993' => 'whit list api',
            '9999' => 'UNKNOWN_ERROR',
            '-987' => 'RECORD_NOT_FOUND',
            '-997' => 'SYS_EXCEPTION, Please contact CS.',
            '-998' => 'INSUFFICIENT_APIKIOSK_BALANCE',
            '-999' => 'API_ERROR',
            '501' => 'WC success'
        ];
        return $array;
    }
}