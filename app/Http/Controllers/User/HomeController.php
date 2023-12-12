<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\SeniorAgentNoti;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function noti($id)
    {
        // Close Noti
        // $auth_id = Auth::guard('senior_agent')->user()->id;
        // $close_noti = SeniorAgentNoti::where('noti_id',$id)->where('senior_agent_id',$auth_id)->select('id')->get();
        // $close_noti = SeniorAgentNoti::findOrFail($close_noti[0]->id);
        // $close_noti->close_noti = '1';
        // $close_noti->update();

        // $noti = Notification::findOrFail($id);
        // return view('senior_agent.noti',compact('noti'));
    }

    public function dashboard(){
        $auth_id = Auth::guard('user')->user()->id;
        $user = User::find($auth_id);
        $token = $user->device_token;
        return view('user.dashboard.index',compact('token'));
    }

    public function create_account()
    {
        return view('user.create_account');
    }

    public function view_account()
    {
        $auth_id = Auth::guard('user')->user()->id;
        $users = User::where('user_id',$auth_id)->get();
        return view('user.view_account',compact('users'));
    }

    public function save_token(Request $request)
    {
        $auth_id = Auth::guard('user')->user()->id;
        $token = User::find($auth_id);
        $token->device_token = $request->token;
        $token->update();

        return[
            'message'=>'success',
        ];
    }
}
