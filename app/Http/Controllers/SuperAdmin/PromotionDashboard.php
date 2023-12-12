<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\CashIn;
use App\Models\CashOut;
use App\Models\UserBet;
use App\Models\Promotion;
use App\Models\UserBet1d;
use App\Models\UserBet3d;
use App\Models\SuperAdmin;
use App\Models\PromoReport;
use Illuminate\Http\Request;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PromotionDashboard extends Controller
{
   public function dashboard(){
    $date = date('Y-m-d');
        $promotion = Promotion::select(
            DB::raw('SUM(total_bet) as total_bet'),
            DB::raw('SUM(promotion_amount) as promotion_amount')
        )
        ->whereDate('created_at', $date)
        ->where('status','0')
        ->first();

        $report = PromoReport::get();
    return view('super_admin.promotion.dashboard',compact('report','promotion'));
   }


   public function index(){
    $promo = Promotion::with('user')->get();
      return view('super_admin.promotion.index',compact('promo'));
   }

   public function approve(Request $request){
     
    $super_admin_id = Auth::guard('super_admin')->user()->id;
    $password = SuperAdmin::findOrFail($super_admin_id)->password;

    if($request->start_date == null || $request->end_date == null || $request->precent == null){
        return back()->with('error_message', 'Fill in the blank');
    }

    if (Hash::check($request->password, $password)) {
       
        $start = $request->start_date;
        $end =  $request->end_date;
        $ended = date('Y-m-d', strtotime($end . ' +1 day'));
        $users =  User::get();
        
        foreach($users as $u){
      
            $total_cashin =CashIn::select(DB::raw('SUM(amount) as amount'))
            ->where('user_id',$u->id)
            ->whereBetween('created_at', [$start, $ended])
            ->where('status','Approve')
            ->first();

            
            $total_cashout =CashOut::select(DB::raw('SUM(amount) as amount'))
            ->where('user_id',$u->id)
            ->whereBetween('created_at', [$start, $ended])
            ->where('status','Approve')
            ->first();


            $one = UserBet1d::select(DB::raw('SUM(total_amount) as one_bet_amount'))
            ->where('user_id',$u->id)
            ->whereBetween('date', [$start, $ended])
            ->first();


            $two = UserBet::select(DB::raw('SUM(total_amount) as two_bet_amount'))
            ->where('user_id',$u->id)
            ->whereBetween('date', [$start, $ended])
            ->first();


            $cryptoOne = UserBetCrypto1d::select(DB::raw('SUM(total_amount) as crypto_one_bet_amount'))
            ->where('user_id',$u->id)
            ->whereBetween('date', [$start, $ended])
            ->first();

            $cryptoTwo = UserBetCrypto2d::select(DB::raw('SUM(total_amount) as crypto_two_bet_amount'))
            ->where('user_id',$u->id)
            ->whereBetween('date', [$start, $ended])
            ->first();


            $three = UserBet3d::select(DB::raw('SUM(total_amount) as three_bet_amount'))
            ->where('user_id',$u->id)
            ->whereBetween('date', [$start, $ended])
            ->first();


            $result = 0;
            $total_cashin_amount = $total_cashin ? $total_cashin->amount : 0;
            $total_cashout_amount = $total_cashout ? $total_cashout->amount : 0;
            $one_bet_amount = $one ? $one->one_bet_amount : 0;
            $two_bet_amount = $two ? $two->two_bet_amount : 0;

            $crypto_one_bet_amount = $cryptoOne ? $cryptoOne->crypto_one_bet_amount : 0;
            $crypto_two_bet_amount = $cryptoTwo ? $cryptoTwo->crypto_two_bet_amount : 0;
            $three_bet_amount = $three ? $three->three_bet_amount : 0;

            $result = ($u->balance + $total_cashout_amount +  $one_bet_amount +  + $two_bet_amount + $crypto_one_bet_amount + $crypto_two_bet_amount  + $three_bet_amount) - $total_cashin_amount;

           
            if($result < 0){
                
                $precent =  $request->precent;
                
                $promo = new Promotion();
                $promo->cashin_amount = $total_cashin_amount;
                $promo->cashout_amount =  $total_cashout_amount;
                $promo->two_amount =  $two_bet_amount;
                $promo->three_amount =  $three_bet_amount;
                $promo->one_amount =  $one_bet_amount;
                $promo->crypto_one_amount =  $crypto_one_bet_amount;
                $promo->crypto_two_amount =  $crypto_two_bet_amount;
                $promo->promotion_amount = abs($result) * ($precent / 100);
                $promo->start_date = $request->start_date;
                $promo->end_date = $request->end_date;
                $promo->precent = $precent;
                $promo->total_bet = abs($result);
                $promo->user_id = $u->id;
                $promo->save();
            }
        }
        
        return back()->with('flash_message', 'Cash back promotion approve successful');
    }else {
        return back()->with('error_message', 'Super admin password worng!');
    }
   
}


    public function complete(Request $request){

        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $password = SuperAdmin::findOrFail($super_admin_id)->password;

        if (Hash::check($request->password, $password)) {
        
            $to_day = Carbon::now();

            $promo = Promotion::whereDate('created_at',$to_day)->get();
            foreach ($promo as $p) {
                $p->status ='1';
                $p->save();

                $user = User::where('id', $p->user_id)->first();
                $user->balance += $p->promotion_amount;

                $title = 'Myvip';
                $body = 'cash back promotion get ' . $p->promotion_amount. 'VND';
                sendNotification($title,$body,$user->id);
                $user->save();
                
            }
        
            $promotion = Promotion::select(
                'start_date',
                'end_date',
                DB::raw('SUM(total_bet) as total_bet'),
                DB::raw('SUM(promotion_amount) as promotion_amount')
            )
            ->whereDate('created_at', $to_day)
            ->where('status', '1')
            ->groupBy('start_date', 'end_date')
            ->first();

            $dailyReport = new PromoReport();
            $dailyReport->all_bet_amount = $promotion->total_bet;
            $dailyReport->all_payout_amount =  $promotion->promotion_amount;
            $dailyReport->start_date = $promotion->start_date;
            $dailyReport->end_date = $promotion->end_date;
            $dailyReport->save();
            return back()->with('flash_message', 'promotion Clearance successful');
        }else {
            return back()->with('error_message', 'Super admin password worng!');
        }
    }


    public function refund(Request $request){
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $password = SuperAdmin::findOrFail($super_admin_id)->password;
    
        if (Hash::check($request->password, $password)) {
            $to_day = Carbon::now();
            $promotions = Promotion::whereDate('created_at', $to_day)->get();
    
            if ($promotions->isNotEmpty()) {
                Promotion::whereDate('created_at', $to_day)->delete();
                return back()->with('flash_message', 'Promotion approve refund successful');
            } else {
                return back()->with('error_message', 'No promotions found for today');
            }
        } else {
            return back()->with('error_message', 'Super admin password wrong!');
        }
    }
}
