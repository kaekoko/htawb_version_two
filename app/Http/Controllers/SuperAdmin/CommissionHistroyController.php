<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UserReferHistory;
use App\Models\CommissionHistory;
use App\Http\Controllers\Controller;

class CommissionHistroyController extends Controller
{
    public function commission_history()
    {
        $commission_histories = CommissionHistory::orderBy('id', 'DESC')->get();
        return view('super_admin.commission.commission_history',compact('commission_histories'));
    }

    public function user_refer_history(Request $request)
    {
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now()->subDays(29);
            $start = $start->format('Y-m-d');
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
            $end = $end->format('Y-m-d');
        }

        $refer_histories = UserReferHistory::orderBy('id', 'DESC')->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"])->get();
        return view('super_admin.commission.user_refer_history', compact('refer_histories'));
    }
}
