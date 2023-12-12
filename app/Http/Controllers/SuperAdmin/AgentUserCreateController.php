<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Agent;
use App\Helper\helper;
use App\Helper\checkPhone;
use App\Models\SuperAdmin;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use Illuminate\Http\Request;
use App\Models\CreditHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AccountCreateRequest;
use App\Http\Requests\SuperAdminAccountCreateRequest;

class AgentUserCreateController extends Controller
{
    public function super_admin_create(SuperAdminAccountCreateRequest $request)
    {
        if(checkPhone::number() == 0){
            $super_admin_id = Auth::guard('super_admin')->user()->id;
            if($request->hasFile('image')) {
                $image = $request->file('image');
                $super_admin = new SuperAdmin();
                $super_admin->super_admin_id = $super_admin_id;
                $super_admin->name = $request->name;
                $super_admin->phone = $request->phone;
                $super_admin->password = Hash::make($request->password);
                $super_admin->image = $image->store('super_admin');
                $super_admin->save();
            }else{
                $super_admin = new SuperAdmin();
                $super_admin->super_admin_id = $super_admin_id;
                $super_admin->name = $request->name;
                $super_admin->phone = $request->phone;
                $super_admin->password = Hash::make($request->password);
                $super_admin->save();
            }
            return redirect('super_admin/view_account')->with('flash_message','Super Admin Created');
        }else{
            return redirect('super_admin/create_account')->with('error_message','Phone Number Exit!');
        }

    }

    public function senior_agent_create(AccountCreateRequest $request)
    {
        if($request->credit){
            $request->validate([
                'credit' => 'numeric',
            ]);
        }
        if(checkPhone::number() == 0){
            $super_admin_id = Auth::guard('super_admin')->user()->id;
            if($request->hasFile('image')) {
                $image = $request->file('image');
                $senior_agent = new SeniorAgent();
                $senior_agent->super_admin_id = $super_admin_id;
                $senior_agent->name = $request->name;
                $senior_agent->phone = $request->phone;
                $senior_agent->password = Hash::make($request->password);
                $senior_agent->image = $image->store('senior_agent');
                $senior_agent->percent = $request->percent;
                if($request->credit){
                    $senior_agent->credit = $request->credit;
                }
                $senior_agent->save();
            }else{
                $senior_agent = new SeniorAgent();
                $senior_agent->super_admin_id = $super_admin_id;
                $senior_agent->name = $request->name;
                $senior_agent->phone = $request->phone;
                $senior_agent->password = Hash::make($request->password);
                $senior_agent->percent = $request->percent;
                if($request->credit){
                    $senior_agent->credit = $request->credit;
                }
                $senior_agent->save();
            }
            if($request->credit){
                $datetime = helper::currentDateTime();
                $credit_history = new CreditHistory();
                $credit_history->super_admin_id = $super_admin_id;
                $credit_history->senior_agent_id = $senior_agent->id;
                $credit_history->credit = $request->credit;
                $credit_history->payment_method_id = $request->payment_method_id;
                $credit_history->date = $datetime['date'];
                $credit_history->time = $datetime['time'];
                $credit_history->save();
            }
            return redirect('super_admin/view_account')->with('flash_message','Senior Agent Created');
        }else{
            return redirect('super_admin/create_account')->with('error_message','Phone Number Exit!');
        }
    }

    public function master_agent_create(AccountCreateRequest $request)
    {
        if($request->credit){
            $request->validate([
                'credit' => 'numeric',
            ]);
        }
        if(checkPhone::number() == 0){
            $super_admin_id = Auth::guard('super_admin')->user()->id;
            if($request->hasFile('image')) {
                $image = $request->file('image');
                $master_agent = new MasterAgent();
                $master_agent->super_admin_id = $super_admin_id;
                $master_agent->name = $request->name;
                $master_agent->phone = $request->phone;
                $master_agent->password = Hash::make($request->password);
                $master_agent->image = $image->store('master_agent');
                $master_agent->percent = $request->percent;
                if($request->credit){
                    $master_agent->credit = $request->credit;
                }
                $master_agent->save();
            }else{
                $master_agent = new MasterAgent();
                $master_agent->super_admin_id = $super_admin_id;
                $master_agent->name = $request->name;
                $master_agent->phone = $request->phone;
                $master_agent->password = Hash::make($request->password);
                $master_agent->percent = $request->percent;
                if($request->credit){
                    $master_agent->credit = $request->credit;
                }
                $master_agent->save();
            }
            if($request->credit){
                $credit_history = new CreditHistory();
                $credit_history->super_admin_id = $super_admin_id;
                $credit_history->master_agent_id = $master_agent->id;
                $credit_history->credit = $request->credit;
                $credit_history->payment_method_id = $request->payment_method_id;
                $credit_history->save();
            }
            return redirect('super_admin/view_account')->with('flash_message','Master Agent Created');
        }else{
            return redirect('super_admin/create_account')->with('error_message','Phone Number Exit!');
        }
    }

    public function agent_create(AccountCreateRequest $request)
    {
        if($request->credit){
            $request->validate([
                'credit' => 'numeric',
            ]);
        }
        if(checkPhone::number() == 0){
            $super_admin_id = Auth::guard('super_admin')->user()->id;
            if($request->hasFile('image')) {
                $image = $request->file('image');
                $agent = new Agent();
                $agent->super_admin_id = $super_admin_id;
                $agent->name = $request->name;
                $agent->phone = $request->phone;
                $agent->password = Hash::make($request->password);
                $agent->image = $image->store('agent');
                $agent->percent = $request->percent;
                $agent->city_id = $request->city_id;
                if($request->credit){
                    $agent->credit = $request->credit;
                }
                $agent->save();
            }else{
                $agent = new Agent();
                $agent->super_admin_id = $super_admin_id;
                $agent->name = $request->name;
                $agent->phone = $request->phone;
                $agent->password = Hash::make($request->password);
                $agent->percent = $request->percent;
                $agent->city_id = $request->city_id;
                if($request->credit){
                    $agent->credit = $request->credit;
                }
                $agent->save();
            }
            if($request->credit){
                $credit_history = new CreditHistory();
                $credit_history->super_admin_id = $super_admin_id;
                $credit_history->agent_id = $agent->id;
                $credit_history->credit = $request->credit;
                $credit_history->payment_method_id = $request->payment_method_id;
                $credit_history->save();
            }
            return redirect('super_admin/view_account')->with('flash_message','Agent Created');
        }else{
            return redirect('super_admin/create_account')->with('error_message','Phone Number Exit!');
        }
    }

}
