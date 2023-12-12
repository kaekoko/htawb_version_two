<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\helper;
use App\Models\CashOut;
use App\Invoker\invokeAll;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CashOutRequest;
use App\Http\Controllers\FirebaseController;

class CashOutController extends Controller
{
    public function index(Request $request)
    {
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now()->subDays(1);
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
        }

        $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->get();
        foreach($cash_outs as $cash_out){
            $cash_out->read = 1;
            $cash_out->save();
        }

        if($request->status){
            $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->get();
        }

        if($request->payment){
            $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('payment_id', $request->payment)->get();
        }

        if($request->side){
            if($request->side == 'myvip'){
                $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('side',null)->get();
               }else if($request->side == 'icasino'){
                $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('side', 1)->get();
               }else{
                $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->get();
               }
        }

        if($request->status && $request->side){
            if($request->side == 'myvip'){
                $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->where('side', null)->get();
               }else if($request->side == 'icasino'){
                $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->where('side', 1)->get();
               }else{
                $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->get();
               }
        }

        if($request->status && $request->payment && $request->side){
            if($request->side == 'myvip'){
                $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->where('payment_id', $request->payment)->where('side', null)->get();
               }else if($request->side == 'icasino'){
                $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->where('payment_id', $request->payment)->where('side', 1)->get();
               }else{
                $cash_outs = CashOut::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->where('payment_id', $request->payment)->get();
               }
        }

        $payments = CashOut::select(DB::raw('count(*) as user_count, payment_id'))->groupBy('payment_id')->get();

        return view('super_admin.cash_out.index', compact('cash_outs', 'payments'));
    }

    public function cashoutConfirm(Request $request, $id)
    {
        if (!empty($request->cashout_password)) {
            $admin = Auth::guard('super_admin')->user();

            if (Hash::check($request->cashout_password, $admin->password)) {
                $cash_out = CashOut::findOrFail($id);
                $user_name = User::where('id',$cash_out->user_id)->first();
                $user_name = $user_name->name;
                $cash_out->status = "Approve";
                $cash_out->update();

                //alert noti for user
                $title = 'Cash Out Approved';
                invokeAll::userAlertNoti($title, $cash_out->amount.' MMK Transfer From Admin', $cash_out->user_id);
                $firebasecontroller = new FirebaseController;
                $firebasecontroller->index($user_name . ' မှ ' . $cash_out->amount . ' kyats ထုတ်ယူခြင်းအောင်မြင်ပါသည်။');
                return redirect()->back()->with('flash_message', 'Approve');
            }else{
                return redirect()->back()->with('error_message', 'Incorrect your password');
            }
        } else {
            return redirect()->back()->with('error_message', 'Please enter your password');
        }

    }

    public function cashoutReject(Request $request, $id)
    {
        if (!empty($request->cashout_reject_password)) {
            $admin = Auth::guard('super_admin')->user();

            if (Hash::check($request->cashout_reject_password, $admin->password)) {
                $cash_out = CashOut::findOrFail($id);
                $cash_out->status = "Reject";
                $cash_out->message = $request->message;
                $cash_out->update();

                $user = User::findOrFail($request->user_id);
                $user->balance = $user->balance + $request->amount;
                $user->update();

                //alert noti for user
                $title = 'Cash Out Reject';
                invokeAll::userAlertNoti($title, $request->message, $cash_out->user_id);

                return redirect()->back()->with('flash_message', 'Reject');
            } else {
                return redirect()->back()->with('error_message', 'Incorrect your password');
            }

        } else {
            return redirect()->back()->with('error_message', 'Please enter your password');
        }

    }

    public function cash_out($id)
    {
        $user = User::findOrFail($id);
        $payments = PaymentMethod::where('status',0)->get();
        return view('super_admin.cash_out.cash_out_create',compact('user','payments'));
    }

    public function cash_out_create($id, CashOutRequest $request)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $user = User::findOrFail($id);
        $balance = $user->balance;

        if($request->amount <= $balance){
            $balance = $user->balance;
            $user->balance = $balance - $request->amount;
            $user->update();

            $datetime = helper::currentDateTime();
            $cash_out = new CashOut();
            $cash_out->user_id = $id;
            $cash_out->payment_id = $request->payment_id;
            $cash_out->status = "Approve";
            $cash_out->amount = $request->amount;
            $cash_out->account_name = $request->account_name ? $request->account_name : $user->name;
            $cash_out->user_phone = $request->user_phone ? $request->user_phone : $user->phone;
            $cash_out->old_amount = $balance;
            $cash_out->super_admin_id = $super_admin_id;
            $cash_out->date = $datetime['date'];
            $cash_out->time = $datetime['time'];
            $cash_out->save();
            return redirect('super_admin/user')->with('flash_message','Success Cash Out');
        }else{
            return redirect("super_admin/cash_out/$id")->with('error_message','Over Cash Out Limit');
        }
    }

    public function cash_out_history(Request $request,$id)
    {

        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now()->subDays(1);
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
        }

        $cash_outs = CashOut::where('user_id',$id)->orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->get();

        if ($request->status) {
            $cash_outs = CashOut::where('user_id',$id)->orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->get();
        }
        // $cash_outs = CashOut::where('user_id',$id)->orderBy('created_at', 'desc')->get();
        return view('super_admin.cash_out.cash_out_history',compact('cash_outs'));
    }
}
