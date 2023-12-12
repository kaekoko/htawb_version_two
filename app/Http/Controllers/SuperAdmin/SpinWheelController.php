<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\SpinWheel;
use App\Invoker\invokeAll;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use App\Models\SpinWheelUser;
use App\Models\SpinWheelRange;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SpinWheelLuckyHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SpinWheelController extends Controller
{
    public function index(Request $request)
    {
        $spin_wheels = SpinWheel::where('type', 'wheel')->get();
        $message = SpinWheel::where('id', 9)->first();
        $wheel_on_off = SpinWheel::where('id', 10)->first();
        $spin_wheel_users = SpinWheelUser::all();
        $ranges = SpinWheelRange::all();
        $lvl_2 = SpinWheel::where('id', 11)->first();
        $sp_free = SpinWheel::where('id', 12)->first();

        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now();
            $start = $start->format('Y-m-d');
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
            $end = $end->format('Y-m-d');
        }
        $lucky_history = SpinWheelLuckyHistory::orderBy('id', 'DESC')->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"])->get();

        return view('super_admin.spin_wheel.index', compact('spin_wheels', 'message', 'wheel_on_off','spin_wheel_users','ranges','lvl_2','sp_free','lucky_history'));
    }

    public function update($id, Request $request)
    {
        if($id == '9'){
            $spin = SpinWheel::find($id);
            $spin->message = $request->message;
            $spin->update();
            return redirect('super_admin/spin_wheel')->with('flash_message', 'Spin Wheel Message Updated');
        }

        if($id == '10'){

            $s_id = Auth::guard('super_admin')->user()->id;
            $super_admin = SuperAdmin::where('id', $s_id)->where('role_id', 1)->first();
            if(!$super_admin)
            {
                return abort(403);
            }
            $password = SuperAdmin::findOrFail($super_admin->id)->password;
            if(Hash::check($request->password , $password)){

                $spin = SpinWheel::find($id);
                $spin->type = $request->type;
                $spin->update();

                if($request->type == 'wheel_off'){
                    SpinWheelUser::truncate();
                }
                return redirect('super_admin/spin_wheel')->with('flash_message', 'Spin Wheel On/Off Updated');
            }else{
                return redirect('super_admin/spin_wheel')->with('error_message', 'Password Inncorrect');
            }

        }

        $validator = Validator::make($request->all(), [
            'percent' => 'numeric',
            'degree' => 'numeric',
            'amount' => 'numeric',
        ]);
        if ($validator->fails()) {
            return redirect('super_admin/spin_wheel')->with('error_message', 'Spin Wheel in some data must be number');
        }

        $spin = SpinWheel::findOrFail($id);
        $spin->name = $request->name;
        $spin->percent = $request->percent;
        if($request->hasFile('image')) {
            Storage::delete($spin->image);
            $spin->image = $request->file('image')->store('super_admin');
        }
        $spin->degree = $request->degree;
        $spin->amount = $request->amount;
        $spin->update();
        return redirect('super_admin/spin_wheel')->with('flash_message', 'Spin Wheel '.$id.' updated!');
    }

    public function refresh(Request $request)
    {
        $s_id = Auth::guard('super_admin')->user()->id;
        $super_admin = SuperAdmin::where('id', $s_id)->where('role_id', 1)->first();
        if(!$super_admin)
        {
            return abort(403);
        }
        $password = SuperAdmin::findOrFail($super_admin->id)->password;
        if(Hash::check($request->password , $password))
        {
            SpinWheelUser::truncate();
            return redirect('super_admin/spin_wheel')->with('flash_message', 'Spin Wheel Users Count Refreshed');
        }else{
            return redirect('super_admin/spin_wheel')->with('error_message', 'Password Inncorrect');
        }
    }

    public function range_amount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_amount' => 'numeric',
            'end_amount' => 'numeric',
            'count' => 'numeric',
        ]);
        if ($validator->fails()) {
            return redirect('super_admin/spin_wheel')->with('error_message', 'number required');
        }

        $range_amount = new SpinWheelRange;
        $range_amount->start_amount = $request->start_amount;
        $range_amount->end_amount = $request->end_amount;
        $range_amount->count = $request->count;
        $range_amount->save();
        return redirect('super_admin/spin_wheel')->with('flash_message', 'Create range amount setting');
    }

    public function range_amount_update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_amount' => 'numeric',
            'end_amount' => 'numeric',
            'count' => 'numeric',
        ]);
        if ($validator->fails()) {
            return redirect('super_admin/spin_wheel')->with('error_message', 'number required');
        }

        $range_amount = SpinWheelRange::findOrfail($id);
        $range_amount->start_amount = $request->start_amount;
        $range_amount->end_amount = $request->end_amount;
        $range_amount->count = $request->count;
        $range_amount->update();
        return redirect('super_admin/spin_wheel')->with('flash_message', 'Update range amount setting');
    }

    public function range_amount_delete($id)
    {
        SpinWheelRange::find($id)->delete();
        return redirect('super_admin/spin_wheel')->with('flash_message','Delete range amount setting');
    }

    public function lvl_2_on_off(Request $request)
    {
        $lvl_2_on_off = SpinWheel::findOrFail($request->id);
        $lvl_2_on_off->type = $request->status;
        $lvl_2_on_off->update();
        return response()->json([
            'status' => $request->status
        ]);
    }

    public function spin_free_on_off(Request $request)
    {
        $free_on_off = SpinWheel::findOrFail($request->id);
        $free_on_off->type = $request->status;
        $free_on_off->update();
        return response()->json([
            'status' => $request->status
        ]);
    }

}
