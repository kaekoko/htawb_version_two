<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\helper;
use App\Models\CryptoTwoD;
use App\Models\SuperAdmin;
use App\Models\LuckyNumber;
use Illuminate\Http\Request;
use App\Models\LotteryOffDay;
use App\Models\RefundHistory;
use App\Models\BettingCrypto2d;
use App\Models\SectionCrypto2d;
use App\Models\UserBetCrypto2d;
use App\Models\UserReferHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\NumberSettingCrypto2d;
use App\Models\OverAllSetting;

class DashboardCrypto2dController extends Controller
{
    public function dashboard()
    {
        $sections = SectionCrypto2d::all();
        return view("super_admin.dashboard_crypto_2d.section")->with([
            'sections' => $sections
        ]);
    }


    // Function to get formatted date
    private function getFormattedDate($requestDate)
    {
        return $requestDate ? $requestDate : date('Y-m-d');
    }

    // Function to get formatted time
    private function getFormattedTime($section)
    {
        return Carbon::createFromFormat('H:i:s', $section)->format('h:i A');
    }

    // Function to get block or hot data
    private function getBlockOrHotData($type, $date, $section)
    {
        $data = NumberSettingCrypto2d::where('type', $type)
            ->whereDate('created_at', $date)
            ->where('section', $this->getFormattedTime($section))
            ->first();

        return $data ? explode(',', $type == 'hot' ? $data->hot_number : $data->block_number) : [];
    }

    // Function to get block or hot data
    private function getBlockOrHotAmount($type, $date, $section)
    {
        $data = NumberSettingCrypto2d::where('type', $type)
            ->whereDate('created_at', $date)
            ->where('section', $this->getFormattedTime($section))
            ->first();

        return $data ? $data->hot_amount : 0;
    }


    // Function to get section data
    private function getSectionData($section)
    {
        return SectionCrypto2d::where('time_section', $section)
            ->first();
    }

    // Function to get user bets
    private function getUserBets($date, $formatTime)
    {
        return UserBetCrypto2d::with('bettings_c2d')
            ->where('date', $date)
            ->where('section', $formatTime)
            ->get();
    }

    // Function to get betting total
    private function getBettingTotal($date, $formatTime)
    {
        return BettingCrypto2d::select('bet_number', DB::raw('SUM(amount) as amount'))
            ->where('section', $formatTime)
            ->where('date', $date)
            ->groupBy('bet_number')
            ->get();
    }

    // Function to get overall stats
    private function getOverallStats($date, $formatTime)
    {
        return UserBetCrypto2d::select(
            DB::raw('SUM(total_amount) as total_amount'),
            DB::raw('SUM(reward_amount) as reward_amount')
        )
            ->where('date', $date)
            ->where('section', $formatTime)
            ->first();
    }

    private function getLuckyNumber($date, $formatTime)
    {
        return LuckyNumber::where('create_date', $date)
            ->where('category_id', 3)
            ->where('section', $formatTime)
            ->where('approve', 1)
            ->first();
    }

    public function section(Request $request, $section)
    {
        $loading = true;
        $two_ds = CryptoTwoD::all();
        $currentDate = Carbon::now();
        $two_lottery_off = false;

        $two_lottery_off_weekend = false;

        $date = $this->getFormattedDate($request->dashboard_date);
        $lottery_off_day = LotteryOffDay::where('off_day', $date)->where('category_id', 3)->get();


        foreach ($lottery_off_day as $n) {
            if ($date == $n->off_day) {
                $two_lottery_off = true;
            } else {
                $two_lottery_off = false;
            }
        }

        $formatTime =  $this->getFormattedTime($section);
        $block_number =  $this->getBlockOrHotData('block', $date, $section);
        $hot_number =  $this->getBlockOrHotData('hot', $date, $section);
        $hot_amount = $this->getBlockOrHotAmount('hot', $date, $section);
        $section =  $this->getSectionData($section);
        $betting =  $this->getUserBets($date, $formatTime);
        $betting_total =  $this->getBettingTotal($date, $formatTime);
        $total_amount =  $this->getOverallStats($date, $formatTime);
        $luckynumber  = $this->getLuckyNumber($date, $formatTime);
        if ($luckynumber) {
            $numberLucky = $luckynumber->lucky_number;
            $read = $luckynumber->read;
            $approve = $luckynumber->approve;
        } else {
            $numberLucky = '--';
            $read = '0';
            $approve = '0';
        }

        $overAll = OverAllSetting::first();

        $loading = false;
        return view('super_admin.dashboard_crypto_2d.index', compact('two_lottery_off_weekend', 'two_lottery_off', 'loading', 'two_ds', 'section', 'block_number', 'hot_number', 'hot_amount', 'betting', 'betting_total', 'total_amount', 'numberLucky', 'read', 'approve', 'overAll'));
    }

    public function clearance(Request $request)
    {
        $date = $request->date;
        $section = $request->section;
        // Category_id: 3, Crypto 2D
        $luckynumbers = LuckyNumber::where('section', $section)->where('category_id', 3)->where('create_date', $date)->select('read')->get();
        $approves = LuckyNumber::where('section', $section)->where('category_id', 3)->where('create_date', $date)->where('approve', 1)->select('approve')->get();

        $luckynumber = '';
        foreach ($luckynumbers as $luckynumber) {
            $luckynumber = $luckynumber->read;
        }

        $approve = '';
        foreach ($approves as $approve) {
            $approve = $approve->approve;
        }

        $now = Carbon::now()->toTimeString();
        $get_section = date("H:i:s", strtotime($section));
        if ($get_section > $now) {
            $time_check = 'open';
        } else {
            $time_check = 'close';
        }

        return [
            'section' => $section,
            'time_check' => $time_check,
            'lucky_number_read' => $luckynumber,
            'approve' => $approve,
        ];
    }

