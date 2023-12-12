<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\User;
use App\Models\Banner;
use App\Models\BannerTwo;
use App\Models\TransferIn;
use App\Models\TransferOut;
use App\Models\GameCategory;
use App\Models\GameProvider;
use Illuminate\Http\Request;
use App\Models\BettingHistory;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use Illuminate\Support\Facades\Http;
use App\Models\ProviderMinimumAmount;
use App\Models\ClaimGamerReferHistory;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = BannerTwo::all();
        return view('super_admin.game.dashboard',compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.game.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        $banner = new BannerTwo();
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $banner->image = $image->store('banner');
        }
        if($request->hasFile('mb_image')) {
            $image = $request->file('mb_image');
            $banner->mb_image = $image->store('banner');
        }
        $banner->save();
        return redirect('game/bannertwo')->with('flash_message','Sider Created');
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
        $banner = BannerTwo::findOrFail($id);
        return view('super_admin.game.edit',compact('banner'));
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
        $banner = BannerTwo::findOrFail($id);
        if($request->hasFile('image')) {
            $image = $request->file('image');
            Storage::delete($banner->image);
            $banner->image = $image->store('banner');
        }
        if($request->hasFile('mb_image')) {
            $image = $request->file('mb_image');
            Storage::delete($banner->mb_image);
            $banner->mb_image = $image->store('banner');
        }
       
        $banner->save();
        return redirect('game/bannertwo')->with('flash_message','Sider Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $old_image = BannerTwo::find($id);
        BannerTwo::destroy($id);
        Storage::delete($old_image->image);
        Storage::delete($old_image->mb_image);
        return redirect('game/bannertwo')->with('flash_message', 'Slider deleted!');
    }

}
