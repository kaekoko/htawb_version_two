<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\helper;
use App\Models\Section;
use App\Models\LogRecord;
use App\Models\AutoRecord;
use App\Models\LiveRecord;
use App\Models\LuckyNumber;
use App\Models\CustomRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CustomRecordCrypto2D;
use App\Models\SectionCrypto2d;

class LiveController extends Controller
{
    public function times()
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d h:i A', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $times = Section::where("is_open", 1)->pluck('time_section')->toArray();
        $array = [];
        foreach ($times as $t) {
            $time = date("h:i A", strtotime($t));
            $st_time = strtotime($time);
            $st_now = strtotime($date[1] . ' ' . $date[2]);
            if ($st_now >= $st_time) {
                $res = 'over';
            } else {
                $res = $time;
            }
            array_push($array, $res);
        }

        return response()->json($array);
    }

    public function custom(Request $request, $id)
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $number = $request->number;

        $sec = $request->sec;
        

        $custom = new LuckyNumber();
        $custom->lucky_number = $number;
        $custom->section = $sec;
        $custom->category_id = 4;
        $custom->create_date = $now;
        $custom->save();

        $message = [
            'data' => $custom
        ];
        return $message;
    }

    public function custom_c2d(Request $request, CustomRecordCrypto2D $custom_record)
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT + 06:30 ( Myanmar Time Zone )
        $date = gmdate('Y-m-d', time() + $diffWithGMT);
        $number = $request->number;
        $custom_record->twod_number = $number;
        $custom_record->record_date = $date;
        $custom_record->save();

        $message = [
            'data' => $custom_record
        ];
        return $message;
    }

    public function dailyList()
    {
        $time_array = Section::where("is_open", 1)->pluck('time_section')->toArray();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d h:i A', time() + $diffWithGMT);
        $ex_date = explode(' ', $now);
        $final = [];
        foreach ($time_array as $time) {
            $time_format = date("h:i A", strtotime($time));
            $num = AutoRecord::where('record_time', $time_format)->where('record_date', $ex_date[0])->first();
            if ($num !== null) {
                $time_data = [
                    "buy" => $num->buy,
                    "sell" => $num->sell,
                    "2d" => $num->twod_number,
                    "time" => $num->record_time
                ];
            } else {
                $time_data = [
                    "buy" => "Coming Soon",
                    "sell" => "Coming Soon",
                    "2d" => "Coming Soon",
                    "time" => $time_format
                ];
            }

            array_push($final, $time_data);
        }

        return response()->json($final);
    }



    public function filter_date(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d h:i A', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $nor_array = [];

        $normals = AutoRecord::whereBetween('record_date', [$from, $to])->get();
        foreach ($normals as $normal) {
            $nor_data = [
                "buy" =>  $normal->buy,
                "sell" => $normal->sell,
                "2d" => $normal->twod_number,
                "date" => $normal->record_date,
                "time" => $normal->record_time,
                "datetime" => str_replace('00:00:00', '', $normal->record_date . $normal->record_time)
            ];
            array_push($nor_array, $nor_data);
        }

        return response()->json([
            "data" => $nor_array
        ]);
    }

    public function tingo_channel()
    {
        helper::tingo();
    }

    public function testing()
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $date = gmdate('Y-m-d', time() + $diffWithGMT);
    }


    public function live()
    {
        $datum = LiveRecord::where('id', 1)->first();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $timing = gmdate('s', time() + $diffWithGMT);
        $curtime = gmdate('H:i', time() + $diffWithGMT);
        $now = gmdate('Y-m-d H:i:s', time() + $diffWithGMT);
        $now_time = gmdate('Y-m-d h:i A', time() + $diffWithGMT);
        $ex_date = explode(' ', $now_time);
        $date = gmdate('Y-m-d', time() + $diffWithGMT);

        $get_rec = CustomRecord::where('record_date', '=', $date)->where('record_time', '=', $ex_date[1] . ' ' . $ex_date[2])->where('twod_number', '!=', '-')->first();
        if (!empty($get_rec)) {
            $ex_one = explode('.', $datum->sell);
            $ex_two = explode('.', $datum->buy);

            $split_db = str_split($get_rec->twod_number);
            $one_len = strlen($ex_one[1]);
            if ($one_len == 2) {
                $split_b_api = str_split($ex_one[1]);
                $first_b_num = $split_b_api[0];
                $second_b_num = $split_db[1];
                $sell = $ex_one[0] . '.' . $first_b_num .  $second_b_num;
            } else {
                $first_b_num = $ex_one[1];
                $second_b_num = $split_db[1];
                $sell = $ex_one[0] . '.' . $first_b_num .  $second_b_num;
            }

            $two_len = strlen($ex_two[1]);

            if ($two_len === 2) {
                $split_a_api = str_split($ex_two[1]);
                $first_a_num = $split_a_api[0];
                $second_a_num = $split_db[0];
                $buy = $ex_one[0] . '.' . $first_a_num .  $second_a_num;
            } else {
                $first_a_num = $ex_two[1];
                $second_a_num = $split_db[0];
                $buy = $ex_two[0] . '.' . $first_a_num .  $second_a_num;
            }

            if (number_format($timing) >= 0 && number_format($timing) <= 20) {
                $same_data_lucky = DB::table('lucky_numbers')->where('section', $get_rec->record_time)->where('create_date', $date)->first();
                if (empty($same_data_lucky)) {
                    $new = new AutoRecord();
                    $new->record_date = $get_rec->record_date;
                    $new->record_time = $get_rec->record_time;
                    $new->twod_number = $get_rec->twod_number;
                    $new->buy = $buy;
                    $new->sell = $sell;
                    $new->save();

                    $lucky = new LuckyNumber();
                    $lucky->lucky_number = $get_rec->twod_number;
                    $lucky->section = $get_rec->record_time;
                    $lucky->create_date = $get_rec->record_date;
                    $lucky->category_id = 1;
                    $lucky->save();

                    helper::checkStatus($date, $get_rec->record_time);
                    $data_app = [
                        "buy" => $new->buy,
                        "sell" => $new->sell,
                        "2d" => $get_rec->twod_number,
                        "time" => date($now, time()),
                        "text" => "lucky",
                    ];
                } else {
                    $data_app = [
                        "buy" => $datum->buy,
                        "sell" => $datum->sell,
                        "2d" => $datum->twod,
                        "time" => date($now, time()),
                        "text" => "fire",
                    ];
                }
            } else {
                $data_app = [
                    "buy" => $datum->buy,
                    "sell" => $datum->sell,
                    "2d" => $datum->twod,
                    "time" => date($now, time()),
                    "text" => "fire",
                ];
            }
        } else {
            $times = Section::where("is_open", 1)->pluck('time_section')->toArray();
            $etxt = "fire";
            foreach ($times as $t) {
                $time = date("H:i", strtotime($t));
                if ($curtime == $time) {
                    if (number_format($timing) >= 0 && number_format($timing) <= 20) {
                        $same_data_lucky = DB::table('lucky_numbers')->where('section', $ex_date[1] . ' ' . $ex_date[2])->where('create_date', $date)->first();
                        if (empty($same_data_lucky)) {
                            $etxt = "lucky";

                            $new = new AutoRecord();
                            $new->record_date = $date;
                            $new->record_time = $ex_date[1] . ' ' . $ex_date[2];
                            $new->twod_number = $datum->twod;
                            $new->buy = $datum->buy;
                            $new->sell = $datum->sell;
                            $new->save();

                            $lucky = new LuckyNumber();
                            $lucky->lucky_number = $datum->twod;
                            $lucky->section = $ex_date[1] . ' ' . $ex_date[2];
                            $lucky->create_date = $date;
                            $lucky->category_id = 1;
                            $lucky->save();

                            helper::checkStatus($date, $lucky->section);
                        }
                    }
                }
            }
            $data_app = [
                "buy" => $datum->buy,
                "sell" => $datum->sell,
                "2d" => $datum->twod,
                "time" => date($now, time()),
                "text" => $etxt,
            ];
        }
        return response()->json($data_app);
    }
}
