<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\TwoD;
use App\Helper\helper;
use App\Models\Section;
use App\Models\Section1d;
use Illuminate\Http\Request;
use App\Helper\HolidayHelper;
use App\Models\LotteryOffDay;
use App\Models\OverAllSetting;
use App\Models\SectionCrypto2d;
use App\Helper\republicFunction;
use App\Http\Controllers\Controller;

class OneDController extends Controller
{
    public function index(Request $request)
    {
        //validate time section
        $validated = $request->validate([
            'section' => 'required',
        ]);

        $date = Carbon::now();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d', time() + $diffWithGMT);

        $weekend = HolidayHelper::isThatWeekend($date);
        // category_id: 1, Thai 2D
        $lottey_off_day = LotteryOffDay::where('category_id', 1)->pluck('off_day')->toArray();
        $off_day = HolidayHelper::isTheDayHoliday($date, $lottey_off_day);

        $daily_section = Section::where("is_open", 1)->orderBy('id', 'DESC')->get();
        if ($daily_section[0]['time_section'] > $date->toTimeString()) {
            $daily_section = 0;
        } else {
            $daily_section = 1;
        }

        if ($off_day == 0 && $daily_section == 0) {
            $game = 'open';
        } else {
            $game = 'close';
        }

        $two_ds = helper::time_filter($request->section, $now);
        $data = $two_ds->original['data'];

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'game' => $game,
            'data' => $data
        ]);
    }

    public function nTwoD(Request $request)
    {
        //validate time section
        $validated = $request->validate([
            'section' => 'required',
        ]);

        $date = Carbon::now();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d', time() + $diffWithGMT);

        $weekend = HolidayHelper::isThatWeekend($date);
        // category_id: 1, Thai 2D
        $lottey_off_day = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
        $off_day = HolidayHelper::isTheDayHoliday($date, $lottey_off_day);

        $daily_section = Section1d::where("is_open", 1)->orderBy('id', 'DESC')->get();

        if ($daily_section[0]['time_section'] > $date->toTimeString()) {
            $daily_section = 0;
        } else {
            $daily_section = 1;
        }

        if ($weekend == 0 && $off_day == 0 && $daily_section == 0) {
            $game = 'open';
        } else {
            $game = 'close';
        }

        $two_ds = republicFunction::getNumberInfo1d($request->section, $now);
        $overAllSetting = OverAllSetting::first();

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'overall_amount' => $overAllSetting->over_all_amount_1d,
            'default_amount' => $overAllSetting->over_all_default_amount,
            'odd' => $overAllSetting->over_all_odd_1d,
            'game' => $game,
            'data' => $two_ds
        ]);
    }

}
