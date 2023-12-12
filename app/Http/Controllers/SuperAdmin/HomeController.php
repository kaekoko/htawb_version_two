<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\User;
use App\Models\Agent;
use App\Models\SuperAdmin;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\SuperAdminNoti;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class HomeController extends Controller
{
    public function create_account()
    {
        $cities = City::all();
        $payment_methods = PaymentMethod::where('status',0)->get();
        return view('super_admin.create_account',compact('cities','payment_methods'));
    }

    public function view_account()
    {
        $auth_id = Auth::guard('super_admin')->user()->id;
        $super_admins = SuperAdmin::whereNotNull('super_admin_id')->get();
        $senior_agents = SeniorAgent::whereNotNull('super_admin_id')->get();
        $master_agents = MasterAgent::whereNotNull('super_admin_id')->get();
        $agents = Agent::whereNotNull('super_admin_id')->get();
        $users = User::whereNotNull('super_admin_id')->get();
        return view('super_admin.view_account',compact('super_admins','senior_agents','master_agents','agents','users'));
    }

}
