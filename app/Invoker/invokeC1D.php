<?php

namespace App\Invoker;

use Carbon\Carbon;
use App\Models\Sectionc1d;
use App\Models\LuckyNumber;
use App\Models\SectionCrypto2d;
use App\Models\AutoRecordCrypto2D;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CustomRecordCrypto2D;
use App\Http\Controllers\SuperAdmin\LuckyNumberController;

class invokeC1D
{

    public static function luckyNumberSectionC1DCron($schedule)
    {
        $check = invokeAll::holidayCheckC2D();
        if ($check === 1) {
            $sections = Sectionc1d::where("is_open", 1)->get();
            foreach ($sections as $section) {
                $time=$section->time_section;
                $newtime = date('H:i', strtotime($time. ' +2 minutes'));
                $schedule->command('lucky_number_c1d:cron')->dailyAt($newtime)->timezone('Asia/Yangon');
            }
        }
    }

    public function fetchLiveC1D()
    {
        
        $ch = curl_init();
        $url = 'https://api.lucky8.website/api/luckynumber';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        return json_decode($data, true);
    }

    public static function insertLuckyNumberC1D($section)
    {
        $invoker = new invokeC1D;
        $res = $invoker->fetchLiveC1D();
       
        $newsection = date('H:i A', strtotime($section. ' -2 minutes'));

        while (empty($res)) {
            $res = $invoker->fetchLiveC1D();
        }
        foreach ($res as $key => $value) {
            $lntime=date('H:i A', strtotime($value['section']. ' 0 minutes'));
            if( $lntime == $newsection){
               $two_D = $value['number'];
               $two_D = substr($two_D, 1, 1);
               $datasection = $value['section'];
        
            }
          }
         
          $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT + 06:30 ( Myanmar Time Zone )
          $date = gmdate('Y-m-d', time() + $diffWithGMT);
          // to store lucky number and save on autorecord
          $same_data_lucky = DB::table('lucky_numbers')
              ->where('section', $datasection)
              ->where('create_date', $date)
              ->where('category_id', 5) // Crypto 2D
              ->first();
          if (empty($same_data_lucky)) {
              $lucky = new LuckyNumber();
              $lucky->lucky_number = $two_D;
              $lucky->section = $datasection;
              $lucky->create_date = $date;
              $lucky->approve = 1;
              $lucky->category_id = 5; // Crypto 2D
              $lucky->save();
        
              // Approve Lucky Number
              $LuckyController = new LuckyNumberController();
              $LuckyController->approve_c1d($lucky);
          }
        Log::info('Crypto One D insert in lucky number');
    }

    public static function dailystaticCron($schedule)
    {
        $check = invokeAll::holidayCheckC2D();
        if ($check === 1) {
            $last_sec = Sectionc1d::where("is_open", 1)->orderBy('id', 'DESC')->first();
            $run = Carbon::parse($last_sec->time_section)->addHour(2)->toTimeString();
            $schedule->command('daily_static_c1d:cron')->dailyAt($run)->timezone('Asia/Yangon');
        }
    }
}