    public function clearance_money(Request $request)
    {
        $section = $this->getFormattedTime($request->section);
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $password = SuperAdmin::findOrFail($super_admin_id)->password;
        if (Hash::check($request->password, $password)) {
            // Category_id: 2, 2D
            $change_read = LuckyNumber::where('section', $section)->where('category_id', 3)->where('create_date', $request->date)->first();
            if ($change_read) {
                $change_read->read = 1;
                $change_read->save();
            }

            $total_status = UserBetCrypto2d::where('date', $request->date)->where('section', $section)->where('win', 1)->get();
            foreach ($total_status as $status) {
                $status->reward = 1;
                $status->claim = 1;
                $status->save();

                $user = User::where('id', $status->user_id)->first();
                $user->balance += $status->reward_amount;
                $user->save();
            }
            return redirect('super_admin/dashboard/sectionc2d/' . $request->section)->with('flash_message', $section . ' Clearance Success');
        } else {
            return redirect('super_admin/dashboard/sectionc2d/' . $request->section)->with('error_message', 'Your password incorrect');
        }
    }

    public function refund_money(Request $request)
    {
        $section = $this->getFormattedTime($request->section);
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $password = SuperAdmin::findOrFail($super_admin_id)->password;
        if (Hash::check($request->password, $password)) {

            $user_bets = UserBetCrypto2d::with('bettings_c2d')->where('section', $section)->where('date', $request->date)->get();
            foreach ($user_bets as $user_bet) {

                $refund_history = new RefundHistory();
                $refund_history->user_id = $user_bet->user_id;
                $refund_history->section = $user_bet->section;
                $refund_history->total_amount = $user_bet->total_amount;
                $refund_history->total_bet = $user_bet->total_bet;
                $refund_history->date = $user_bet->date;
                $refund_history->save();

                $user = User::find($user_bet->user_id);
                $user->balance = $user->balance + $user_bet->total_amount;
                $user->update();

                foreach ($user_bet->bettings_c2d as $betting) {
                    $betting = BettingCrypto2d::find($betting->id);
                    $betting->delete();
                }

                $user_bet = UserBetCrypto2d::find($user_bet->id);
                $user_bet->bettings_c2d()->detach();
                $user_bet->delete();

                $user_refunds = UserReferHistory::where('section', $section)->where('type', 'C2D')->whereDate('created_at', $request->date)->get();
                foreach ($user_refunds as $user_refund) {
                    $user_refund = UserReferHistory::find($user_refund->id);
                    $user_refund->delete();
                }
            }
            return redirect('super_admin/dashboard_c2d')->with('flash_message', $section . ' Refund Success');
        } else {
            return redirect('super_admin/dashboard_c2d')->with('error_message', 'Your password incorrect');
        }
    }

    public function time_statics(Request $request)
    {
        $time = $request->time;
        $date = $request->date;
        $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT + 6:30 ( Myanmar Time Zone )
        $curdate = gmdate('Y-m-d', time() + $diffWithGMT);
        if ($date !== null) {
            return helper::dashboard_c2d_section($time, $date);
        }
        return helper::dashboard_c2d_section($time, $curdate);
    }

    public function daily_statics(Request $request)
    {
        $date = $request->date;
        $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT + 06:30 ( Myanmar Time Zone )
        $curdate = gmdate('Y-m-d', time() + $diffWithGMT);
        if ($date !== null) {
            return helper::daily_statics_c2d($date);
        }
        return helper::daily_statics_c2d($curdate);
    }

    public function bet_slips(Request $request)
    {
        $bet_slips = [];
        $date = $request->date;
        $section = $request->section;
        $user_bets = UserBetCrypto2d::where('section', $section)->where('date', $date)->get();
        foreach ($user_bets as $user_bet) {

            if (isset($user_bet->user->super_admin->name)) {
                $agent_name = $user_bet->user->super_admin->name;
            }

            $bet = [
                "user_name" => $user_bet->user->name,
                "agent_name" => $agent_name,
                "section" => $user_bet->section,
                "total_amount" => $user_bet->total_amount,
                "total_bet" => $user_bet->total_bet,
                "date" => date('d-M-Y H:i:s', strtotime($user_bet->created_at)),
                "action" => $user_bet->id,
            ];
            array_push($bet_slips, $bet);
        }
        return $bet_slips;
    }

    public function daily_bet_slips(Request $request)
    {
        $bet_slips = [];
        $date = $request->date;
        $user_bets = UserBetCrypto2d::whereDate('date', $date)->get();
        foreach ($user_bets as $user_bet) {

            if (isset($user_bet->user->super_admin->name)) {
                $agent_name = $user_bet->user->super_admin->name;
            }

            $bet = [
                "user_name" => $user_bet->user->name,
                "agent_name" => $agent_name,
                "section" => $user_bet->section,
                "total_amount" => $user_bet->total_amount,
                "total_bet" => $user_bet->total_bet,
                "date" => date('d-M-Y H:i:s', strtotime($user_bet->created_at)),
                "action" => $user_bet->id,
            ];
            array_push($bet_slips, $bet);
        }
        return $bet_slips;
    }
}
