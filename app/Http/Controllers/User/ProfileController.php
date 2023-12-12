<?php

namespace App\Http\Controllers\User;

use App\Helper\checkPhone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;

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
        $profile = User::findOrFail($id);
        return view('user.profile.show',compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $profile = User::findOrFail($id);
        return view('user.profile.edit',compact('profile'));
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
                $old_password = User::findOrFail($id)->password;
                $request->password = $request->password ? Hash::make($request->password) : $old_password;

                $user = User::findOrFail($id);
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->password = $request->password;

                if($request->hasFile('image')) {
                    Storage::delete($user->image);
                    $user->image =  $request->file('image') ? $request->file('image')->store('user') : $user->image;
                }
                $user->update();
                return redirect("user/profile/$id")->with('flash_message', 'Profile Updated!');
            }else{
                return redirect("user/profile/$id/edit")->with('error_message','Phone Number Exit!');
            }
        }else{
            $old_password = User::findOrFail($id)->password;
            $request->password = $request->password ? Hash::make($request->password) : $old_password;

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->password = $request->password;

            if($request->hasFile('image')) {
                Storage::delete($user->image);
                $user->image =  $request->file('image') ? $request->file('image')->store('user') : $user->image;
            }
            $user->update();
            return redirect("user/profile/$id")->with('flash_message', 'Profile Updated!');
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

