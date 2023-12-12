<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\LotteryOffDay;
use App\Http\Controllers\Controller;
use App\Http\Requests\LotteryOffDayRequest;

class LotteryOffDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lottery_off_days = LotteryOffDay::all();
        return view('super_admin.lottery_off_day.index',compact('lottery_off_days'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('super_admin.lottery_off_day.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LotteryOffDayRequest $request)
    {
        $date = new LotteryOffDay();
        $date->category_id = $request->category_id;
        $date->off_day = Carbon::parse($request->off_day);
        $date->save();
        return redirect('super_admin/lottery_off_day')->with('flash_message','Lottery Off Day Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $lottery_off_day = LotteryOffDay::findOrFail($id);
        return view('super_admin.lottery_off_day.edit',compact('categories','lottery_off_day'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LotteryOffDayRequest $request, $id)
    {
        $date = LotteryOffDay::findOrFail($id);
        $date->category_id = $request->category_id;
        $date->off_day = Carbon::parse($request->off_day);
        $date->update();
        return redirect('super_admin/lottery_off_day')->with('flash_message','Lottery Off Day Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LotteryOffDay::find($id)->delete();
        return redirect('super_admin/lottery_off_day')->with('flash_message','Lottery Off Day Deleted');
    }
}
