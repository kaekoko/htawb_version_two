<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Models\User;
use App\Models\Agent;
use App\Models\UserBet;
use App\Models\MasterAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BetSlipCheck
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

        if(Auth::guard('senior_agent')->check()){
            // $senior_agent_id = Auth::guard('senior_agent')->user()->id;
            // $user_bet = UserBet::find($route_id);
            // $user = User::find($user_bet->user_id);
            // if($user->senior_agent_id == $senior_agent_id){
            //     return $next($request);
            // }else{
            //     return abort('403');
            // }
            return $next($request);
        }

        if(Auth::guard('master_agent')->check()){
            // $master_agent_id = Auth::guard('master_agent')->user()->id;
            // $user_bet = UserBet::find($route_id);
            // $user = User::find($user_bet->user_id);
            // if($user->master_agent_id == $master_agent_id){
            //     return $next($request);
            // }else{
            //     return abort('403');
            // }
            return $next($request);
        }

        if(Auth::guard('agent')->check()){
            // $agent_id = Auth::guard('agent')->user()->id;
            // $user_bet = UserBet::find($route_id);
            // $user = User::find($user_bet->user_id);
            // if($user->agent_id == $agent_id){
            //     return $next($request);
            // }else{
            //     return abort('403');
            // }
            return $next($request);
        }

    }
}
