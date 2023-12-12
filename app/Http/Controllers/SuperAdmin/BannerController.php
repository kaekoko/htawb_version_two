<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();
        return view('super_admin.banner.index',compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        $banner = new Banner();
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $banner->image = $image->store('banner');
        }
        if($request->hasFile('mb_image')) {
            $image = $request->file('mb_image');
            $banner->mb_image = $image->store('banner');
        }
        $banner->save();
        return redirect('super_admin/banner')->with('flash_message','Sider Created');
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
        $banner = Banner::findOrFail($id);
        return view('super_admin.banner.edit',compact('banner'));
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
        $banner = Banner::findOrFail($id);
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
        return redirect('super_admin/banner')->with('flash_message','Sider Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $old_image = Banner::find($id);
        Banner::destroy($id);
        Storage::delete($old_image->image);
        Storage::delete($old_image->mb_image);

        return redirect('super_admin/banner')->with('flash_message', 'Slider deleted!');
    }
}
