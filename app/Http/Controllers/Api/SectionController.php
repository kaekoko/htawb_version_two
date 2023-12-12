<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Helper\helper;
use App\Models\Section;
use App\Invoker\invoke3D;
use App\Models\Section1d;
use App\Models\Section3d;
use App\Models\Sectionc1d;
use Illuminate\Http\Request;
use App\Helper\HolidayHelper;
use App\Models\LotteryOffDay;
use App\Models\NumberSetting;
use App\Models\NumberSetting1d;
use App\Models\SectionCrypto2d;
use App\Models\NumberSettingc1d;
use App\Http\Controllers\Controller;
use App\Models\NumberSettingCrypto2d;

class SectionController extends Controller
{
    public function index()
    {
        $current_time = date('H:i:s');
        $sections = Section::whereTime('time_section', '>', $current_time)
            ->where('is_open', 1)->get();
        $date = Carbon::now();
        $weekend = HolidayHelper::isThatWeekend($date);
        // category_id: 1, Thai 2D
        $lottey_off_day = LotteryOffDay::where('category_id', 1)->pluck('off_day')->toArray();
        $off_day = HolidayHelper::isTheDayHoliday($date, $lottey_off_day);
        $is_opened_today = ($weekend == 0 && $off_day == 0) ? true : false;
        return response()->json([
            'result' => count($sections),
            'status' => 200,
            'is_opened_today' => $is_opened_today,
            'message' => 'success',
            'data' => $sections
        ]);
    }

    public function index_1d()
    {
        $current_time = date('H:i:s');
        $sections = Section1d::whereTime('time_section', '>', $current_time)
            ->where('is_open', 1)->get();
        $date = Carbon::now();
        $weekend = HolidayHelper::isThatWeekend($date);
        // category_id: 1, Thai 2D
        $lottey_off_day = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
        $off_day = HolidayHelper::isTheDayHoliday($date, $lottey_off_day);
        $is_opened_today = ($weekend == 0 && $off_day == 0) ? true : false;
        return response()->json([
            'result' => count($sections),
            'status' => 200,
            'is_opened_today' => $is_opened_today,
            'message' => 'success',
            'data' => $sections
        ]);
    }

    public function web()
    {
        $time = date("H:i:s");
        $sections = Section::where("is_open", 1)->where('close_time', '>', $time)->get();
        return response()->json([
            'result' => count($sections),
            'status' => 200,
            'message' => 'success',
            'data' => $sections
        ]);
    }

    private function hot_block_data_insert($date)
    {
        $find_num = NumberSetting::whereDate('created_at', $date)->get();
        if (count($find_num) <= 0) {
            $hot_times = Section::where("is_open", 1)->get();
            foreach ($hot_times as $time) {
                $hot = new NumberSetting();
                $hot->section = date("h:i A", strtotime($time->time_section));
                $hot->type = 'hot';
                $hot->hot_number = '-';
                $hot->hot_amount = NULL;
                $hot->save();
            }
            $block_times = Section::where("is_open", 1)->get();
            foreach ($block_times as $time) {
                $block = new NumberSetting();
                $block->section = date("h:i A", strtotime($time->time_section));
                $block->type = 'block';
                $block->block_number = NULL;
                $block->save();
            }
        }
    }

    private function hot_block_data_insert_1d($date)
    {
        $find_num = NumberSetting1d::whereDate('created_at', $date)->get();
        if (count($find_num) <= 0) {
            $hot_times = Section1d::where("is_open", 1)->get();
            foreach ($hot_times as $time) {
                $hot = new NumberSetting1d();
                $hot->section = date("h:i A", strtotime($time->time_section));
                $hot->type = 'hot';
                $hot->hot_number = '-';
                $hot->hot_amount = NULL;
                $hot->save();
            }
            $block_times = Section1d::where("is_open", 1)->get();
            foreach ($block_times as $time) {
                $block = new NumberSetting1d();
                $block->section = date("h:i A", strtotime($time->time_section));
                $block->type = 'block';
                $block->block_number = NULL;
                $block->save();
            }
        }
    }


