<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Models\SeniorAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeniorAgentAccountCheck
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
        $route_id = $request->route('id');
        if(Auth::guard('super_admin')->check()){
            // $auth_id = Auth::guard('super_admin')->user()->id;
            // $senior_agent_check = SeniorAgent::findorFail($route_id);
            // if($senior_agent_check->super_admin_id == $auth_id){
            //     return $next($request);
            // }else{
            //     return abort('403');
            // }
            return $next($request);
        }
        if(Auth::guard('senior_agent')->check()){
            $auth_id = Auth::guard('senior_agent')->user()->id;
            $senior_agent_check = SeniorAgent::findorFail($route_id);
            if($senior_agent_check->senior_agent_id == $auth_id){
                return $next($request);
            }else{
                return abort('403');
            }
        }
    }
}
