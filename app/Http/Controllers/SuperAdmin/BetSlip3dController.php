<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\Section;
use App\Models\UserBet;
use App\Invoker\invoke3D;
use App\Models\Section3d;
use App\Models\UserBet3d;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OverAllSetting;

class BetSlip3dController extends Controller
{
    public function bet_slip_3d(Request $request)
    {
        $bet_slips = UserBet3d::with('bettings_3d');

        if($request->month){
            $month = $request->month;
        }else{
            $month = date('m');
        }

        if($request->year){
            $year = $request->year;
        }else{
            $year = date('Y');
        }

        if($request->date3d){
            $bet_slips = $bet_slips->where('date_3d', $request->date3d);
        }



        if($request->side_stats){
            $bet_slips = $bet_slips->whereHas('user', function ($query) use($request)  {
               if($request->side_stats == 'myvip'){
                return $query->where('side',null);
               }elseif($request->side_stats == 'icasino'){
                return $query->where('side',1);
               }
            })->orderBy('id', 'DESC')->whereMonth('date', $month)->whereYear('date', $year)->get();
           }else{
            $bet_slips = $bet_slips->orderBy('id', 'DESC')->whereMonth('date', $month)->whereYear('date', $year)->get();
           }

        foreach($bet_slips as $bet_slip){
            $bet_slip->read = 1;
            $bet_slip->save();
        }

        $date = Section3d::all();
        $months = invoke3D::months();
        $cur_month = date('m');
        $years = invoke3D::years();
        $cur_year = date('Y');

        return view('super_admin.bet_slip_3d.bet_slip',compact('bet_slips', 'date', 'months','cur_month','years','cur_year'));
    }

    public function bet_slip_detail($id)
    {
        $bet_slip_detail = UserBet3d::findOrFail($id);
        $bet_slip_detail->read = 1;
        $bet_slip_detail->update();

        return view('super_admin.bet_slip_3d.bet_slip_detail',compact('bet_slip_detail'));
    }
}
