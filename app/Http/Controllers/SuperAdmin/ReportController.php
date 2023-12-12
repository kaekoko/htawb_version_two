<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\CashIn;
use App\Models\CashOut;
use App\Invoker\invoke3D;
use App\Models\DailyStatic;
use Illuminate\Http\Request;
use App\Models\DailyStatic1D;
use App\Models\BetDate3dHistory;
use Yajra\DataTables\DataTables;
use App\Models\DailyStaticCrypto1D;
use App\Models\DailyStaticCrypto2D;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function report_2d(Request $request)
    {
        if ($request->start_date) {
            $start = $request->start_date;
        } else {
            $start = Carbon::now()->subDays(29);
        }

        if ($request->end_date) {
            $end = $request->end_date;
        } else {
            $end = Carbon::now();
        }
        $daily_statics = DailyStatic::orderBy('id', 'DESC')->whereBetween('date', [$start, $end])->get();

        $all_bet_amount = DailyStatic::whereBetween('date', [$start, $end])->sum('all_bet_amount');
        $total_reward = DailyStatic::whereBetween('date', [$start, $end])->sum('total_reward');
        $profit = DailyStatic::whereBetween('date', [$start, $end])->sum('profit');
        $user_referral = DailyStatic::whereBetween('date', [$start, $end])->sum('user_referral');

        return view('super_admin.report.report_2d', compact('daily_statics', 'all_bet_amount', 'total_reward', 'profit', 'user_referral'));
    }

    public function report_1d(Request $request)
    {
        if ($request->start_date) {
            $start = $request->start_date;
        } else {
            $start = Carbon::now()->subDays(29);
        }

        if ($request->end_date) {
            $end = $request->end_date;
        } else {
            $end = Carbon::now();
        }
        $daily_statics = DailyStatic1D::orderBy('id', 'DESC')->whereBetween('date', [$start, $end])->get();

        $all_bet_amount = DailyStatic1D::whereBetween('date', [$start, $end])->sum('all_bet_amount');
        $total_reward = DailyStatic1D::whereBetween('date', [$start, $end])->sum('total_reward');
        $profit = DailyStatic1D::whereBetween('date', [$start, $end])->sum('profit');
        $user_referral = DailyStatic1D::whereBetween('date', [$start, $end])->sum('user_referral');

        return view('super_admin.report.report_1d', compact('daily_statics', 'all_bet_amount', 'total_reward', 'profit', 'user_referral'));
    }

    public function report_crypto_2d(Request $request)
    {
        if ($request->start_date) {
            $start = $request->start_date;
        } else {
            $start = Carbon::now()->subDays(29);
        }

        if ($request->end_date) {
            $end = $request->end_date;
        } else {
            $end = Carbon::now();
        }
        $daily_statics = DailyStaticCrypto2D::orderBy('id', 'DESC')->whereBetween('date', [$start, $end])->get();
        $all_bet_amount = DailyStaticCrypto2D::whereBetween('date', [$start, $end])->sum('all_bet_amount');
        $total_reward = DailyStaticCrypto2D::whereBetween('date', [$start, $end])->sum('total_reward');
        $profit = DailyStaticCrypto2D::whereBetween('date', [$start, $end])->sum('profit');
        $user_referral = DailyStaticCrypto2D::whereBetween('date', [$start, $end])->sum('user_referral');

        return view('super_admin.report.report_crypto_2d', compact('daily_statics', 'all_bet_amount', 'total_reward', 'profit', 'user_referral'));
    }

    public function report_crypto_1d(Request $request)
    {
        if ($request->start_date) {
            $start = $request->start_date;
        } else {
            $start = Carbon::now()->subDays(29);
        }

        if ($request->end_date) {
            $end = $request->end_date;
        } else {
            $end = Carbon::now();
        }
        $daily_statics = DailyStaticCrypto1D::orderBy('id', 'DESC')->whereBetween('date', [$start, $end])->get();
        $all_bet_amount = DailyStaticCrypto1D::whereBetween('date', [$start, $end])->sum('all_bet_amount');
        $total_reward = DailyStaticCrypto1D::whereBetween('date', [$start, $end])->sum('total_reward');
        $profit = DailyStaticCrypto1D::whereBetween('date', [$start, $end])->sum('profit');
        $user_referral = DailyStaticCrypto1D::whereBetween('date', [$start, $end])->sum('user_referral');

        return view('super_admin.report.report_crypto_1d', compact('daily_statics', 'all_bet_amount', 'total_reward', 'profit', 'user_referral'));
    }

    public function report_3d(Request $request)
    {
        if ($request->year) {
            $year = $request->year;
        } else {
            $year = date('Y');
        }

        $bet_date_3d_history = BetDate3dHistory::orderBy('id', 'DESC')->whereYear('bet_date', $year)->get();
        $all_bet_amount = BetDate3dHistory::whereYear('bet_date', $year)->sum('all_bet_amount');
        $total_reward = BetDate3dHistory::whereYear('bet_date', $year)->sum('total_reward');
        $profit = BetDate3dHistory::whereYear('bet_date', $year)->sum('profit');
        $user_referral = BetDate3dHistory::whereYear('bet_date', $year)->sum('user_referral');

        $years = invoke3D::years();
        $cur_year = date('Y');

        return view('super_admin.report.report_3d', compact('bet_date_3d_history', 'all_bet_amount', 'total_reward', 'profit', 'user_referral', 'years', 'cur_year'));
    }

    public function report_user()
    {
        $users = User::orderBy('id', 'DESC')->where('delete', 0)->get();
        $cash_ins = CashIn::orderBy('id', 'DESC')->get();
        $cash_outs = CashOut::orderBy('id', 'DESC')->get();
        return view('super_admin.report.report_user', compact('users', 'cash_ins', 'cash_outs'));
    }
}
