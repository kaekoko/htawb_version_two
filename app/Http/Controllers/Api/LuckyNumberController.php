<?php

namespace App\Http\Controllers\Api;

use App\Models\Section;
use App\Models\Section1d;
use App\Models\Section3d;
use App\Models\Sectionc1d;
use App\Models\LuckyNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LuckyNumberController extends Controller
{
    public function luckynumber_daily()
    {
        $time_array = Section::where("is_open", 1)->pluck('time_section')->toArray();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d h:i A', time() + $diffWithGMT);
        $ex_date = explode(' ', $now);
        $final = [];
        foreach ($time_array as $time) {
            $time_format = date("h:i A", strtotime($time));
            $num = LuckyNumber::where('section', $time_format)->where('create_date', $ex_date[0])->where('category_id', 1)->where('approve', 1)->first();
            if ($num !== null) {
                $time_data = [
                    "2d" => $num->lucky_number,
                    "section" => $num->section
                ];
            } else {
                $time_data = [
                    "2d" => "Coming Soon",
                    "section" => $time_format
                ];
            }
            array_push($final, $time_data);
        }

        return response()->json($final);
    }

    public function luckynumber1d()
    {
        $time_array = Section1d::where("is_open", 1)->pluck('time_section')->toArray();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d h:i A', time() + $diffWithGMT);
        $ex_date = explode(' ', $now);
        $final = [];
        foreach ($time_array as $time) {
            $time_format = date("h:i A", strtotime($time));
            $num = LuckyNumber::where('section', $time_format)->where('create_date', $ex_date[0])->where('category_id', 4)->where('approve', 1)->first();
            if ($num !== null) {
                $time_data = [
                    "1d" => $num->lucky_number,
                    "section" => $num->section
                ];
            } else {
                $time_data = [
                    "1d" => "Coming Soon",
                    "section" => $time_format
                ];
            }
            array_push($final, $time_data);
        }

        return response()->json($final);
    }

    public function luckynumber3d()
    {
        $date_array = Section3d::pluck('date')->toArray();
        $month = date('m');
        $year = date('Y');
        $final = [];
        foreach ($date_array as $date) {
            $num = LuckyNumber::where('category_id', 2)->where('create_date', $year . '-' . $month . '-' . $date)->first();
            if ($num !== null) {
                $time_data = [
                    "lucky number" => $num->lucky_number,
                    "date" => $date,
                    "opening_date" => $num->create_date
                ];
            } else {
                $time_data = [
                    "lucky number" => 'Coming Soon',
                    "date" => $date
                ];
            }
            array_push($final, $time_data);
        }
        return response()->json($final);
    }

    public function luckynumbercrypton2d()
    {
        $time_array = Section::pluck('time_section')->toArray();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d h:i A', time() + $diffWithGMT);
        $ex_date = explode(' ', $now);
        $final = [];
        foreach ($time_array as $time) {
            $time_format = date("h:i A", strtotime($time));
            $num = LuckyNumber::where('section', $time_format)->where('create_date', $ex_date[0])->where('category_id', 3)->where('approve', 1)->first();
            if ($num !== null) {
                $time_data = [
                    "c2d" => $num->lucky_number,
                    "section" => $num->section
                ];
            } else {
                $time_data = [
                    "c2d" => "Coming Soon",
                    "section" => $time_format
                ];
            }
            array_push($final, $time_data);
        }

        return response()->json($final);
    }

    public function luckynumbercrypton1d()
    {
        $time_array = Sectionc1d::where("is_open", 1)->pluck('time_section')->toArray();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d h:i A', time() + $diffWithGMT);
        $ex_date = explode(' ', $now);
        $final = [];
        foreach ($time_array as $time) {
            $time_format = date("h:i A", strtotime($time));
            $num = LuckyNumber::where('section', $time_format)->where('create_date', $ex_date[0])->where('category_id', 5)->where('approve', 1)->first();
            if ($num !== null) {
                $time_data = [
                    "c1d" => $num->lucky_number,
                    "section" => $num->section
                ];
            } else {
                $time_data = [
                    "c1d" => "Coming Soon",
                    "section" => $time_format
                ];
            }
            array_push($final, $time_data);
        }

        return response()->json($final);
    }

    

    public function luckyNumberHistory(Request $request, $type)
    {
        $this->validate($request, [
            "start_date" => "required",
            "end_date" => "required"
        ]);
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        // category_id: 1 => Thai 2D
        // category_id: 2 => Thai 3D
        // category_id: 3 => Crypto 2D
        $histories = LuckyNumber::where('approve', 1)
            ->orderBy('id', 'desc')
            ->whereBetween('create_date', [$start, $end]);
        if ($type === "2D") {
            $histories->where('category_id', 1);
        } elseif ($type === "3D") {
            $histories->where('category_id', 2);
        } elseif ($type === "C2D") {
            $histories->where('category_id', 3);
        }elseif ($type === "C1D") {
            $histories->where('category_id', 5);
        }elseif ($type === "1D") {
            $histories->where('category_id', 4);
        }
        return response()->json([
            'data' => $histories->get()
        ], 200);
    }
}
