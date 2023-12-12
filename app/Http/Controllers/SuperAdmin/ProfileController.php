<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\checkPhone;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return abort(404, 'Page Not Found');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404, 'Page Not Found');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404, 'Page Not Found');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile = SuperAdmin::findOrFail($id);
        return view('super_admin.profile.show',compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $profile = SuperAdmin::findOrFail($id);
        return view('super_admin.profile.edit',compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request, $id)
    {
        if($request->phone){
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
                return redirect("super_admin/profile/$id")->with('flash_message', 'Profile Updated!');
            }else{
                return redirect("super_admin/profile/$id/edit")->with('error_message','Phone Number Exit!');
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
            return redirect("super_admin/profile/$id")->with('flash_message', 'Profile Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404, 'Page Not Found');
    }
}
