<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Agent;
use App\Models\CashIn;
use App\Models\CashOut;
use App\Models\UserBet;
use App\Models\UserBet1d;
use App\Models\UserBet3d;
use App\Models\MasterAgent;
use App\Models\SuperAdminNoti;
use App\Models\SeniorAgentNoti;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Event::listen(MigrationsStarted::class, function () {
            if (config('databases.allow_disabled_pk')) {
                DB::statement('SET SESSION sql_require_primary_key=0');
            }
        });

        Event::listen(MigrationsEnded::class, function () {
            if (config('databases.allow_disabled_pk')) {
                DB::statement('SET SESSION sql_require_primary_key=1');
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        View::composer('*', function ($view) {
            if (Auth::guard('super_admin')->check()) {
                $auth_id = Auth::guard('super_admin')->user()->id;

                $today = date('Y-m-d');
                // BetSlips
                // $bet_slip_1d_count = UserBet1d::where('read', 0)->where('date', $today)->get();
                // $bet_slip_count = UserBet::where('read', 0)->where('date', $today)->get();
                // $bet_slip_3d_count = UserBet3d::where('read', 0)->where('date', $today)->get();
                // $bet_slip_c2d_count = UserBetCrypto2d::where('read', 0)->where('date', $today)->get();
                // $bet_slip_c1d_count = UserBetCrypto1d::where('read', 0)->where('date', $today)->get();
                // CashIn or Out
                // $cash_in_count = CashIn::where('read', 0)->where('date', $today)->get();
                // $cash_out_count = CashOut::where('read', 0)->where('date', $today)->get();
                // User
                // $user_count =  User::where('side',null)->get();
                // $user_count_i =  User::where('side',1)->get();
                // $view->with('bet_slip_count', $bet_slip_count)
                    // ->with('bet_slip_3d_count', $bet_slip_3d_count)
                    // ->with('bet_slip_c2d_count', $bet_slip_c2d_count)
                    // ->with('bet_slip_c1d_count', $bet_slip_c1d_count)
                    // ->with('cash_in_count', $cash_in_count)
                    // ->with('cash_out_count', $cash_out_count)
                    // ->with('bet_slip_1d_count', $bet_slip_1d_count)
                    // ->with('user_count', $user_count)
                    // ->with('user_count_i', $user_count_i);
            }

            if (Auth::guard('senior_agent')->check()) {
                $auth_id = Auth::guard('senior_agent')->user()->id;
                $master_agent_id = MasterAgent::where('senior_agent_id', $auth_id)->select('id')->get();
                $agent_id = Agent::where('senior_agent_id', $auth_id)->select('id')->get();


                $today = date('Y-m-d');
                $bet_slip_count = UserBet::where('read', 0)->whereHas('user',  function ($q) use ($auth_id, $master_agent_id, $agent_id) {
                    $q->where('senior_agent_id', $auth_id)
                        ->orWhereIn('master_agent_id', $master_agent_id)
                        ->orWhereIn('agent_id', $agent_id);
                })->where('date', $today)->get();

                $view->with('bet_slip_count', $bet_slip_count);
            }

            if (Auth::guard('master_agent')->check()) {
                $auth_id = Auth::guard('master_agent')->user()->id;
                $agent_id = Agent::where('master_agent_id', $auth_id)->select('id')->get();

                $today = date('Y-m-d');
                $bet_slip_count = UserBet::where('read', 0)->whereHas('user',  function ($q) use ($auth_id, $agent_id) {
                    $q->where('master_agent_id', $auth_id)
                        ->orWhereIn('agent_id', $agent_id);
                })->where('date', $today)->get();
                $view->with('bet_slip_count', $bet_slip_count);
            }

            if (Auth::guard('agent')->check()) {
                $auth_id = Auth::guard('agent')->user()->id;
                $today = date('Y-m-d');
                $bet_slip_count = UserBet::where('read', 0)->whereHas('user',  function ($q) use ($auth_id) {
                    $q->where('agent_id', $auth_id);
                })->where('date', $today)->get();
                $view->with('bet_slip_count', $bet_slip_count);
            }
        });

        Blade::directive('convert', function ($money) {
            return "<?php echo number_format($money); ?>";
        });
    }
}
