<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Agent;
use App\Helper\helper;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use Illuminate\Http\Request;
use App\Models\CreditHistory;
use App\Models\PaymentMethod;
use App\Models\CreditOutHistory;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditRequest;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    public function senior_agent_add_credit($id)
    {
        $agent = SeniorAgent::findOrFail($id);
        $payment_methods = PaymentMethod::where('status',0)->get();
        return view('super_admin.credit.senior_agent_add_credit',compact('agent','payment_methods'));
    }

    public function senior_agent_update_credit(CreditRequest $request,$id)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $agent = SeniorAgent::findOrFail($id);
        $agent->credit = $agent->credit + $request->credit;
        $agent->update();

        $datetime = helper::currentDateTime();
        $credit_history = new CreditHistory();
        $credit_history->super_admin_id = $super_admin_id;
        $credit_history->senior_agent_id = $agent->id;
        $credit_history->credit = $request->credit;
        $credit_history->date = $datetime['date'];
        $credit_history->time = $datetime['time'];
        $credit_history->payment_method_id = $request->payment_method_id;
        $credit_history->save();

        return redirect('super_admin/credit_history')->with('flash_message','Add Credit');
    }

    public function master_agent_add_credit($id)
    {
        $agent = MasterAgent::findOrFail($id);
        $payment_methods = PaymentMethod::where('status',0)->get();
        return view('super_admin.credit.master_agent_add_credit',compact('agent','payment_methods'));
    }

    public function master_agent_update_credit(CreditRequest $request,$id)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $agent = MasterAgent::findOrFail($id);
        $agent->credit = $agent->credit + $request->credit;
        $agent->update();

        $datetime = helper::currentDateTime();
        $credit_history = new CreditHistory();
        $credit_history->super_admin_id = $super_admin_id;
        $credit_history->master_agent_id = $agent->id;
        $credit_history->credit = $request->credit;
        $credit_history->date = $datetime['date'];
        $credit_history->time = $datetime['time'];
        $credit_history->payment_method_id = $request->payment_method_id;
        $credit_history->save();

        return redirect('super_admin/credit_history')->with('flash_message','Add Credit');
    }

    public function agent_add_credit($id)
    {
        $agent = Agent::findOrFail($id);
        $payment_methods = PaymentMethod::where('status',0)->get();
        return view('super_admin.credit.agent_add_credit',compact('agent','payment_methods'));
    }

    public function agent_update_credit(CreditRequest $request,$id)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $agent = Agent::findOrFail($id);
        $agent->credit = $agent->credit + $request->credit;
        $agent->update();

        $datetime = helper::currentDateTime();
        $credit_history = new CreditHistory();
        $credit_history->super_admin_id = $super_admin_id;
        $credit_history->agent_id = $agent->id;
        $credit_history->credit = $request->credit;
        $credit_history->date = $datetime['date'];
        $credit_history->time = $datetime['time'];
        $credit_history->payment_method_id = $request->payment_method_id;
        $credit_history->save();

        return redirect('super_admin/credit_history')->with('flash_message','Add Credit');
    }

    public function credit_history()
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $credit_histories = CreditHistory::whereNotNull('super_admin_id')->orderBy('id', 'DESC')->get();
        $credit_out_histories = CreditOutHistory::whereNotNull('super_admin_id')->orderBy('id', 'DESC')->get();
        return view('super_admin.credit.credit_history',compact('credit_histories','credit_out_histories'));
    }

    public function credit_all_agent()
    {
        $payment_methods = PaymentMethod::where('status',0)->get();
        return view('super_admin.credit.credit_all_agent',compact('payment_methods'));
    }

    public function senior_agent_credit(CreditRequest $request)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $senior_agents = SeniorAgent::where('super_admin_id',$super_admin_id)->get();
        foreach($senior_agents as $senior_agent){
            $senior_agent->credit = $senior_agent->credit + $request->credit;
            $senior_agent->update();

            $datetime = helper::currentDateTime();

            $credit_history = new CreditHistory();
            $credit_history->super_admin_id = $super_admin_id;
            $credit_history->senior_agent_id = $senior_agent->id;
            $credit_history->credit = $request->credit;
            $credit_history->date = $datetime['date'];
            $credit_history->time = $datetime['time'];
            $credit_history->payment_method_id = $request->payment_method_id;
            $credit_history->save();
        }
        return redirect('super_admin/credit_history')->with('flash_message','Add All Senior Agent Credit');
    }

    public function master_agent_credit(CreditRequest $request)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $master_agents = MasterAgent::where('super_admin_id',$super_admin_id)->get();
        foreach($master_agents as $master_agent){
            $master_agent->credit = $master_agent->credit + $request->credit;
            $master_agent->update();

            $datetime = helper::currentDateTime();

            $credit_history = new CreditHistory();
            $credit_history->super_admin_id = $super_admin_id;
            $credit_history->master_agent_id = $master_agent->id;
            $credit_history->credit = $request->credit;
            $credit_history->date = $datetime['date'];
            $credit_history->time = $datetime['time'];
            $credit_history->payment_method_id = $request->payment_method_id;
            $credit_history->save();
        }
        return redirect('super_admin/credit_history')->with('flash_message','Add All Master Agent Credit');
    }

    public function agent_credit(CreditRequest $request)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $agents = Agent::where('super_admin_id',$super_admin_id)->get();
        foreach($agents as $agent){
            $agent->credit = $agent->credit + $request->credit;
            $agent->update();

            $datetime = helper::currentDateTime();

            $credit_history = new CreditHistory();
            $credit_history->super_admin_id = $super_admin_id;
            $credit_history->agent_id = $agent->id;
            $credit_history->credit = $request->credit;
            $credit_history->date = $datetime['date'];
            $credit_history->time = $datetime['time'];
            $credit_history->payment_method_id = $request->payment_method_id;
            $credit_history->save();
        }
        return redirect('super_admin/credit_history')->with('flash_message','Add All Agent Credit');
    }

    public function senior_agent_add_credit_out($id)
    {
        $agent = SeniorAgent::findOrFail($id);
        $payment_methods = PaymentMethod::where('status',0)->get();
        return view('super_admin.credit_out.senior_agent_add_credit_out',compact('agent','payment_methods'));
    }

    public function senior_agent_update_credit_out(CreditRequest $request,$id)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $agent = SeniorAgent::findOrFail($id);

        if($agent->credit >= $request->credit){
            $agent->credit = $agent->credit - $request->credit;
            $agent->update();

            $credit_history = new CreditOutHistory();
            $credit_history->super_admin_id = $super_admin_id;
            $credit_history->senior_agent_id = $agent->id;
            $credit_history->credit = $request->credit;
            $credit_history->payment_method_id = $request->payment_method_id;
            $credit_history->save();

            return redirect('super_admin/credit_history')->with('flash_message','Add Credit Out');
        }else{
            return redirect("super_admin/senior_agent_add_credit_out/$id")->with('error_message','Over Cash Out Limit!');
        }
    }

    public function master_agent_add_credit_out($id)
    {
        $agent = MasterAgent::findOrFail($id);
        $payment_methods = PaymentMethod::where('status',0)->get();
        return view('super_admin.credit_out.master_agent_add_credit_out',compact('agent','payment_methods'));
    }

    public function master_agent_update_credit_out(CreditRequest $request,$id)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $agent = MasterAgent::findOrFail($id);

        if($agent->credit >= $request->credit){
            $agent->credit = $agent->credit - $request->credit;
            $agent->update();

            $credit_history = new CreditOutHistory();
            $credit_history->super_admin_id = $super_admin_id;
            $credit_history->master_agent_id = $agent->id;
            $credit_history->credit = $request->credit;
            $credit_history->payment_method_id = $request->payment_method_id;
            $credit_history->save();

            return redirect('super_admin/credit_history')->with('flash_message','Add Credit Out');
        }else{
            return redirect("super_admin/master_agent_add_credit_out/$id")->with('error_message','Over Cash Out Limit!');
        }
    }

    public function agent_add_credit_out($id)
    {
        $agent = Agent::findOrFail($id);
        $payment_methods = PaymentMethod::where('status',0)->get();
        return view('super_admin.credit_out.agent_add_credit_out',compact('agent','payment_methods'));
    }

    public function agent_update_credit_out(CreditRequest $request,$id)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        $agent = Agent::findOrFail($id);

        if($agent->credit >= $request->credit){
            $agent->credit = $agent->credit - $request->credit;
            $agent->update();

            $credit_history = new CreditOutHistory();
            $credit_history->super_admin_id = $super_admin_id;
            $credit_history->agent_id = $agent->id;
            $credit_history->credit = $request->credit;
            $credit_history->payment_method_id = $request->payment_method_id;
            $credit_history->save();

            return redirect('super_admin/credit_history')->with('flash_message','Add Credit Out');
        }else{
            return redirect("super_admin/agent_add_credit_out/$id")->with('error_message','Over Cash Out Limit!');
        }
    }

}