    private function hot_block_data_insert_c2d($date)
    {
        $find_num = NumberSettingCrypto2d::whereDate('created_at', $date)->get();
        if (count($find_num) <= 0) {
            $hot_times = SectionCrypto2d::get();
            foreach ($hot_times as $time) {
                $hot = new NumberSettingCrypto2d();
                $hot->section = date("h:i A", strtotime($time->time_section));
                $hot->type = 'hot';
                $hot->hot_number = '-';
                $hot->hot_amount = NULL;
                $hot->save();
            }
            $block_times = SectionCrypto2d::get();
            foreach ($block_times as $time) {
                $block = new NumberSettingCrypto2d();
                $block->section = date("h:i A", strtotime($time->time_section));
                $block->type = 'block';
                $block->block_number = NULL;
                $block->save();
            }
        }
    }

    private function hot_block_data_insert_c1d($date)
    {
        $find_num = NumberSettingc1d::whereDate('created_at', $date)->get();
        if (count($find_num) <= 0) {
            $hot_times = Sectionc1d::where('is_open',1)->get();
            foreach ($hot_times as $time) {
                $hot = new NumberSettingc1d();
                $hot->section = date("h:i A", strtotime($time->time_section));
                $hot->type = 'hot';
                $hot->hot_number = '-';
                $hot->hot_amount = NULL;
                $hot->save();
            }
            $block_times = Sectionc1d::where('is_open',1)->get();
            foreach ($block_times as $time) {
                $block = new NumberSettingc1d();
                $block->section = date("h:i A", strtotime($time->time_section));
                $block->type = 'block';
                $block->block_number = NULL;
                $block->save();
            }
        }
    }

