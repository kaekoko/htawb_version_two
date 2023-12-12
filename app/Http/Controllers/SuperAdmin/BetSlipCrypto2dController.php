<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\helper;
use App\Helper\HolidayHelper;
use App\Http\Controllers\Controller;
use App\Models\LotteryOffDay;
use App\Models\SectionCrypto2d;
use App\Models\UserBetCrypto2d;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BetSlipCrypto2dController extends Controller
{

    public function bet_slip_crypto_2d(Request $request)
    {
        $bet_slips = UserBetCrypto2d::with('bettings_c2d');

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

        $sections = SectionCrypto2d::all();
        return view('super_admin.bet_slip_crypto_2d.bet_slip')->with([
            'bet_slips' => $bet_slips,
            'sections' => $sections
        ]);
    }
    

    public function bet_slip_crypto_2d_detail($id)
    {
        $bet_slip_detail = UserBetCrypto2d::findOrFail($id);
        $bet_slip_detail->read = 1;
        $bet_slip_detail->update();
        return view('super_admin.bet_slip_crypto_2d.bet_slip_detail', [
            'bet_slip_detail' => $bet_slip_detail
        ]);
    }

    public function all_bets(Request $request)
    {
        $time = $request->time; //9:01 AM
        $date = $request->date; //created_at
        // category_id: 3, crypto_2D
        $lot_off = LotteryOffDay::where('category_id', 3)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "message" => "success",
                "status" => "close",
            ]);
        }
        return helper::time_filter_c2d($time, $date);
    }

    public function daily_all_bets(Request $request)
    {
        $date = $request->date; //created_at
        // category_id: 3, crypto_2D
        $lot_off = LotteryOffDay::where('category_id', 3)->pluck('off_day')->toArray();
        if (HolidayHelper::isTheDayHoliday($date, $lot_off) === 1) {
            return response()->json([
                "message" => "success",
                "status" => "close",
            ]);
        } 
        return helper::daily_filter_c2d($date);
    }

    public function single_bet_detail(Request $request){
        $time = $request->time;
        $date = $request->date;
        $number = $request->number;
        $diffWithGMT=6*60*60+30*60; // GMT + 06:30 ( Myanmar Time Zone )
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !==  null){
            return helper::single_detail_c2d($time, $date, $number);
        }
        return helper::single_detail_c2d($time, $curdate, $number);
    }

    public function daily_bet_detail(Request $request){
        $date = $request->date;
        $number = $request->number;
        $diffWithGMT=6*60*60+30*60; // GMT + 06:30 ( Myanmar Time Zone )
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !==  null){
            return helper::daily_detail_c2d($date, $number);
        }
        return helper::daily_detail_c2d($curdate, $number);
    }
}
