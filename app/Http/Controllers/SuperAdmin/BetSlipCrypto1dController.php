<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Helper\helper;
use App\Models\Sectionc1d;
use Illuminate\Http\Request;
use App\Helper\HolidayHelper;
use App\Models\LotteryOffDay;
use App\Models\SectionCrypto2d;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use App\Http\Controllers\Controller;

class BetSlipCrypto1dController extends Controller
{
    public function bet_slip_crypto_1d(Request $request)
    {
        $bet_slips = UserBetCrypto1d::with('bettings');

        if($request->date){
            $date = $request->date;
        }else{
            $date = Carbon::today();
        }

        if($request->section){
            $bet_slips = $bet_slips->where('section', $request->section);
        }

        if($request->side_stats){
            $bet_slips = $bet_slips->whereHas('user', function ($query) use($request)  {
               if($request->side_stats == 'myvip'){
                return $query->where('side',null);
               }elseif($request->side_stats == 'icasino'){
                return $query->where('side',1);
               }
            })->orderBy('id', 'DESC')->whereDate('date', $date)->get();
           }else{
            $bet_slips = $bet_slips->orderBy('id', 'DESC')->whereDate('date', $date)->get();
           }

        foreach($bet_slips as $bet_slip){
            $bet_slip->read = 1;
            $bet_slip->save();
        }

        $sections = Sectionc1d::where("is_open", 1)->get();
        return view('super_admin.bet_slip_crypto_1d.bet_slip')->with([
            'bet_slips' => $bet_slips,
            'sections' => $sections
        ]);
    }
    

    public function bet_slip_crypto_1d_detail($id)
    {
        $bet_slip_detail = UserBetCrypto1d::findOrFail($id);
        $bet_slip_detail->read = 1;
        $bet_slip_detail->update();
        return view('super_admin.bet_slip_crypto_1d.bet_slip_detail', [
            'bet_slip_detail' => $bet_slip_detail
        ]);
    }

    public function all_bets_c1d(Request $request)
    {
        $time = $request->time; //9:01 AM
        $date = $request->date; //created_at
        // category_id: 3, crypto_2D
        $lot_off = LotteryOffDay::where('category_id', 5)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "message" => "success",
                "status" => "close",
            ]);
        }
        return helper::time_filter_c1d($time, $date);
    }

    public function daily_all_bets_c1d(Request $request)
    {
        $date = $request->date; //created_at
        // category_id: 3, crypto_2D
        $lot_off = LotteryOffDay::where('category_id', 5)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "message" => "success",
                "status" => "close",
            ]);
        } 
        return helper::daily_filter_c1d($date);
    }

    public function single_bet_detail_c1d(Request $request){
        $time = $request->time;
        $date = $request->date;
        $number = $request->number;
        $diffWithGMT=6*60*60+30*60; // GMT + 06:30 ( Myanmar Time Zone )
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !==  null){
            return helper::single_detail_c1d($time, $date, $number);
        }
        return helper::single_detail_c1d($time, $curdate, $number);
    }

    public function daily_bet_detail_c1d(Request $request){
        $date = $request->date;
        $number = $request->number;
        $diffWithGMT=6*60*60+30*60; // GMT + 06:30 ( Myanmar Time Zone )
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !==  null){
            return helper::daily_detail_c1d($date, $number);
        }
        return helper::daily_detail_c1d($curdate, $number);
    }
}
