<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Agent;
use App\Helper\checkPhone;
use App\Models\SuperAdmin;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AccountUpdateRequest;
use App\Http\Requests\SuperAdminAccountUpdateRequest;
use App\Models\City;

class AgentUserDetailEditController extends Controller
{
    public function super_admin_detail_account($id){
        $detail_account = SuperAdmin::findOrFail($id);
        return view('super_admin.agent_user_detail_edit.super_admin_detail_account',compact('detail_account'));
    }

    public function super_admin_edit_account($id){
        $edit_account = SuperAdmin::findOrFail($id);
        return view('super_admin.agent_user_detail_edit.super_admin_edit_account',compact('edit_account'));
    }

    public function super_admin_update_account(SuperAdminAccountUpdateRequest $request, $id){
        if($request->phone){
            if($request->phone){
                $request->validate([
                    'phone' => 'numeric',
                ]);
            }
            if(checkPhone::number() == 0){
                $old_password = SuperAdmin::findOrFail($id)->password;
                $request->password = $request->password ? Hash::make($request->password) : $old_password;

                $super_admin = SuperAdmin::findOrFail($id);
                $super_admin->name = $request->name;
                $super_admin->phone = $request->phone;
                $super_admin->password = $request->password;

                if($request->hasFile('image')) {
                    Storage::delete($super_admin->image);
                    $super_admin->image =  $request->file('image') ? $request->file('image')->store('super_admin') : $super_admin->image;
                }
                $super_admin->update();
                return redirect('super_admin/view_account')->with('flash_message', 'Super Admin updated!');
            }else{
                return redirect("super_admin/super_admin_edit_account/$id")->with('error_message','Phone Number Exit!');
            }
        }else{
            $old_password = SuperAdmin::findOrFail($id)->password;
            $request->password = $request->password ? Hash::make($request->password) : $old_password;

            $super_admin = SuperAdmin::findOrFail($id);
            $super_admin->name = $request->name;
            $super_admin->password = $request->password;

            if($request->hasFile('image')) {
                Storage::delete($super_admin->image);
                $super_admin->image =  $request->file('image') ? $request->file('image')->store('super_admin') : $super_admin->image;
            }
            $super_admin->update();
            return redirect('super_admin/view_account')->with('flash_message', 'Super Admin updated!');
        }
    }

    public function senior_agent_detail_account($id){
        $detail_account = SeniorAgent::findOrFail($id);
        return view('super_admin.agent_user_detail_edit.senior_agent_detail_account',compact('detail_account'));
    }

    public function senior_agent_edit_account($id){
        $edit_account = SeniorAgent::findOrFail($id);
        return view('super_admin.agent_user_detail_edit.senior_agent_edit_account',compact('edit_account'));
    }

    public function senior_agent_update_account(AccountUpdateRequest $request, $id){
        if($request->phone){
            if($request->phone){
                $request->validate([
                    'phone' => 'numeric',
                ]);
            }
            if(checkPhone::number() == 0){
                $old_password = SeniorAgent::findOrFail($id)->password;
                $request->password = $request->password ? Hash::make($request->password) : $old_password;

                $senior_agent = SeniorAgent::findOrFail($id);
                $senior_agent->name = $request->name;
                $senior_agent->phone = $request->phone;
                $senior_agent->password = $request->password;

                if($request->hasFile('image')) {
                    Storage::delete($senior_agent->image);
                    $senior_agent->image =  $request->file('image') ? $request->file('image')->store('senior_agent') : $senior_agent->image;
                }
                $senior_agent->percent = $request->percent;
                $senior_agent->update();

                return redirect('super_admin/view_account')->with('flash_message', 'Senior Agent updated!');
            }else{
                return redirect("super_admin/senior_agent_edit_account/$id")->with('error_message','Phone Number Exit!');
            }
        }else{
            $old_password = SeniorAgent::findOrFail($id)->password;
            $request->password = $request->password ? Hash::make($request->password) : $old_password;

            $senior_agent = SeniorAgent::findOrFail($id);
            $senior_agent->name = $request->name;
            $senior_agent->password = $request->password;

            if($request->hasFile('image')) {
                Storage::delete($senior_agent->image);
                $senior_agent->image =  $request->file('image') ? $request->file('image')->store('senior_agent') : $senior_agent->image;
            }
            $senior_agent->percent = $request->percent;
            $senior_agent->update();

            return redirect('super_admin/view_account')->with('flash_message', 'Senior Agent updated!');
        }
    }

