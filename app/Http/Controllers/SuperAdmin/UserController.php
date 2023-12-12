<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Agent;
use App\Helper\helper;
use App\Models\CashIn;
use phpseclib3\Crypt\RC2;
use App\Helper\checkPhone;
use App\Invoker\invokeAll;
use App\Models\UserLevel2;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\FirebaseController;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $usersQuery = User::with(['cash_ins'])->select('id','name','side','phone','user_code','block','balance','created_at')->where('side', null);


        if (request('search')) {
            $searchTerm = '%' . request('search') . '%';
            $usersQuery->where(function ($query) use ($searchTerm) {
                $query->orWhere('name', 'like', $searchTerm)
                      ->orWhere('phone', 'like', $searchTerm)
                      ->orWhere('user_code', 'like', $searchTerm);
            });
        }

        $users = $usersQuery->latest()->paginate(10);
        return view('super_admin.user.index', ['users' => $users ]);
    }


    public function userOnlineStatus()
    {

        $oneMinuteAgo = Carbon::now()->subMinute();

         $users = User::where('last_seen', '>=', $oneMinuteAgo)->paginate(10);

          return view('super_admin.user.online', compact('users'));
    }

    public function userlvltwo()
    {

        $lvl_2 = UserLevel2::orderBy('id', 'DESC')->get();
        return view('super_admin.user.lvltwo', compact('lvl_2'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_methods = PaymentMethod::where('status', 0)->get();
        return view('super_admin.user.create', compact('payment_methods'));
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
            $user->user_code = $user_code;
            $user->referral = $referral;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->balance = 0;
            if ($request->hasFile('image')) {
                $user->image = $image->store('user');
            }

            // create_table($user->user_code);
            $user->save();

            if ($request->balance) {
                $datetime = helper::currentDateTime();
                $cash_in = new CashIn();
                $cash_in->user_id = $user->id;
                $cash_in->payment_id = $request->payment_id;
                $cash_in->amount = $request->balance;
                $cash_in->super_admin_id = $super_admin_id;
                $cash_in->status = 'Approve';
                $cash_in->date = $datetime['date'];
                $cash_in->time = $datetime['time'];
                $cash_in->save();

                //spin wheel count for user
                invokeAll::spinWheelUserCount($user->id, $request->balance);
            }

            return redirect('super_admin/user')->with('flash_message', 'User Created');
        } else {
            return redirect('super_admin/user/create')->with('error_message', 'Phone Number Exit!');
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
        return view('super_admin.user.show', compact('user', 'get_balance'));
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
        return view('super_admin.user.edit', compact('user'));
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

                return redirect('super_admin/user')->with('flash_message', 'User updated!');
            } else {
                return redirect("super_admin/user/$id/edit")->with('error_message', 'Phone Number Exit!');
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

            return redirect('super_admin/user')->with('flash_message', 'User updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete = 1;
        $user->phone = $user->phone . '-delete' . $id;
        $user->update();
        return redirect('super_admin/user')->with('flash_message', 'User deleted!');
    }

    public function user_lvl_2_approve($id)
    {
        $approve = UserLevel2::findOrfail($id);
        $approve->status = 'approve';
        $approve->update();

        $user = User::find($approve->user_id);
        $user->lvl_2 = 1;
        $user->update();

        return redirect('super_admin/user')->with('flash_message', 'User Level 2 Approved');
    }

    public function user_lvl_2_reject($id)
    {
        $reject = UserLevel2::findOrfail($id);
        $reject->status = 'reject';
        $reject->update();
        return redirect('super_admin/user')->with('flash_message', 'User Level 2 Reject');
    }

    public function notionly(Request $request){
        invokeAll::userAlertNoti($request->title,$request->body, $request->user_id);

        return back()->with('flash_message', 'Successfull Noti Send');
    }
}
