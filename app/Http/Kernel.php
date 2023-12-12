<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\ActivityByUser::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            "throttle:5000000,1",
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\ActivityByUser::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        // 'csrf' => \Http\Middleware\VerifyCsrfToken::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'login_check' => \App\Http\Middleware\Custom\LoginCheck::class,
        'super_admin' => \App\Http\Middleware\Custom\SuperAdmin::class,
        'senior_agent' => \App\Http\Middleware\Custom\SeniorAgent::class,
        'master_agent' => \App\Http\Middleware\Custom\MasterAgent::class,
        'agent' => \App\Http\Middleware\Custom\Agent::class,
        'user' => \App\Http\Middleware\Custom\User::class,
        'notification_admin' => \App\Http\Middleware\Custom\NotificationAdmin::class,
        'super_admin_account_check' => \App\Http\Middleware\Custom\SuperAdminAccountCheck::class,
        'senior_agent_account_check' => \App\Http\Middleware\Custom\SeniorAgentAccountCheck::class,
        'master_agent_account_check' => \App\Http\Middleware\Custom\MasterAgentAccountCheck::class,
        'agent_account_check' => \App\Http\Middleware\Custom\AgentAccountCheck::class,
        'user_check' => \App\Http\Middleware\Custom\UserCheck::class,
        'profile_check' => \App\Http\Middleware\Custom\ProfileCheck::class,
        'cash_in_out_check' => \App\Http\Middleware\Custom\CashInOutCheck::class,
        'bet_slip_check' => \App\Http\Middleware\Custom\BetSlipCheck::class,
        'lucky_number_check' => \App\Http\Middleware\Custom\LuckyNumberCheck::class,
        'activity_check' => \App\Http\Middleware\Custom\ActivityCheck::class,
        'super_admin_role' => \App\Http\Middleware\Custom\SuperAdminRoleCheck::class,
        'super_admin_or_admin_role' => \App\Http\Middleware\Custom\SuperAdminOrAdminRoleCheck::class
    ];
}