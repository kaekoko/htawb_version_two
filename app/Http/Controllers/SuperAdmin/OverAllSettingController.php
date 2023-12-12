<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\OverAllSetting;
use Illuminate\Http\Request;

class OverAllSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $over_all_setting = OverAllSetting::first();
        return view('super_admin.over_all_setting.index',compact('over_all_setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.over_all_setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $over_all_setting = OverAllSetting::findOrFail($id);
        $over_all_setting->over_all_amount_crypto_2d = $request->over_all_amount_crypto_2d;
        $over_all_setting->over_all_amount_crypto_1d = $request->over_all_amount_crypto_1d;
        $over_all_setting->crypto_2d_odd = $request->crypto_2d_odd;
        $over_all_setting->crypto_1d_odd = $request->crypto_1d_odd;
        $over_all_setting->over_all_amount = $request->over_all_amount;
        $over_all_setting->over_all_odd = $request->over_all_odd;
        $over_all_setting->over_all_amount_3d = $request->over_all_amount_3d;
        $over_all_setting->odd_3d = $request->odd_3d;
        $over_all_setting->tot_3d = $request->tot_3d;
        $over_all_setting->over_all_default_amount = $request->over_all_default_amount;
        $over_all_setting->referral = $request->referral;
        $over_all_setting->referral_c2d = $request->referral_c2d;
        $over_all_setting->referral_3d = $request->referral_3d;
        $over_all_setting->game_refer = $request->game_refer;
        $over_all_setting->over_all_amount_1d = $request->over_all_amount_1d;
        $over_all_setting->over_all_odd_1d = $request->over_all_odd_1d;
        $over_all_setting->update();
        return redirect('super_admin/over_all_setting')->with('flash_message','Over All Setting Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
