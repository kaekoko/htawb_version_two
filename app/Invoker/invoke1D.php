<?php

namespace App\Invoker;

use Carbon\Carbon;
use App\Models\Section;
use App\Models\Section1d;
use App\Invoker\invokeAll;
use App\Models\LuckyNumber;
use Illuminate\Support\Str;
use App\Models\CustomRecord;
use Illuminate\Support\Facades\Log;

class invoke1D
{
    public static function luckyNumberSection1dCron($schedule)
    {
        $check = invokeAll::holidayCheck();
        if($check === 1){
            $sections = Section1d::where("is_open", 1)->get();
            foreach($sections as $section){
                $schedule->command('lucky_number_1d:cron')->dailyAt(str_split($section->time_section, 5)[0])->timezone('Asia/Yangon');
            }
        }
    }

    // public static function insertLuckyNumber1D($section)
    // {
    //     $tingo = json_decode(file_get_contents("https://api.thaistock2d.com/live"), true);

    //     if($tingo['live']){
    //         $live = $tingo['live'];
    //     }

    //     info('live success');

    //     $get_two_d = CustomRecord::where('record_time', $section)->where('twod_number', '!=', '-')->first();
    //     if($get_two_d){

    //         $twod_number = $get_two_d->twod_number;
            
    //         $ex_num = str_split($get_two_d->twod_number);
    //         $value = rand(1000,8000).$ex_num[1].'.'.rand(00,99);

    //     }else{
    //         if($live){
    //             if($live['value'] && $live['twod']){

    //                 $twod_number = $live['twod'];
    //                 $value = $live['value'];
    //             }else{

    //                 $twod_number = rand(10,99);
    //                 $ex_num = str_split($twod_number);
    //                 $value = rand(1000,8000).$ex_num[1].'.'.rand(00,99);

    //             }
    //         }else{
    //             $twod_number = rand(10,99);
    //             $ex_num = str_split($twod_number);
    //             $value = rand(1000,8000).$ex_num[1].'.'.rand(00,99);
    //         }
    //     }

        

    //     $twod_number = substr($twod_number, 1, 1);
    //     info($twod_number);

    //     $date = Carbon::now();
    //     $lucky_number = New LuckyNumber();
    //     $lucky_number->lucky_number = $twod_number;
    //     $lucky_number->section = $section;
    //     $lucky_number->value = $value;
    //     $lucky_number->category_id = 4;
    //     $lucky_number->create_date = Carbon::parse($date);
    //     $lucky_number->save();
    //     Log::info('1 D insert in lucky number');
    // }

    public static function dailystaticCron($schedule)
    {
        $check = invokeAll::holidayCheck();
        if($check === 1){
            $last_sec = Section1d::where("is_open", 1)->orderBy('id', 'DESC')->first();
            $run = Carbon::parse($last_sec->time_section)->addHour(2)->toTimeString();
            $schedule->command('daily_static:cron')->dailyAt($run)->timezone('Asia/Yangon');
        }
    }

}
