<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Helper\SmaOne;
use App\Models\User;
use App\Models\Agent;
use App\Helper\helper;
use App\Models\Section;
use App\Models\UserBet;
use App\Models\Section1d;
use App\Models\UserBet1d;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use Illuminate\Http\Request;
use App\Helper\HolidayHelper;
use App\Models\LotteryOffDay;
use App\Http\Controllers\Controller;

class BetSlip1dController extends Controller
{
    public function bet_slip(Request $request)
    {
        $bet_slips = UserBet1d::with(['bettings','user']);

        if($request->date){
            $date = $request->date;
        }else{
            $date = Carbon::today();
        }

        if($request->section){
            $bet_slips = $bet_slips->where('section', $request->section);
        }

        // $bet_slips = $bet_slips->orderBy('id', 'DESC')->whereDate('date', $date)->get();
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

        $sections = Section1d::where("is_open", 1)->get();
    
        return view('super_admin.bet_slip_1d.bet_slip',compact('bet_slips','sections'));
    }

    public function bet_slip_detail($id)
    {
        $bet_slip_detail = UserBet1d::findOrFail($id);
        $bet_slip_detail->read = 1;
        $bet_slip_detail->update();

        return view('super_admin.bet_slip_1d.bet_slip_detail',compact('bet_slip_detail'));
    }

    public function pay_reward($id,$amount)
    {
        $pay_reward = UserBet1d::findOrFail($id);
        $pay_reward->reward = 1;
        $pay_reward->update();

        $user = User::find($pay_reward->user_id);
        $user->balance = $user->balance + $amount;
        $user->update();

        return redirect("super_admin/bet_slip_1d/$id")->with('flash_message','Pay Reward Successfully');
    }

    public function all_bets(Request $request){
        $time = $request->time; //9:01 AM
        $date = $request->date; //created_at
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        $now = Carbon::now();
        $dayname = date('l', strtotime($curdate));
        $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
        if(HolidayHelper::isTheDayHoliday($date, $lot_off) === 1){
            return response()->json([
                "message" => "success",
                "status" => "close",
            ]);
        } else {
            if(HolidayHelper::isThatWeekend($date) == 1){
                return response()->json([
                    "message" => "success",
                    "status" => "close",
                ]);
            }
            return helper::time_filter_1d($time, $date);
        }
    }

    public function single_bet_detail(Request $request){
        $time = $request->time;
        $date = $request->date;
        $number = $request->number;
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !==  null){
            return helper::single_detail_1d($time, $date, $number);
        }
        return helper::single_detail_1d($time, $curdate, $number);
    }

    public function daily_bet_detail(Request $request){
        $time = $request->time;
        $date = $request->date;
        $number = $request->number;
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !==  null){
            return helper::daily_filter_1d($date, $number);
        }
        return helper::daily_filter_1d($curdate, $number);
    }

    public function daily_all_bets(Request $request){
        $date = $request->date; //created_at
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        $now = Carbon::now();
        $dayname = date('l', strtotime($curdate));

        $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
        if(HolidayHelper::isTheDayHoliday($date, $lot_off) === 1){
            return response()->json([
                "message" => "success",
                "status" => "close",
            ]);
        } else {
            if(HolidayHelper::isThatWeekend($date) == 1){
                return response()->json([
                    "message" => "success",
                    "status" => "close",
                ]);
            }
            return helper::daily_filter_1d($date);
        }
    }

    public function sma_section_grant_number(Request $request, $id){
        $time = $request->time; //9:01 AM
        $date = $request->date; //created_at
        $column = $request->column;
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        $now = Carbon::now();
        $dayname = date('l', strtotime($curdate));
        $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
        if(HolidayHelper::isTheDayHoliday($date, $lot_off) === 1){
            return response()->json([
                "message" => "success",
                "status" => "close",
            ]);
        } else {
            if(HolidayHelper::isThatWeekend($date) == 1){
                return response()->json([
                    "message" => "success",
                    "status" => "close",
                ]);
            }
            return Sma::sma_grant_numbers($time, $date,$id, $column);
        }
    }

    public function sma_daily_grant_number(Request $request, $id){
        $date = $request->date; //created_at
        $column = $request->column;
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        $now = Carbon::now();
        $dayname = date('l', strtotime($curdate));

        $lot_off = LotteryOffDay::where('category_id', 4)->pluck('off_day')->toArray();
        if(HolidayHelper::isTheDayHoliday($date, $lot_off) === 1){
            return response()->json([
                "message" => "success",
                "status" => "close",
            ]);
        } else {
            if(HolidayHelper::isThatWeekend($date) == 1){
                return response()->json([
                    "message" => "success",
                    "status" => "close",
                ]);
            }
            return Sma::sma_grant_numbers_daily($date,$id, $column);
        }
    }

    public function sma_single_detail(Request $request, $id){
        $time = $request->time;
        $date = $request->date;
        $column = $request->column;
        $number = $request->number;
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !==  null){
            return Sma::sma_single_detail($time, $date, $number, $column, $id);
        }
        return Sma::sma_single_detail($time, $curdate, $number, $column, $id);
    }

    public function sma_daily_detail(Request $request, $id){
        $date = $request->date;
        $number = $request->number;
        $column = $request->column;
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !==  null){
            return Sma::sma_daily_detail($date, $number, $column, $id);
        }
        return Sma::sma_daily_detail($curdate, $number, $column, $id);
    }

    public function sma_section_statics(Request $request, $id){
        $time = $request->time;
        $date = $request->date;
        $column = $request->column;
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !== null){
            return Sma::sma_section_statics($time,$date,$column,$id);
        }

        return Sma::sma_section_statics($time,$curdate,$column,$id);
    }

    public function sma_daily_statics(Request $request, $id){
        $date = $request->date;
        $column = $request->column;
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        if($date !== null){
            return Sma::sma_daily_statics($date,$column,$id);
        }
        return Sma::sma_daily_statics($curdate,$column,$id);
    }
}
