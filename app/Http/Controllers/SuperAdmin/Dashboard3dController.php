<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ThreeD;
use App\Invoker\invoke3D;
use App\Models\Betting3d;
use App\Models\Section3d;
use App\Models\UserBet3d;
use App\Models\SuperAdmin;
use App\Models\LuckyNumber;
use Illuminate\Http\Request;
use App\Models\LotteryOffDay;
use App\Models\RefundHistory;
use App\Models\OverAllSetting;
use App\Models\NumberSetting3d;
use App\Models\BetDate3DHistroy;
use App\Models\UserReferHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Dashboard3dController extends Controller
{
    public function dashboard_3d(Request $request)
    {
        \Artisan::call('cache:clear');
        $sections = Section3d::get();
        return view('super_admin.dashboard.section_3d', compact('sections'));
    }

        // Function to get formatted date
        private function getFormattedDate($requestDate) {
            return $requestDate ? $requestDate : date('Y-m-d');
        }

        // Function to get block or hot data
        private function getBlockOrHotData($type) {
            $data = NumberSetting3d::where('type', $type)
                ->latest()
                ->first();

            return $data ? explode(',', $data->block_number) : [];
        }

        private function getBettingTotal($month,$year,$section) {

            $bet_date = $year.'-'.$month.'-'.$section;
            return Betting3d::select('bet_number', DB::raw('SUM(amount) as amount'))
                ->where('date_3d', $section)
                ->where('bet_date',$bet_date)
                ->groupBy('bet_number')
                ->get();
        }

        // Function to get user bets
        private function getUserBets($month,$year,$section) {
            $bet_date = $year.'-'.$month.'-'.$section;
            return UserBet3d::with('bettings_3d')
                ->where('bet_date',$bet_date)
                ->where('date_3d', $section)
                ->get();
        }


         // Function to get overall stats
        private function getOverallStats($month,$year,$section) {
            $bet_date = $year.'-'.$month.'-'.$section;
            return UserBet3d::select(
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('SUM(reward_amount) as reward_amount')
            )
            ->where('bet_date',$bet_date)
            ->where('date_3d', $section)
                ->first();
        }


        private function getLuckyNumber($current_month,$year,$section){
            $bet_date = date('Y-m-d');
            return LuckyNumber::where('create_date', $bet_date)->where('category_id', 2)
                    ->where('approve',1)
                    ->first();
        }
      

    public function section(Request $request,$section){
        $loading = true;
        $two_ds = ThreeD::all();
        $currentDate = Carbon::now();
        $two_lottery_off = false;
        $current_month = date('m');
       

        if($request->month){
            $month = $request->month;
        }else{
            $month = date('m',strtotime('+1 month'));
        }

        if($request->year){
            $year = $request->year;
        }else{
            $year = date('Y');
        }



        $date = $this->getFormattedDate($request->dashboard_date);
        $lottery_off_day = LotteryOffDay::where('off_day',$date)->where('category_id',2)->get();
        
        
        foreach($lottery_off_day as $n){
            if( $date == $n->off_day){
              $two_lottery_off = true;
            }else{
              $two_lottery_off = false;
            }
        }

        $section = Section3d::where('date',$section)->first();
        $block_number =  $this->getBlockOrHotData('block', $date);
        $betting_total =  $this->getBettingTotal($month,$year,$section->date);
        $betting =  $this->getUserBets($month,$year,$section->date);
        $total_amount =  $this->getOverallStats($month,$year,$section->date);
        $luckynumber =  $this->getLuckyNumber($current_month,$year,$section->date);
        if($luckynumber){
            $numberLucky = $luckynumber->lucky_number;
            $read = $luckynumber->read;
            $approve = $luckynumber->approve;
        }else{
            $numberLucky = '---';
            $read = '0';
            $approve = '0';
        }
        $loading = false;

        $months = invoke3D::months();
        $cur_month = date('m',strtotime('+1 month'));
        $t_month = date('m');
        $years = invoke3D::years();
        $cur_year = date('Y');
        return view('super_admin.dashboard.index_3d', compact('t_month','approve','read','numberLucky','total_amount','betting','cur_month','cur_year','months','years','two_lottery_off','two_ds','section','block_number','betting_total'));
    }

    public function grant_numbers_3d(Request $request)
    {
        $bet_date = $request->year.'-'.$request->month.'-'.$request->date3d;
        $res = [];
        $numbers = invoke3D::numbers();
        $queries = UserBet3d::where('bet_date', $bet_date)->with('bettings_3d')->get();

        if($queries->count() > 0){

            foreach($numbers as $num){

                $overall = OverAllSetting::first();
                $betting_amount = Betting3d::where('bet_date', $bet_date)->where('bet_number', $num->bet_number)->sum('amount');

                array_push($res, [
                    "id" => $num->id,
                    "bet_number" => $num->bet_number,
                    "amount" => (int) $betting_amount
                ]);

            }

            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);

        }else{
            foreach($numbers as $num){
                array_push($res, [
                    "id" => $num->id,
                    "bet_number" => $num->bet_number,
                    "amount" => 0
                ]);
            }
            return response()->json([
                "message" => "success",
                "status" => "open",
                "data" => $res
            ]);
        }

    }

    public function time_statics_3d(Request $request)
    {
        $res = [];
        $amt = [];
        $bet_date = $request->year.'-'.$request->month.'-'.$request->date3d;
        $bet_amounts = UserBet3d::where('bet_date', $bet_date)->sum('total_amount');
        $reward_amounts = UserBet3d::where('bet_date', $bet_date)->sum('reward_amount');

        $bet_counts = Betting3d::where('bet_date', $bet_date)->get();

        if($bet_counts->count() > 0){
            foreach($bet_counts as $count){
                $key = $count->bet_number ;
                    if (isset($res[$key])){
                        if ($res[$key]['bet_number'] === $count->bet_number){
                            $res[$key]['amount'] += $count->amount;
                        }
                    } else {
                        $res[$key] = $count;
                    }
            }

            $r_key = array_values($res);
            foreach($r_key as $k){
                array_push($amt, [
                    "amount" => (float) $k['amount'],
                    "bet_number" => $k['bet_number']
                ]);
            }
            $total_bet_numbers = count( $r_key);
            $max_bet_amount = max($amt);
        } else {
            $total_bet_numbers = 0;
            $max_bet_amount = 0;
        }

        $user_refer_history = UserReferHistory::where('bet_date_3d', $bet_date)->where('type', '3D')->sum('amount');
        $user_refer = (float) $user_refer_history;

        $sum_cost = $reward_amounts + $user_refer;
        if($sum_cost > 0){
            $profit = $bet_amounts - $sum_cost;
        } else {
            $profit = $bet_amounts;
        }


        $lucky = LuckyNumber::where('create_date', $bet_date)->where('approve', 1)->where('category_id', 2)->first();
        if(!empty($lucky)){
            $luck = $lucky->lucky_number;
        } else {
            $luck = '-';
        }

        return response()->json([
            "all_bet_amounts" => (float) $bet_amounts,
            "total_reward" => (float) $reward_amounts,
            "user_refer_total" => $user_refer,
            "profit" => $profit,
            "total_bet_number" => $total_bet_numbers,
            "most_amount_number" => $max_bet_amount,
            "lucky_number" => $luck,
        ]);

    }

    public function single_detail_3d(Request $request)
    {
        $final = [];
        $number = $request->number;
        $bet_date = $request->year.'-'.$request->month.'-'.$request->date3d;

        $arr = [
            [
            "id" => 'super_admin_id',
            "model" => 'super_admins'
            ],
        ];

        $bettings_3d = Betting3d::where('bet_date', $bet_date)->where('bet_number', $number)->with('user_bets_3d')->get();
        if($bettings_3d->count() > 0){
            foreach($bettings_3d as $bettings_3d){
                foreach($bettings_3d->user_bets_3d as $bet){
                    $ub = UserBet3d::where('id',$bet->id)->with('user')->first();
                    foreach($arr as $r){
                        $parentid = $r['id'];
                        if($ub->user->$parentid){
                            $find_parent = DB::table($r['model'])->where('id', $ub->user->$parentid)->first();
                            $bt = [
                                "amount" => $bettings_3d->amount,
                                "user_id" => $ub->user->id,
                                "name" => $ub->user->name,
                                "agent" => $find_parent->name
                            ];
                            array_push($final,$bt);
                        }
                    }
                }
            }
        }

        return $final;

    }

    public function hot_block_3d(Request $request)
    {
        $sections = NumberSetting3d::whereMonth('created_at', $request->month)->get();
        $hot_numbers = $sections->where('type', 'hot')->toArray();
        $hot_data = [];
        foreach($hot_numbers as $hn)
        {
            $n_array = [
                'hot_number' => $hn['hot_number'] != '-' && $hn['hot_number'] != null ? explode(',', $hn['hot_number']) : [],
                'amount' => $hn['hot_amount'] != null ? $hn['hot_amount'] : []
            ];
            array_push($hot_data, $n_array);
        }

        $block_numbers = $sections->where('type', 'block')->toArray();
        $block_data = [];
        foreach($block_numbers as $bn)
        {
            $n_array = [
                'block_number' => $bn['block_number'] != null ? explode(',', $bn['block_number']) : []
            ];
            array_push($block_data, $n_array);
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

    public function clearance_3d(Request $request)
    {

        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $password = SuperAdmin::findOrFail($super_admin_id)->password;
        if(Hash::check($request->password , $password)){

            $bet_date = $request->year.'-'.$request->month.'-'.$request->date3d;
            $change_read = LuckyNumber::where('create_date', $bet_date)->where('category_id', 2)->firstOrFail();
            $change_read->read = 1;
            $change_read->save();


            $total_status = UserBet3d::where('bet_date', $bet_date)->where('date_3d', $request->date3d)->where('win', 1)->get();
            foreach($total_status as $status){
                $status->reward = 1;
                // $status->claim = 1;
                $status->save();

                $user = User::where('id', $status->user_id)->first();
                $user->balance += $status->reward_amount;
                $user->save();
            }
            //number setting 3d or hot block 3d delete
            NumberSetting3d::truncate();
            return redirect('super_admin/dashboard/sectionc3d/'.$request->date3d)->with('flash_message', $bet_date . ' Clearance Success');
        }else{
            return redirect('super_admin/dashboard/sectionc3d/'.$request->date3d)->with('error_message', 'Your password incorrect');
        }
    }

    public function clearance_status_3d(Request $request)
    {
        $date = $request->year.'-'.$request->month.'-'.$request->date3d;
        // Category_id: 2, 3D
        $check_date = LuckyNumber::where('create_date', $date)->where('category_id', 2)->first();
        $approve = LuckyNumber::where('create_date', $date)->where('category_id', 2)->where('approve', 0)->where('read', 0)->first();
        $read = LuckyNumber::where('create_date', $date)->where('category_id', 2)->where('read', 1)->where('approve', 1)->first();

        if($check_date){
            if($approve){
                $status = 'yes';
            }elseif($read){
                $status = 'yes';
            }
            else{
                $status = 'no';
            }
        }else{
            $status = 'yes';
        }
        return $status;
    }

    public function refund_3d(Request $request)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $password = SuperAdmin::findOrFail($super_admin_id)->password;
        if(Hash::check($request->password , $password)){

            $bet_date = $request->year.'-'.$request->month.'-'.$request->date3d;
            $user_bets = UserBet3d::with('bettings_3d')->where('bet_date', $bet_date)->get();
            foreach($user_bets as $user_bet){

                $refund_history = new RefundHistory();
                $refund_history->user_id = $user_bet->user_id;
                $refund_history->section = $user_bet->date_3d;
                $refund_history->total_amount = $user_bet->total_amount;
                $refund_history->total_bet = $user_bet->total_bet;
                $refund_history->date = $user_bet->date;
                $refund_history->save();

                $user = User::find($user_bet->user_id);
                $user->balance = $user->balance + $user_bet->total_amount;
                $user->update();

                foreach($user_bet->bettings_3d as $betting){
                    $betting = Betting3d::find($betting->id);
                    $betting->delete();
                }

                $user_bet = UserBet3d::find($user_bet->id);
                $user_bet->bettings_3d()->detach();
                $user_bet->delete();

                $user_refunds = UserReferHistory::where('bet_date_3d', $bet_date)->get();
                foreach($user_refunds as $user_refund){
                    $user_refund = UserReferHistory::find($user_refund->id);
                    $user_refund->delete();
                }
            }
            return redirect()->back()->with('flash_message', 'Refund Success: ' . $bet_date);
        }else{
            return redirect()->back()->with('error_message', 'Your password incorrect');
        }
    }

}