    public function hot_block(Request $request)
    {

        $this->hot_block_data_insert($request->date);

        $sections = NumberSetting::whereDate('created_at', $request->date)->get();
        $hot_numbers = $sections->where('type', 'hot')->toArray();
        $hot_data = [];
        foreach ($hot_numbers as $hn) {
            $hot_data[$hn['section']] = [
                'hot_number' => $hn['hot_number'] != '-' && $hn['hot_number'] != null ? explode(',', $hn['hot_number']) : [],
                'amount' => $hn['hot_amount'] != null ? $hn['hot_amount'] : []
            ];
        }

        $block_numbers = $sections->where('type', 'block')->toArray();
        $block_data = [];
        foreach ($block_numbers as $bn) {
            $block_data[$bn['section']] = [
                'block_number' => $bn['block_number'] != null ? explode(',', $bn['block_number']) : []
            ];
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => [
                'hot' => $hot_data,
                'block' => $block_data
            ]
        ]);
    }

    public function hot_block_1d(Request $request)
    {

        $this->hot_block_data_insert_1d($request->date);

        $sections = NumberSetting1d::whereDate('created_at', $request->date)->get();
        $hot_numbers = $sections->where('type', 'hot')->toArray();
        $hot_data = [];
        foreach ($hot_numbers as $hn) {
            $hot_data[$hn['section']] = [
                'hot_number' => $hn['hot_number'] != '-' && $hn['hot_number'] != null ? explode(',', $hn['hot_number']) : [],
                'amount' => $hn['hot_amount'] != null ? $hn['hot_amount'] : []
            ];
        }

        $block_numbers = $sections->where('type', 'block')->toArray();
        $block_data = [];
        foreach ($block_numbers as $bn) {
            $block_data[$bn['section']] = [
                'block_number' => $bn['block_number'] != null ? explode(',', $bn['block_number']) : []
            ];
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => [
                'hot' => $hot_data,
                'block' => $block_data
            ]
        ]);
    }

    

    public function hot_block_c2d(Request $request)
    {

        $this->hot_block_data_insert_c2d($request->date);

        $sections = NumberSettingCrypto2d::whereDate('created_at', $request->date)->get();
        $hot_numbers = $sections->where('type', 'hot')->toArray();
        $hot_data = [];
        foreach ($hot_numbers as $hn) {
            $hot_data[$hn['section']] = [
                'hot_number' => $hn['hot_number'] != '-' && $hn['hot_number'] != null ? explode(',', $hn['hot_number']) : [],
                'amount' => $hn['hot_amount'] != null ? $hn['hot_amount'] : []
            ];
        }

        $block_numbers = $sections->where('type', 'block')->toArray();
        $block_data = [];
        foreach ($block_numbers as $bn) {
            $block_data[$bn['section']] = [
                'block_number' => $bn['block_number'] != null ? explode(',', $bn['block_number']) : []
            ];
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => [
                'hot' => $hot_data,
                'block' => $block_data
            ]
        ]);
    }

    public function hot_block_c1d(Request $request)
    {

        $this->hot_block_data_insert_c1d($request->date);

        $sections = NumberSettingc1d::whereDate('created_at', $request->date)->get();
        $hot_numbers = $sections->where('type', 'hot')->toArray();
        $hot_data = [];
        foreach ($hot_numbers as $hn) {
            $hot_data[$hn['section']] = [
                'hot_number' => $hn['hot_number'] != '-' && $hn['hot_number'] != null ? explode(',', $hn['hot_number']) : [],
                'amount' => $hn['hot_amount'] != null ? $hn['hot_amount'] : []
            ];
        }

        $block_numbers = $sections->where('type', 'block')->toArray();
        $block_data = [];
        foreach ($block_numbers as $bn) {
            $block_data[$bn['section']] = [
                'block_number' => $bn['block_number'] != null ? explode(',', $bn['block_number']) : []
            ];
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => [
                'hot' => $hot_data,
                'block' => $block_data
            ]
        ]);
    }

    public function hot_block_mobile(Request $request)
    {

        $this->hot_block_data_insert($request->date);

        $sections = NumberSetting::whereDate('created_at', $request->date)->get();
        $hot_numbers = $sections->where('type', 'hot')->toArray();
        $hot_data = [];
        foreach ($hot_numbers as $hn) {
            $hot_data[] = [
                'section' => $hn['section'],
                'hot_number' => $hn['hot_number'] != '-' && $hn['hot_number'] != null ? explode(',', $hn['hot_number']) : [],
                'amount' => $hn['hot_amount'] != null ? $hn['hot_amount'] : '0'
            ];
        }

        $block_numbers = $sections->where('type', 'block')->toArray();
        $block_data = [];
        foreach ($block_numbers as $bn) {
            $block_data[] = [
                'section' => $bn['section'],
                'block_number' => $bn['block_number'] != null ? explode(',', $bn['block_number']) : []
            ];
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => [
                'hot' => $hot_data,
                'block' => $block_data
            ]
        ]);
    }

    public function section_3d()
    {
        $now_date = date('d');
        $get = invoke3D::getSection3D($now_date);
        $section = Section3d::where('date', $get)->first();
        $sec = date('h:i A', strtotime($section->time));
        $close = date('h:i A', strtotime($section->close_time));
        $date = Carbon::now();
        // Category Id 2, Thai 3D     
        $lottey_off_day = LotteryOffDay::where('category_id', 2)->pluck('off_day')->toArray();
        $off_day = HolidayHelper::isTheDayHoliday($date, $lottey_off_day);
        $is_opened_today = $off_day == 0 ? true : false;
        //opening date
        $datetime = helper::currentDateTime();
        $bet_date = invoke3D::betDate3d($datetime['date']);
        return response()->json([
            'date_3d' => $get,
            'section' => $sec,
            'close' => $close,
            'opening_date'=>$bet_date,
            'is_opened_today' => $is_opened_today
        ]);
    }

    public function section_crypton2d()
    {
        $current_time = date('H:i:s');
        $sections = SectionCrypto2d::whereTime('time_section', '>', $current_time)->get();
        $date = Carbon::now();
        // Category Id 3, Crypto 2D
        $lottey_off_day = LotteryOffDay::where('category_id', 3)->pluck('off_day')->toArray();
        $off_day = HolidayHelper::isTheDayHoliday($date, $lottey_off_day);
        $is_opened_today = $off_day == 0 ? true : false;
        return response()->json([
            'result' => count($sections),
            'status' => 200,
            'is_opened_today' => $is_opened_today,
            'message' => 'success',
            'data' => $sections
        ]);
    }

    public function section_crypton1d()
    {
        $current_time = date('H:i:s');
        $sections = Sectionc1d::whereTime('time_section', '>', $current_time)->where('is_open', 1)->get();
        $date = Carbon::now();
        // Category Id 3, Crypto 2D
        $lottey_off_day = LotteryOffDay::where('category_id', 5)->pluck('off_day')->toArray();
        $off_day = HolidayHelper::isTheDayHoliday($date, $lottey_off_day);
        $is_opened_today = $off_day == 0 ? true : false;
        return response()->json([
            'result' => count($sections),
            'status' => 200,
            'is_opened_today' => $is_opened_today,
            'message' => 'success',
            'data' => $sections
        ]);
    }
}
