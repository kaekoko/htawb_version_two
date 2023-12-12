<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Models\User;
use App\Models\Agent;
use App\Models\SuperAdmin;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard('super_admin')->check()){
            if($request->route()->uri == 'super_admin/profile/{profile}' || $request->route()->uri == 'super_admin/profile/{profile}/edit'){
                $route_id = $request->route('profile');
                $auth_id = Auth::guard('super_admin')->user()->id;
                $profile_check = SuperAdmin::findorFail($route_id);
                if($profile_check->id == $auth_id){
                    return $next($request);
                }else{
                    return abort('403');
                }
            }else{
                return $next($request);
            }
        }
        if(Auth::guard('senior_agent')->check()){
            if($request->route()->uri == 'senior_agent/profile/{profile}' || $request->route()->uri == 'senior_agent/profile/{profile}/edit'){
                $route_id = $request->route('profile');
                $auth_id = Auth::guard('senior_agent')->user()->id;
                $profile_check = SeniorAgent::findorFail($route_id);
                if($profile_check->id == $auth_id){
                    return $next($request);
                }else{
                    return abort('403');
                }
            }else{
                return $next($request);
            }
        }
        if(Auth::guard('master_agent')->check()){
            if($request->route()->uri == 'master_agent/profile/{profile}' || $request->route()->uri == 'master_agent/profile/{profile}/edit'){
                $route_id = $request->route('profile');
                $auth_id = Auth::guard('master_agent')->user()->id;
                $profile_check = MasterAgent::findorFail($route_id);
                if($profile_check->id == $auth_id){
                    return $next($request);
                }else{
                    return abort('403');
                }
            }else{
                return $next($request);
            }
        }
        if(Auth::guard('agent')->check()){
            if($request->route()->uri == 'agent/profile/{profile}' || $request->route()->uri == 'agent/profile/{profile}/edit'){
                $route_id = $request->route('profile');
                $auth_id = Auth::guard('agent')->user()->id;
                $profile_check = Agent::findorFail($route_id);
                if($profile_check->id == $auth_id){
                    return $next($request);
                }else{
                    return abort('403');
                }
            }else{
                return $next($request);
            }
        }
        if(Auth::guard('user')->check()){
            if($request->route()->uri == 'user/profile/{profile}' || $request->route()->uri == 'user/profile/{profile}/edit'){
                $route_id = $request->route('profile');
                $auth_id = Auth::guard('user')->user()->id;
                $profile_check = User::findorFail($route_id);
                if($profile_check->id == $auth_id){
                    return $next($request);
                }else{
                    return abort('403');
                }
            }else{
                return $next($request);
            }
        }
    }
}
