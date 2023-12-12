<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\User;
use App\Models\Agent;
use App\Helper\helper;
use App\Models\CashIn;
use App\Helper\checkPhone;
use App\Models\SuperAdmin;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FirebaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Invoker\invokeAll;
use App\Models\UserLevel2;
use CasinoGames\Facade\CasinoGames;
use phpseclib3\Crypt\RC2;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserTwoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('side',1)->latest()->paginate(10);
        if (request('search')) {
            $users = User::orWhere('name', 'like', '%' . request('search') . '%')
                ->orWhere('phone', 'like', "%" . request('search') . "%")
                ->orWhere('user_code', 'like', "%" . request('search') . "%")
                ->where('side',1)
                ->paginate(10);
        }
        return view('super_admin.casino.index', compact('users'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_methods = PaymentMethod::where('status', 0)->get();
        return view('super_admin.casino.create', compact('payment_methods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        if ($request->balance) {
            $request->validate([
                'balance' => 'numeric',
            ]);
        }
        $user_code = substr(md5($request->phone), 0, 10);
        $referral = invokeAll::generateReferralCode();
        if (checkPhone::number() == 0) {
            $super_admin_id = Auth::guard('super_admin')->user()->id;

            $image = $request->file('image');
            $user = new User();
            $user->super_admin_id = $super_admin_id;
            $user->name = $request->name;
            $user->side = 1;
            $user->user_code = $user_code;
            $user->referral = $referral;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->balance = 0;
            if ($request->hasFile('image')) {
                $user->image = $image->store('user');
            }
            create_table($user->user_code);
            $user->save();

            // if ($request->balance) {
            //     $datetime = helper::currentDateTime();
            //     $cash_in = new CashIn();
            //     $cash_in->user_id = $user->id;
            //     $cash_in->payment_id = $request->payment_id;
            //     $cash_in->amount = $request->balance;
            //     $cash_in->super_admin_id = $super_admin_id;
            //     $cash_in->status = 'Approve';
            //     $cash_in->date = $datetime['date'];
            //     $cash_in->time = $datetime['time'];
            //     $cash_in->save();

            //     //spin wheel count for user
            //     invokeAll::spinWheelUserCount($user->id, $request->balance);
            // }

            return redirect('game/user')->with('flash_message', 'User Created');
        } else {
            return redirect('game/user/create')->with('error_message', 'Phone Number Exit!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $get_balance = new FirebaseController();
        $user_balance = $get_balance->getValue($user->id);
        $provider = $user_balance['last_provider'];
        // $get_balance = getbalance($provider, $user->user_code, 'mmkslot');
        return view('super_admin.casino.show', compact('user', 'get_balance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('super_admin.casino.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        if ($request->balance) {
            $request->validate([
                'balance' => 'numeric',
            ]);
        }
        if ($request->phone) {
            if (checkPhone::number() == 0) {
                $old_password = User::findOrFail($id)->password;
                $request->password = $request->password ? Hash::make($request->password) : $old_password;

                $user = User::findOrFail($id);
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->password = $request->password;

                if ($request->hasFile('image')) {
                    Storage::delete($user->image);
                    $user->image =  $request->file('image') ? $request->file('image')->store('user') : $user->image;
                }
                $user->update();

                return redirect('game/user')->with('flash_message', 'User updated!');
            } else {
                return redirect("game/user/$id/edit")->with('error_message', 'Phone Number Exit!');
            }
        } else {
            $old_password = User::findOrFail($id)->password;
            $request->password = $request->password ? Hash::make($request->password) : $old_password;

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->password = $request->password;

            if ($request->hasFile('image')) {
                Storage::delete($user->image);
                $user->image =  $request->file('image') ? $request->file('image')->store('user') : $user->image;
            }
            $user->update();

            return redirect('game/user')->with('flash_message', 'User updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}
