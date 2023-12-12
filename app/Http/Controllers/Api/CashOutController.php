<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\helper;
use App\Models\CashOut;
use App\Models\Setting;
use App\Invoker\invokeAll;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\TelegramCashOutNotification;

class CashOutController extends Controller
{
    public function cashout(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'payment_id' => 'required',
            'amount' => 'required|numeric',
            'password' => 'required'
        ]);


        $user = User::where('id', $request->user_id)->first();

        // Check Password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 400,
                'message' => 'Your password is incorrect.'
            ], 400);
        }

        if ($user) {
            $cashout_minimum_amount = Setting::where('key', 'cash_out_minimum_amount')->first();

            if ($request->amount >= $cashout_minimum_amount->value ?? 0) {
                if ($request->amount <= $user->balance) {
                    $datetime = helper::currentDateTime();

                    $cash_out = new CashOut();
                    $cash_out->user_id = $request->user_id;
                    $cash_out->payment_id = $request->payment_id;
                    $cash_out->account_name = $request->account_name ? $request->account_name : $user->name;
                    $cash_out->user_phone = $request->user_phone ? $request->user_phone : $user->phone;
                    $cash_out->amount = $request->amount;
                    $cash_out->old_amount = $user->balance;
                    $cash_out->date = $datetime['date'];
                    $cash_out->time = $datetime['time'];

                    if($request->side != null){
                        $cash_out->side = $request->side;
                    }
                    $cash_out->save();

                    $user->balance = $user->balance - $request->amount;
                    $user->update();

                    //alert noti for all admin
                    $body = 'User Cash Out';
                    invokeAll::adminAlertNoti($body);
                    $cashstatus='cash-out';
                    $user->notify(new TelegramCashOutNotification($cash_out));

                    return response()->json([
                        'message' => 'CashOut Request Success'
                    ], 200);
                } else {
                    return response()->json('Not enough amount', 400);
                }
            } else {
                return response()->json([
                    "message" => "To withdraw money, you must withdraw at least ($cashout_minimum_amount->value) kyats."
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'User not found'
            ], 401);
        }
    }

    public function cash_out_history(Request $request)
    {
        $user_id = Auth::user()->id;
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        if ($start_date) {
            $start = $start_date;
        } else {
            $start = Carbon::now();
        }

        if ($end_date) {
            $end = $end_date;
        } else {
            $end = Carbon::now();
        }

        $cash_out_history = CashOut::where('user_id', $user_id)->orderBy('id', 'desc')->whereBetween('date', [$start, $end])->get();

        return response()->json([
            'data' => $cash_out_history
        ], 200);
    }

    public function cashOutAmount()
    {
        $cash_out_amount = Setting::where('key', 'cash_out_amount')->first();
        if (!empty($cash_out_amount->value)) {
            $data = explode(",", $cash_out_amount->value);
            return response()->json(['data' => $data], 200);
        }

        return response()->json([
            'data' => []
        ], 200);
    }
}
