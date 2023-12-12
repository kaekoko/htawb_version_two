<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\checkPhone;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AdminCreateRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = SuperAdmin::whereNotNull('super_admin_id')->get();
        return view ('super_admin.admin.index',compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view ('super_admin.admin.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCreateRequest $request)
    {
        $super_admin_id = Auth::guard('super_admin')->user()->id;
        if(checkPhone::number() == 0){
            if($request->hasFile('image')) {
                $image = $request->file('image');
                $super_admin = new SuperAdmin();
                $super_admin->super_admin_id = $super_admin_id;
                $super_admin->name = $request->name;
                $super_admin->phone = $request->phone;
                $super_admin->role_id = $request->role_id;
                $super_admin->password = Hash::make($request->password);
                $super_admin->image = $image->store('super_admin');
                $super_admin->save();
            }else{
                $super_admin = new SuperAdmin();
                $super_admin->super_admin_id = $super_admin_id;
                $super_admin->name = $request->name;
                $super_admin->phone = $request->phone;
                $super_admin->role_id = $request->role_id;
                $super_admin->password = Hash::make($request->password);
                $super_admin->save();
            }
            return redirect('super_admin/admin')->with('flash_message','Super Admin Created');
        }else{
            return redirect("super_admin/admin/create")->with('error_message','Phone Number Exit!');
        }
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
        $admin = SuperAdmin::findOrFail($id);
        $roles = Role::all();
        return view('super_admin.admin.edit',compact('admin','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUpdateRequest $request, $id)
    {
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
                $super_admin->role_id = $request->role_id;

                if($request->hasFile('image')) {
                    Storage::delete($super_admin->image);
                    $super_admin->image =  $request->file('image') ? $request->file('image')->store('super_admin') : $super_admin->image;
                }
                $super_admin->update();
                return redirect('super_admin/admin')->with('flash_message', 'Admin updated!');
            }else{
                return redirect("super_admin/admin/$id/edit")->with('error_message','Phone Number Exit!');
            }
        }else{
            $old_password = SuperAdmin::findOrFail($id)->password;
            $request->password = $request->password ? Hash::make($request->password) : $old_password;

            $super_admin = SuperAdmin::findOrFail($id);
            $super_admin->name = $request->name;
            $super_admin->password = $request->password;
            $super_admin->role_id = $request->role_id;

            if($request->hasFile('image')) {
                Storage::delete($super_admin->image);
                $super_admin->image =  $request->file('image') ? $request->file('image')->store('super_admin') : $super_admin->image;
            }
            $super_admin->update();
            return redirect('super_admin/admin')->with('flash_message', 'Admin updated!');
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
        SuperAdmin::find($id)->delete();
        return redirect('super_admin/admin')->with('flash_message','Admin Account Deleted');
    }
}
