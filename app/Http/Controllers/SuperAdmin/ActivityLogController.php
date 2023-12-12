<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now();
            $start = $start->format('Y-m-d');
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
            $end = $end->format('Y-m-d');
        }

        $activity_logs = ActivityLog::orderBy('id', 'DESC')->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"])->get();
        if($request->admin_id){
            $activity_logs = $activity_logs->where('super_admin_id', $request->admin_id);
        }
        $admin_staffs = SuperAdmin::where('role_id', '!=', 1)->get();
        return view('super_admin.activity_log.index',compact('activity_logs', 'admin_staffs'));
    }
}
