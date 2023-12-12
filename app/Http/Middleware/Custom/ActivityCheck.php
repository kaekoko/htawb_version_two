<?php

namespace App\Http\Middleware\Custom;

use App\Invoker\invokeAll;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityCheck
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

        if(Auth::guard('super_admin')->user()->role_id != 1){

            invokeAll::activityCheck($request->path());
            return $next($request);

        }
        return $next($request);
    }
}
