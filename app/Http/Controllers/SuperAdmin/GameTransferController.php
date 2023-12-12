<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\TransferIn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TransferOut;

class GameTransferController extends Controller
{
    public function transfer_in(Request $request)
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

        $transfer_ins = TransferIn::orderBy('id', 'DESC')->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"])->get();
        return view('super_admin.game_transfer.transfer_in', compact('transfer_ins'));
    }

    public function transfer_out(Request $request)
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

        $transfer_outs = TransferOut::orderBy('id', 'DESC')->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"])->get();
        return view('super_admin.game_transfer.transfer_out', compact('transfer_outs'));
    }
}
