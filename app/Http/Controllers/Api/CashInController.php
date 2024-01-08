<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\helper;
use App\Models\CashIn;
use App\Models\Setting;
use App\Invoker\invokeAll;
use App\Models\PromoCashIn;
use App\Models\WaveOpenOff;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\OverAllSetting;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\FirebaseController;
use App\Notifications\TelegramCashInNotification;

class CashInController extends Controller
{
    public function cashin(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'payment_id' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($request->is_mobile_topup) {
            $this->validate($request, [
                'voucher_code' => 'required|unique:cash_ins'
            ]);
        } else {
            if ($request->is_foregin_bank) {
                $this->validate($request, [
                    'holder_phone' => 'required',
                    'transaction_id' => 'required|max:200',
                    'cash_in_photo' => 'required|image|max:1024'
                ]);
                // check only one day
                $endDate = Carbon::now();  // Get the current date and time
                $startDate = Carbon::now()->subDays(2);

                $already_existed = CashIn::whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->where('foreign_transaction_id', $request->transaction_id)
                    ->get();
                if (count($already_existed) > 0) {
                    return response()->json([
                        'message' => 'The transaction ID has already been taken.'
                    ], 400);
                }
                if ($request->hasFile('cash_in_photo') && $request->file('cash_in_photo')) {
                    $cash_in_photo =  $request->file('cash_in_photo')->store('cash_in');
                }
            } else {
                $this->validate($request, [
                    'holder_phone' => 'required',
                    'transaction_id' => 'required|max:200',
                ]);

                if($request->payment_id != 1){
                    $endDate = Carbon::now();  // Get the current date and time
                    $startDate = Carbon::now()->subDays(2);
                    $pending_exist = CashIn::whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->where('user_id', $request->user_id)
                    ->where('status','Pending')
                    ->get();

                    if (count($pending_exist) > 0) {
                        return response()->json([
                            'message' => 'ယခုလုပ်ငန်းစဉ်နံပါတ်အား တင်ထားပြီးပြီးဖြင့်ပါသဖြင့် ခေတ္တစောင့်ဆိုင်းပေးပါရှင့်'
                        ], 400);
                    }
                }
                // check only one day
                $endDate = Carbon::now();  // Get the current date and time
                $startDate = Carbon::now()->subDays(2);  // Subtract 2 days from the current date and time
                $already_existed = CashIn::where('transaction_id', $request->transaction_id)
                    ->where('status', 'Approve')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();
                if (count($already_existed) > 0) {
                    return response()->json([
                        'message' => 'The transaction ID has already been taken.'
                    ], 400);
                }
            }
        }

        $user = User::where('id', $request->user_id)->first();
        $cashin_minimum_amount = Setting::where('key', 'cash_in_minimum_amount')->first();
        if ($request->is_foregin_bank) {
            $pay_method = PaymentMethod::where('id', $request->payment_id)->first();
            if (!empty($cashin_minimum_amount) && !empty($pay_method)) {
                $req_amount = $request->amount * $pay_method->exchange_rate;
            }
        } else {
            $req_amount = $request->amount;
        }
        if ($req_amount >= $cashin_minimum_amount->value ?? 0) {
            $datetime = helper::currentDateTime();
            $cash_in = new CashIn();
            $cash_in->user_id = $request->user_id;
            $cash_in->payment_id = $request->payment_id;

            if ($request->promo_id) {
                $has_got_promo = CashIn::where('user_id', $user->id)
                    ->whereNotNull('promo_id') // promotion
                    ->where('date', date('Y-m-d')) // current day
                    ->first();
                if (!empty($has_got_promo)) {
                    return response()->json([
                        'message' => 'You already have got a promotion today. Please, try again the next day.'
                    ], 400);
                }
                $cash_in->promo_id = $request->promo_id;
                $promo_cash_in = PromoCashIn::where('id', $request->promo_id)->first();
                $cash_in->promo_percent = $promo_cash_in->percent;
            }
            // If cash in type is mobile_top_up,...
            if ($request->is_mobile_topup) {
                $pay_method = PaymentMethod::where('id', $request->payment_id)->first();
                $cut = ($pay_method->percentage / 100) * $request->amount;
                $cash_in_amount = $request->amount - $cut;
                $cash_in->voucher_code = $request->voucher_code;
            } else {
                $cash_in_amount = $request->amount;
            }
            $cash_in->account_name = $request->account_name ? $request->account_name : $user->name;
            if ($request->is_foregin_bank) {
                $cash_in->foreign_transaction_id = $request->transaction_id;
                $cash_in->cash_in_photo = $cash_in_photo;
            } else {
                $cash_in->transaction_id = $request->transaction_id;
            }
            $cash_in->user_phone = $request->user_phone ? $request->user_phone : $user->phone;
            $cash_in->amount = $cash_in_amount;
            $cash_in->holder_phone = $request->holder_phone;
            $cash_in->old_amount = $user->balance;
            $cash_in->date = $datetime['date'];
            $cash_in->time = $datetime['time'];

            $cash_in->save();
            
            //alert noti for all admin
            $body = 'User Cash In';
            invokeAll::adminAlertNoti($body);
            // $user->notify(new TelegramCashInNotification($cash_in));

            return response()->json([
                'message' => 'CashIn Request Success'
            ], 200);
        } else {
            if ($request->is_foregin_bank) {
                $price = $cashin_minimum_amount->value / $pay_method->exchange_rate;
                return response()->json([
                    "message" => "Please cash in at least (" . round($price, 2) . ") Baht."
                ], 400);
            } else {
                return response()->json([
                    "message" => "To top up, you must top up at least ($cashin_minimum_amount->value) Kyats"
                ], 400);
            }
        }
    }

    public function cash_in_history(Request $request)
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

        $cash_in_history = CashIn::where('user_id', $user_id)->orderBy('id', 'desc')->whereBetween('date', [$start, $end])->get();

        return response()->json([
            'data' => $cash_in_history
        ], 200);
    }

    public function cashInAmount()
    {
        $cash_in_amount = Setting::where('key', 'cash_in_amount')->first();
        if (!empty($cash_in_amount->value)) {
            $data = explode(",", $cash_in_amount->value);
            return response()->json(['data' => $data], 200);
        }

        return response()->json([
            'data' => []
        ], 200);
    }

    public function check_game_balance()
    {
        $user_id = Auth::user()->id;
        $get_balance = new FirebaseController();
        $user_balance = $get_balance->getValue($user_id);

        if ($user_balance['last_balance'] > 1000) {
            return response()->json([
                'message' => 'Your game balance more than 1000'
            ], 400);
        }

        return response()->json([
            'message' => 'ok'
        ], 200);
    }
}
