<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginCheck
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
            return redirect('super_admin/dashboard');
        }
        if(Auth::guard('senior_agent')->check()){
            return redirect('senior_agent/dashboard');
        }
        if(Auth::guard('master_agent')->check()){
            return redirect('master_agent/dashboard');
        }
        if(Auth::guard('agent')->check()){
            return redirect('agent/dashboard');
        }
        if(Auth::guard('user')->check()){
            return redirect('user/dashboard');
        }
        return $next($request);
    }
}
