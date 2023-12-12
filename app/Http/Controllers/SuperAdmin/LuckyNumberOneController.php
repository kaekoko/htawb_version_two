<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\Betting1d;
use App\Models\Section1d;
use App\Models\UserBet1d;
use App\Invoker\invokeAll;
use App\Models\AutoRecord;
use App\Models\LuckyNumber;
use App\Models\CustomRecord;
use Illuminate\Http\Request;
use App\Models\CustomRecord1D;
use App\Http\Controllers\Controller;

class LuckyNumberOneController extends Controller
{
    public function index(Request $request)
    {
        if ($request->date) {
            $date = $request->date;
        } else {
            $date = Carbon::today();
        }

        $lucky_numbers = LuckyNumber::where('create_date', $date)->where('category_id', 4)->get();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d g:i A', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $format = strtotime($date[1] . ' ' . $date[2]);
        $customs = CustomRecord::get();
        $autos = AutoRecord::where('record_date', $date)->get();
        $format_date = strtotime($date[0]);
        $ten = strtotime('10 minutes');

        return view('super_admin.lucky_number.index_1d', compact('lucky_numbers', 'customs', 'format', 'ten', 'format_date', 'autos'));
    }

    public function add(Request $request,$id){
        $date =  date('Y-m-d');
     $already = LuckyNumber::where('create_date', $date)->where('category_id',4)->where('section',$id)->first();
        if($already){
            return back()->with('error_message', $id .' already been taken');
        }
        $date = Carbon::now();
        $lucky_number = New LuckyNumber();
        $lucky_number->lucky_number = $request->two_num;
        $lucky_number->section = $id;
        $lucky_number->category_id = 4;
        $lucky_number->create_date = Carbon::parse($date);
        $lucky_number->save();
        return redirect('super_admin/lucky_number_1d')->with('flash_message', $id .' Created');
    }

    public function approve_1d(Request $request, $id)
    {

        $lucky = LuckyNumber::findOrFail($id);

       

            $lucky->approve = 1;
            $lucky->update();

            $userbets = Betting1d::where('date', $lucky->create_date)->where('section', $lucky->section)->with('user_bets')->get();
            foreach ($userbets as $bet) {
                if ($bet->bet_number == $lucky->lucky_number) {
                    $bet->win = 1;
                    $bet->save();
                } else {
                    $bet->win = 2;
                    $bet->save();
                }
            }

            $bettings = UserBet1d::where('date', $lucky->create_date)->where('section', $lucky->section)->with('bettings')->get();
            foreach ($bettings as $b) {
                $check = $b->bettings->where('win', '=', 1)->first();
                if (!empty($check)) {
                    $b->win = 1;
                    $b->reward_amount = $check->amount * $check->odd;
                    $b->save();

                    //firebase win noti
                    invokeAll::winLuckyNumberNoti($b->user_id, $lucky->lucky_number);
                } else {
                    $b->win = 2;
                    $b->save();
                }
            }
        

        return redirect('super_admin/lucky_number_1d')->with('flash_message', 'Lucky Number Update');
    }

    public function create()
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d g:i A', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $customs = Section1d::where('is_open',1)->get();

        return view('super_admin.lucky_number.create_1d', compact('customs'));
    }

}
