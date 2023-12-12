<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function changePassView()
    {
        return view("super_admin.auth.change_pass");
    }

    public function changePass(Request $request)
    {
        if (Auth::guard('super_admin')->check()) {
            $auth_user = Auth::guard('super_admin')->user();
            if (Hash::check($request->old_password, $auth_user->password)) {
                $super_admin = SuperAdmin::find($auth_user->id);
                $super_admin->password = Hash::make($request->new_password);
                $super_admin->save();
                Log::info("Change Password Successful.");
                return redirect()->back()->with([
                    'flash_message' => 'Change Password Successful.'
                ]);
            } else {
                Log::debug("Old password does not correct.");
                return redirect()->back()->with([
                    'error_message' => 'Change Password Failed.'
                ]);
            }
        } else {
            Log::debug("No Auth");
            return redirect()->back()->with([
                'error_message' => 'Change Password Failed.'
            ]);
        }
    }
}