    public function master_agent_detail_account($id){
        $detail_account = MasterAgent::findOrFail($id);
        return view('super_admin.agent_user_detail_edit.master_agent_detail_account',compact('detail_account'));
    }

    public function master_agent_edit_account($id){
        $edit_account = MasterAgent::findOrFail($id);
        return view('super_admin.agent_user_detail_edit.master_agent_edit_account',compact('edit_account'));
    }

    public function master_agent_update_account(AccountUpdateRequest $request, $id){
        if($request->phone){
            if($request->phone){
                $request->validate([
                    'phone' => 'numeric',
                ]);
            }
            if(checkPhone::number() == 0){
                $old_password = MasterAgent::findOrFail($id)->password;
                $request->password = $request->password ? Hash::make($request->password) : $old_password;

                $master_agent = MasterAgent::findOrFail($id);
                $master_agent->name = $request->name;
                $master_agent->phone = $request->phone;
                $master_agent->password = $request->password;

                if($request->hasFile('image')) {
                    Storage::delete($master_agent->image);
                    $master_agent->image =  $request->file('image') ? $request->file('image')->store('master_agent') : $master_agent->image;
                }
                $master_agent->percent = $request->percent;
                $master_agent->update();

                return redirect('super_admin/view_account')->with('flash_message', 'Master Agent updated!');
            }else{
                return redirect("super_admin/master_agent_edit_account/$id")->with('error_message','Phone Number Exit!');
            }
        }else{
            $old_password = MasterAgent::findOrFail($id)->password;
            $request->password = $request->password ? Hash::make($request->password) : $old_password;

            $master_agent = MasterAgent::findOrFail($id);
            $master_agent->name = $request->name;
            $master_agent->password = $request->password;

            if($request->hasFile('image')) {
                Storage::delete($master_agent->image);
                $master_agent->image =  $request->file('image') ? $request->file('image')->store('master_agent') : $master_agent->image;
            }
            $master_agent->percent = $request->percent;
            $master_agent->update();

            return redirect('super_admin/view_account')->with('flash_message', 'Master Agent updated!');
        }
    }

    public function agent_detail_account($id){
        $detail_account = Agent::findOrFail($id);
        return view('super_admin.agent_user_detail_edit.agent_detail_account',compact('detail_account'));
    }

    public function agent_edit_account($id){
        $edit_account = Agent::findOrFail($id);
        $cities = City::all();
        return view('super_admin.agent_user_detail_edit.agent_edit_account',compact('edit_account','cities'));
    }

    public function agent_update_account(AccountUpdateRequest $request, $id){
        if($request->phone){
            if($request->phone){
                $request->validate([
                    'phone' => 'numeric',
                ]);
            }
            if(checkPhone::number() == 0){
                $old_password = Agent::findOrFail($id)->password;
                $request->password = $request->password ? Hash::make($request->password) : $old_password;

                $agent = Agent::findOrFail($id);
                $agent->name = $request->name;
                $agent->phone = $request->phone;
                $agent->password = $request->password;

                if($request->hasFile('image')) {
                    Storage::delete($agent->image);
                    $agent->image =  $request->file('image') ? $request->file('image')->store('agent') : $agent->image;
                }
                $agent->percent = $request->percent;
                $agent->city_id = $request->city_id;
                $agent->update();

                return redirect('super_admin/view_account')->with('flash_message', 'Agent updated!');
            }else{
                return redirect("super_admin/agent_edit_account/$id")->with('error_message','Phone Number Exit!');
            }
        }else{
            $old_password = Agent::findOrFail($id)->password;
            $request->password = $request->password ? Hash::make($request->password) : $old_password;

            $agent = Agent::findOrFail($id);
            $agent->name = $request->name;
            $agent->password = $request->password;

            if($request->hasFile('image')) {
                Storage::delete($agent->image);
                $agent->image =  $request->file('image') ? $request->file('image')->store('agent') : $agent->image;
            }
            $agent->percent = $request->percent;
            $agent->city_id = $request->city_id;
            $agent->update();

            return redirect('super_admin/view_account')->with('flash_message', 'Agent updated!');
        }
    }

}
