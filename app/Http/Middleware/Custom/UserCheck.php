<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCheck
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
            // if($request->route()->uri == 'super_admin/user/{user}' || $request->route()->uri == 'super_admin/user/{user}/edit'){
            //     $route_id = $request->route('user');
            //     $auth_id = Auth::guard('super_admin')->user()->id;
            //     $user_check = User::findorFail($route_id);
            //     if($user_check->super_admin_id == $auth_id){
            //         return $next($request);
            //     }else{
            //         return abort('403');
            //     }
            // }else{
            //     return $next($request);
            // }
            return $next($request);
        }
        if(Auth::guard('senior_agent')->check()){
            if($request->route()->uri == 'senior_agent/user/{user}' || $request->route()->uri == 'senior_agent/user/{user}/edit'){
                $route_id = $request->route('user');
                $auth_id = Auth::guard('senior_agent')->user()->id;
                $user_check = User::findorFail($route_id);
                if($user_check->senior_agent_id == $auth_id){
                    return $next($request);
                }else{
                    return abort('403');
                }
            }else{
                return $next($request);
            }
        }
        if(Auth::guard('master_agent')->check()){
            if($request->route()->uri == 'master_agent/user/{user}' || $request->route()->uri == 'master_agent/user/{user}/edit'){
                $route_id = $request->route('user');
                $auth_id = Auth::guard('master_agent')->user()->id;
                $user_check = User::findorFail($route_id);
                if($user_check->master_agent_id == $auth_id){
                    return $next($request);
                }else{
                    return abort('403');
                }
            }else{
                return $next($request);
            }
        }
        if(Auth::guard('agent')->check()){
            if($request->route()->uri == 'agent/user/{user}' || $request->route()->uri == 'agent/user/{user}/edit'){
                $route_id = $request->route('user');
                $auth_id = Auth::guard('agent')->user()->id;
                $user_check = User::findorFail($route_id);
                if($user_check->agent_id == $auth_id){
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
