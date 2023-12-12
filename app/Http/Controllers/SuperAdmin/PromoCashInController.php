<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Invoker\invokeAll;
use App\Models\PromoCashIn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PromoCashInRequest;

class PromoCashInController extends Controller
{
    public function index()
    {
        $promo_cash_ins = PromoCashIn::all();
        return view('super_admin.prmo_cash_in.index', compact('promo_cash_ins'));
    }

    public function create(PromoCashInRequest $request)
    {
        $cash_in_promo = new PromoCashIn();
        $cash_in_promo->title = $request->title;
        $cash_in_promo->percent = $request->percent;

        $promo_code = invokeAll::generateReferralCode();
        $cash_in_promo->promo_code = $promo_code;

        $cash_in_promo->turnover = $request->turnover;
        $cash_in_promo->lvl = $request->level;

        $image = $request->file('image');
        if($request->hasFile('image')) {
            $cash_in_promo->image = $image->store('super_admin');
        }

        if($request->game_text){
            $cash_in_promo->game_text = $request->game_text;
        }
        if($request->rule){
            $cash_in_promo->rule = $request->rule;
        }

        $cash_in_promo->save();
        return redirect('super_admin/promo_cash_in')->with('flash_message','Cash In Promotion Created');
    }

    public function update(Request $request, $id)
    {
        $cash_in_promo = PromoCashIn::findOrFail($id);
        $cash_in_promo->title = $request->title;
        $cash_in_promo->percent = $request->percent;

        $cash_in_promo->turnover = $request->turnover;
        $cash_in_promo->lvl = $request->level;

        $image = $request->file('image');
        if($request->hasFile('image')) {
            Storage::delete($cash_in_promo->image);
            $cash_in_promo->image = $image->store('super_admin');
        }

        if($request->game_text){
            $cash_in_promo->game_text = $request->game_text;
        }
        if($request->rule){
            $cash_in_promo->rule = $request->rule;
        }

        $cash_in_promo->update();
        return redirect('super_admin/promo_cash_in')->with('flash_message','Cash In Promotion Updated');
    }

    public function delete($id)
    {
        $prmo_cash_in = PromoCashIn::findOrfail($id);
        Storage::delete($prmo_cash_in->image);
        $prmo_cash_in->delete();
        return redirect('super_admin/promo_cash_in')->with('flash_message','Cash In Promotion Deleted');
    }

    public function status(Request $request)
    {
        $prmo_cash_in = PromoCashIn::findOrfail($request->id);
        $prmo_cash_in->status = $request->status;
        $prmo_cash_in->update();
        return response()->json([
            'status' => $request->status,
        ]);
    }

}
