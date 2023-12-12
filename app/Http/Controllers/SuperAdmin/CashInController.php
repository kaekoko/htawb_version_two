<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\helper;
use App\Models\CashIn;
use App\Invoker\invokeAll;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashInRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CashInController extends Controller
{
    public function index(Request $request)
    {
        if ($request->start_date) {
            $start = $request->start_date;
        } else {
            $start = Carbon::now()->subDays(1);
        }

        if ($request->end_date) {
            $end = $request->end_date;
        } else {
            $end = Carbon::now();
        }



        $cash_ins = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->paginate(10);
        $total = CashIn::whereBetween('date', [$start, $end])->sum('amount');

        foreach ($cash_ins as $cash_in) {
            $cash_in->read = 1;
            $cash_in->save();
        }

        if ($request->status) {

            $query = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status);

            if ($request->start_time) {
                $query->whereTime('created_at', '>=', $request->start_time);
            }

            if ($request->end_time) {
                $query->whereTime('created_at', '<=', $request->end_time);
            }

            if ($request->start_time && $request->end_time) {
                $query->whereTime('created_at', '>=', $request->start_time)
                    ->whereTime('created_at', '<=', $request->end_time);
            }

            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');

        }

        if($request->start_time) {
            $cash_ins = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->whereTime('created_at','>=', $request->start_time)->paginate(10);
            $total = CashIn::whereBetween('date', [$start, $end])->whereTime('created_at','>=', $request->start_time)->sum('amount');
        }

        if($request->end_time) {
            $cash_ins = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->whereTime('created_at','<=', $request->end_time)->paginate(10);
            $total = CashIn::whereBetween('date', [$start, $end])->whereTime('created_at','<=', $request->end_time)->sum('amount');
        }




        if ($request->promo) {
            $cash_ins = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->whereNotNull('promo_id')->paginate(10);
            $total = CashIn::whereBetween('date', [$start, $end])->whereNotNull('promo_id')->sum('amount');
        }

        if ($request->payment) {

            $query = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('payment_id', $request->payment);

            if ($request->start_time) {
                $query->whereTime('created_at', '>=', $request->start_time);
            }

            if ($request->end_time) {
                $query->whereTime('created_at', '<=', $request->end_time);
            }

            if ($request->start_time && $request->end_time) {
                $query->whereTime('created_at', '>=', $request->start_time)
                    ->whereTime('created_at', '<=', $request->end_time);
            }

            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');
        }


         if($request->start_time && $request->end_time){
            $cash_ins = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->whereTime('created_at','>=', $request->start_time)->whereTime('created_at','<=', $request->end_time)->paginate(10);
            $total = CashIn::whereBetween('date', [$start, $end])->whereTime('created_at','>=', $request->start_time)->whereTime('created_at','<=', $request->end_time)->sum('amount');
        }

        if ($request->side) {
            $query = CashIn::orderBy('created_at', 'desc')
            ->whereBetween('date', [$start, $end]);

                if ($request->side == 'myvip') {
                    $query->where('side', null);
                } elseif ($request->side == 'icasino') {
                    $query->where('side', 1);
                }

                if ($request->start_time) {
                    $query->whereTime('created_at', '>=', $request->start_time);
                }

                if ($request->end_time) {
                    $query->whereTime('created_at', '<=', $request->end_time);
                }

                if ($request->start_time && $request->end_time) {
                    $query->whereTime('created_at', '>=', $request->start_time)
                            ->whereTime('created_at', '<=', $request->end_time);
                }

                $cash_ins = $query->paginate(10);
                $total = $query->sum('amount');

        }

        if ($request->status && $request->promo) {
            $query = CashIn::orderBy('created_at', 'desc')
                ->whereBetween('date', [$start, $end])
                ->where('status', $request->status)
                ->whereNotNull('promo_id');

            if ($request->start_time) {
                $query->whereTime('created_at', '>=', $request->start_time);
            }

            if ($request->end_time) {
                $query->whereTime('created_at', '<=', $request->end_time);
            }

            if ($request->start_time && $request->end_time) {
                $query->whereTime('created_at', '>=', $request->start_time)
                    ->whereTime('created_at', '<=', $request->end_time);
            }

            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');

        }


        if ($request->status) {
            $query = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status);

            if ($request->side == 'myvip') {
                $query->where('side', null);
            } elseif ($request->side == 'icasino') {
                $query->where('side', 1);
            }

            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');
        }

        if ($request->status && $request->start_time) {
            $query = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->whereTime('created_at', '>=', $request->start_time);

            if ($request->side == 'myvip') {
                $query->where('side', null);
            } elseif ($request->side == 'icasino') {
                $query->where('side', 1);
            }

            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');
        }

        if ($request->status &&  $request->end_time) {
            $query = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->whereTime('created_at', '<=', $request->end_time);

            if ($request->side == 'myvip') {
                $query->where('side', null);
            } elseif ($request->side == 'icasino') {
                $query->where('side', 1);
            }

            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');
        }
        if ($request->status && $request->start_time && $request->end_time) {
            $query = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->whereTime('created_at', '>=', $request->start_time)->whereTime('created_at', '<=', $request->end_time);

            if ($request->side == 'myvip') {
                $query->where('side', null);
            } elseif ($request->side == 'icasino') {
                $query->where('side', 1);
            }

            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');
        }

        if ($request->side && $request->payment) {
            $query = CashIn::orderBy('created_at', 'desc')
                ->whereBetween('date', [$start, $end])
                ->where('payment_id', $request->payment);

            if ($request->side == 'myvip') {
                $query->where('side', null);
            } elseif ($request->side == 'icasino') {
                $query->where('side', 1);
            }

            if ($request->start_time) {
                $query->whereTime('created_at', '>=', $request->start_time);
            }

            if ($request->end_time) {
                $query->whereTime('created_at', '<=', $request->end_time);
            }

            if ($request->start_time && $request->end_time) {
                $query->whereTime('created_at', '>=', $request->start_time)
                        ->whereTime('created_at', '<=', $request->end_time);
            }

            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');
        }

        if ($request->status && $request->payment) {
            $query = CashIn::orderBy('created_at', 'desc')
            ->whereBetween('date', [$start, $end])
            ->where('status', $request->status)
            ->where('payment_id', $request->payment);

            if ($request->start_time) {
                $query->whereTime('created_at', '>=', $request->start_time);
            }

            if ($request->end_time) {
                $query->whereTime('created_at', '<=', $request->end_time);
            }

            if ($request->start_time && $request->end_time) {
                $query->whereTime('created_at', '>=', $request->start_time)
                ->whereTime('created_at', '<=', $request->end_time);
            }
            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');
        }

        if ($request->promo && $request->payment) {
            $cash_ins = CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->whereNotNull('promo_id')->where('payment_id', $request->payment)->paginate(10);
            $total = CashIn::whereBetween('date', [$start, $end])->whereNotNull('promo_id')->where('payment_id', $request->payment)->sum('amount');
        }

        if ($request->status && $request->promo && $request->payment) {
            $query =  CashIn::orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])
            ->where('status', $request->status)->whereNotNull('promo_id')
            ->where('payment_id', $request->payment);

            if ($request->side == 'myvip') {
                $query->where('side', null);
            } elseif ($request->side == 'icasino') {
                $query->where('side', 1);
            }

            if ($request->start_time) {
                $query->whereTime('created_at', '>=', $request->start_time);
            }

            if ($request->end_time) {
                $query->whereTime('created_at', '<=', $request->end_time);
            }

            if ($request->start_time && $request->end_time) {
                $query->whereTime('created_at', '>=', $request->start_time)
                ->whereTime('created_at', '<=', $request->end_time);
            }
            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');

        }

        if ($request->status && $request->payment && $request->side) {
            $query = CashIn::orderBy('created_at', 'desc')
                ->whereBetween('date', [$start, $end])
                ->where('status', $request->status)
                ->where('payment_id', $request->payment);

            if ($request->side == 'myvip') {
                $query->where('side', null);
            } elseif ($request->side == 'icasino') {
                $query->where('side', 1);
            }

            if ($request->start_time) {
                $query->whereTime('created_at', '>=', $request->start_time);
            }

            if ($request->end_time) {
                $query->whereTime('created_at', '<=', $request->end_time);
            }

            if ($request->start_time && $request->end_time) {
                $query->whereTime('created_at', '>=', $request->start_time)
                ->whereTime('created_at', '<=', $request->end_time);
            }
            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');
        }



        if ($request->status && $request->promo && $request->payment && $request->side) {
            $query = CashIn::orderBy('created_at', 'desc')
                ->whereBetween('date', [$start, $end])
                ->where('status', $request->status)
                ->whereNotNull('promo_id')
                ->where('payment_id', $request->payment);

            if ($request->side == 'myvip') {
                $query->where('side', null);
            } elseif ($request->side == 'icasino') {
                $query->where('side', 1);
            }

            if ($request->start_time) {
                $query->whereTime('created_at', '>=', $request->start_time);
            }

            if ($request->end_time) {
                $query->whereTime('created_at', '<=', $request->end_time);
            }

            if ($request->start_time && $request->end_time) {
                $query->whereTime('created_at', '>=', $request->start_time)
                ->whereTime('created_at', '<=', $request->end_time);
            }

            $cash_ins = $query->paginate(10);
            $total = $query->sum('amount');
        }


        $payments = CashIn::select(DB::raw('count(*) as user_count, payment_id'))->groupBy('payment_id')->get();


        return view('super_admin.cash_in.index', compact('cash_ins', 'payments', 'total'));
    }
    public function search(Request $request)
    {
        $searchString = $request->input('search');

        // Search in the title and body columns from the posts table
        $cash_ins = CashIn::whereHas('user', function ($query) use ($searchString) {
            $query->where('phone', '=', $searchString)
                ->orWhere('transaction_id', '=', $searchString)
                ->orWhere('name', 'like', '%' . $searchString . '%');
        })->paginate(20);

        $total = CashIn::whereHas('user', function ($query) use ($searchString) {
            $query->where('phone', '=', $searchString)
                ->orWhere('transaction_id', '=', $searchString)
                ->orWhere('name', 'like', '%' . $searchString . '%');
        })->sum('amount');

        $payments = CashIn::select(DB::raw('count(*) as user_count, payment_id'))->groupBy('payment_id')->get();

        return view('super_admin.cash_in.index', compact('cash_ins', 'payments', 'total'));
    }
    public function cashinConfirm(Request $request, $id)
    {
        if (!empty($request->cashin_password)) {
            $admin = Auth::guard('super_admin')->user();

            if (Hash::check($request->cashin_password, $admin->password)) {
                $user = User::findOrFail($request->user_id);
                $token = $user->id;
                $cash_in = CashIn::findOrFail($id);
                $cash_in->status = "Approve";
                $cash_in->old_amount = $user->balance;
                $cash_in->update();

                if ($cash_in->promo_id) {
                    invokeAll::promoTurnover($cash_in);
                } else {
                    $user->balance = $user->balance + $request->amount;
                    $user->update();
                }
                //spin wheel count for user
                invokeAll::spinWheelUserCount($user->id, $request->amount);

                //alert noti for user
                $title = 'Cash In Approve';
                $body  = 'ငွေဖြည့်သွင်းမူအောင်မြင်ပါသည်';
                invokeAll::userAlertNoti($title, number_format($request->amount) . 'ငွေဖြည့်သွင်းမူအောင်မြင်ပါသည်', $user->id);

                sendNotification($title, $body, $token);
                return redirect()->back()->with('flash_message', 'Approve');
            } else {
                return redirect()->back()->with('error_message', 'Incorrect your password');
            }
        } else {
            return redirect()->back()->with('error_message', 'Please enter your password');
        }
    }

    public function cashinReject(Request $request, $id)
    {
        if (!empty($request->reject_password)) {
            $admin = Auth::guard('super_admin')->user();
            $user = User::findOrFail($request->user_id);
            $token = $user->id;
            if (Hash::check($request->reject_password, $admin->password)) {
                $cash_in = CashIn::findOrFail($id);
                $cash_in->status = "Reject";
                $cash_in->message = $request->message;
                $cash_in->update();

                //alert noti for user
                $title = 'Cash In Reject';
                $body  = 'ငွေဖြည့်သွင်းမူ မ အောင်မြင်ပါ';
                invokeAll::userAlertNoti($title, $request->message, $cash_in->user_id);
                sendNotification($title, $body, $token);
                return redirect()->back()->with('flash_message', 'Reject');
            } else {
                return redirect()->back()->with('error_message', 'Incorrect your password');
            }
        } else {
            return redirect()->back()->with('error_message', 'Please enter your password');
        }
    }

    public function cash_in($id)
    {
        $user = User::findOrFail($id);
        $payments = PaymentMethod::where('status', 0)->get();
        return view('super_admin.cash_in.cash_in_create', compact('user', 'payments'));
    }

    public function cash_in_create($id, CashInRequest $request)
    {
        if ($request->payment_id == 7 || $request->payment_id == 5) {
            if ($request->transaction_id == null) {
                return back()->with('error_message', 'wave payment is required transaction Id');
            }
            $date =  date('m');
            $already_existed = CashIn::whereMonth('date', $date)
                ->where('transaction_id', $request->transaction_id)
                ->where('status', 'Approve')
                ->get();
            if (count($already_existed) > 0) {
                return back()->with('error_message', 'The transaction ID has already been taken.');
            }
        }


        $user = User::findOrFail($id);
        $balance = $user->balance;
        $user->balance = $balance + $request->amount;
        $user->update();

        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $datetime = helper::currentDateTime();
        $cash_in = new CashIn();
        $cash_in->user_id = $id;
        if ($request->payment_id == 7 || $request->payment_id == 5) {
            $cash_in->transaction_id = $request->transaction_id;
        }
        $cash_in->payment_id = $request->payment_id;
        $cash_in->amount = $request->amount;
        $cash_in->account_name = $user->name;
        $cash_in->user_phone = $user->phone;
        $cash_in->status = "Approve";
        $cash_in->old_amount = $balance;
        $cash_in->super_admin_id = $super_admin_id;
        $cash_in->date = $datetime['date'];
        $cash_in->time = $datetime['time'];
        $cash_in->save();

        //spin wheel count for user
        // invokeAll::spinWheelUserCount($user->id, $request->amount);

        return redirect('super_admin/user')->with('flash_message', 'Success Cash In');
    }

    public function cash_in_history(Request $request, $id)
    {
        if ($request->start_date) {
            $start = $request->start_date;
        } else {
            $start = Carbon::now()->subDays(1);
        }

        if ($request->end_date) {
            $end = $request->end_date;
        } else {
            $end = Carbon::now();
        }

        $cash_ins = CashIn::where('user_id', $id)->orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->get();
        if ($request->status) {
            $cash_ins = CashIn::where('user_id', $id)->orderBy('created_at', 'desc')->whereBetween('date', [$start, $end])->where('status', $request->status)->paginate(10);
        }

        // $cash_ins = CashIn::where('user_id',$id)->orderBy('created_at', 'desc')->get();
        return view('super_admin.cash_in.cash_in_history', compact('cash_ins'));
    }
}
