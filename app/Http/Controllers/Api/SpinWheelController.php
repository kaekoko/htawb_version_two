<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SpinWheel;
use App\Invoker\invokeAll;
use Illuminate\Http\Request;
use App\Models\SpinWheelUser;
use App\Http\Controllers\Controller;
use App\Models\SpinWheelLuckyHistory;
use Illuminate\Support\Facades\Auth;

class SpinWheelController extends Controller
{
    public function index()
    {

        $check = SpinWheel::where('type', 'wheel_off')->first();
        if($check){
            return response()->json([
                'message' => 'spin wheel close'
            ],400);
        }

        $wheels = SpinWheel::where('type', 'wheel')->get();

        $id = Auth::user()->id;
        $s_w_user = SpinWheelUser::where('user_id', $id)->first();
        if($s_w_user){
            $count = $s_w_user->count;
        }else{
            $count = 0;
        }

        $sub_date = Carbon::now()->subDays(1);
        $check = User::where('id', $id)->whereDate('updated_at', '>', $sub_date)->first();
        if($check){
            $free = 0;
        }else{
            $free = 1;
        }

        $sp_mesg = SpinWheel::where('type', 'mesg')->first();
        $mesg = $sp_mesg->message;

        return response()->json([
            'free count' => $free,
            'count' => $count,
            'message' => $mesg,
            'data' => $wheels,

        ]);
    }

    public function spin_wheel_luck()
    {
        $id = Auth::user()->id;
        $s_w_user = SpinWheelUser::where('user_id', $id)->first();

        if(!$s_w_user)
        {
            return response()->json([
                'message' => 'no spin wheel count'
            ],400);
        }
        elseif($s_w_user->count > 0)
        {
            $sub_date = Carbon::now()->subDays(1);
            $check = SpinWheelUser::where('user_id', $id)->where('update_time', '<', $sub_date)->first();
            if($check){
                $s_w_user->count = 0;
                $s_w_user->update();

                return response()->json([
                    'message' => 'expire spin wheel',
                ]);
            }else{
                $s_w_user->count = $s_w_user->count - 1;
                $s_w_user->update();

                $res = invokeAll::spinWheelPercentRate();
                $luck_data = SpinWheel::find($res['id']);
                return response()->json([
                    'count' => $s_w_user->count,
                    'lucky data' => $luck_data
                ]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'no spin wheel count'
            ],400);
        }
    }

    public function free_spin_wheel()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $sub_date = Carbon::now()->subDays(1);

        $spin_free_on_off = SpinWheel::where('id', 12)->first();
        if($spin_free_on_off->type == 'free_off'){
            return response()->json([
                'message' => 'free spin close',
            ]);
        }

        $spin_type = SpinWheel::where('id', 11)->first();
        if($spin_type->type == 'lvl_2_on'){
            $lvl_2_check = User::where('id', $id)->where('lvl_2', 0)->first();
            if($lvl_2_check){
                return response()->json([
                    'message' => 'update user lvl 2',
                ]);
            }
        }

        $check = User::where('id', $id)->whereDate('sp_free_time', '>', $sub_date)->first();

        if($check){
            return response()->json([
                'message' => 'expire spin wheel',
            ]);
        }else{
            $user->sp_free_time = date('Y-m-d H:i:s');
            $user->update();

            $res = invokeAll::spinWheelPercentRate();
            $luck_data = SpinWheel::find($res['id']);
            return response()->json([
                'lucky data' => $luck_data
            ]);
        }

    }

    public function spin_wheel_luck_transfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $id = Auth::user()->id;
        $user = User::find($id);
        $user->balance = $user->balance + $request->amount;
        $user->update();

        $luck_history = new SpinWheelLuckyHistory();
        $luck_history->user_id = $id;
        $luck_history->amount = $request->amount;
        $luck_history->save();

        return response()->json([
            'data' => 'success',
        ],200);
    }


}
