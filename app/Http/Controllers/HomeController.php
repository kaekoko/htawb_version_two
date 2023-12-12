<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agent;
use App\Models\SuperAdmin;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return redirect('login');
    }

    public function login()
    {
        return view('frontend.login');
    }

    public function check(LoginRequest $request)
    {

        $super_admin = SuperAdmin::where('phone', request()->phone)->select('role')->first();
        $user = User::where('phone', request()->phone)->select('role', 'block')->first();
        $check_roles = [$super_admin, $user];
        $check_roles = array_filter($check_roles);

        $role = '';
        foreach ($check_roles as $check_role) {
            $role = $check_role->role;
        }

        if ($role) {
            if ($role == 1) {
                if (Auth::guard('super_admin')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
                    if (!empty($request->device_token)) {
                        $admin = SuperAdmin::where('phone', $request->phone)->first();
                        $req_token = [$request->device_token];
                        if (empty($admin->device_token)) {
                            $data = $req_token;
                        } else {
                            $data = array_merge(json_decode($admin->device_token), $req_token);
                        }
                        $admin->device_token = json_encode(array_unique($data));
                        $admin->update();
                    }
                    return redirect('super_admin/dashboard');
                } else {
                    return redirect()->back()->with('error_message', 'Phone Number Or Password Incorrect!');
                }
            }
            if ($role == 5) {
                if ($check_role->block == 0) {
                    if (Auth::guard('user')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
                        return redirect('user/dashboard');
                    } else {
                        return redirect()->back()->with('error_message', 'Phone Number Or Password Incorrect!');
                    }
                } else {
                    return redirect()->back()->with('error_message', 'Block User!');
                }
            }
        } else {
            return redirect()->back()->with('error_message', 'Phone Number Or Password Incorrect!');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
