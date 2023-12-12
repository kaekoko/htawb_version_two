<?php

use App\Models\User;
use App\Invoker\invokeAll;
use App\Models\LuckyNumber;
use Illuminate\Http\Request;
use App\Models\BettingCrypto1d;
use App\Models\BettingCrypto2d;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OnlyBetSLipController;
use App\Http\Controllers\SuperAdmin\GameController;
use App\Http\Controllers\SuperAdmin\LiveController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\CashInController;
use App\Http\Controllers\SuperAdmin\CashOutController;
use App\Http\Controllers\SuperAdmin\SettingController;
use App\Http\Controllers\SuperAdmin\OthersideController;
use App\Http\Controllers\SuperAdmin\MemberInfoController;
use App\Http\Controllers\SuperAdmin\TwoBetOnlyController;
use App\Http\Controllers\SuperAdmin\LuckyNumberController;
use App\Http\Controllers\SuperAdmin\PaymentMethodController;
use App\Http\Controllers\SuperAdmin\GameManagementController;
use App\Http\Controllers\SuperAdmin\DashboardCrypto2dController;
use App\Http\Controllers\SuperAdmin\BetslipTransactionController;

/*
|--------------------------------------------------------------------------
| SuperAdmin Routes
|--------------------------------------------------------------------------
*/
// Super Admin
Route::group(['middleware' => ['super_admin'], 'namespace' => 'SuperAdmin', 'prefix' => 'super_admin'], function () {
    // Super Admin Only
    Route::group(['middleware' => ['super_admin_role']], function () {
        Route::resource('admin', 'AdminController');
        Route::resource('over_all_setting', 'OverAllSettingController');
    });

    // Super Admin & Admin
    Route::group(['middleware' => ['super_admin_or_admin_role']], function () {
        // Payment
        Route::resource('payment_method', 'PaymentMethodController');
        Route::post('payment_method/update_cashin_status', 'PaymentMethodController@updateCashinStatus');
        Route::post('payment_method/update_cashout_status', 'PaymentMethodController@updateCashoutStatus');
        Route::post('payment_method/update_status', [PaymentMethodController::class, 'updateStatus']);
        Route::get('/payment-account-delete/{id}', [PaymentMethodController::class, 'paymentAccountDelete'])->name('paymentaccount.delete');

        // paymentod two

        //2D Sectio
        Route::resource('section', 'SectionController');
        Route::post('open_or_not_section', 'SectionController@openOrNotSection2D');
        // C1D Section

        Route::get('section_c1d/{id}/edit', 'SectionController@edit_c1d');
        Route::post('section_c1d/update/{id}', 'SectionController@update_c1d');
        Route::post('open_or_not_section_c1d', 'SectionController@openOrNotSectionC1D');

        // 1D Section
        Route::get('section_1d/create', 'SectionController@create_1d');
        Route::post('section_1d/store', 'SectionController@store_1d');
        Route::get('section_1d/{id}/edit', 'SectionController@edit_1d');
        Route::post('section_1d/update/{id}', 'SectionController@update_1d');
        Route::post('section_1d/{id}/destroy', 'SectionController@destroy_1d');
        Route::post('open_or_not_section_1d', 'SectionController@openOrNotSection1D');
        //3D Section
        Route::get('section_3d/create', 'SectionController@create_3d');
        Route::post('section_3d/store', 'SectionController@store_3d');
        Route::get('section_3d/{id}/edit', 'SectionController@edit_3d');
        Route::post('section_3d/update/{id}', 'SectionController@update_3d');
        Route::post('section_3d/{id}/destroy', 'SectionController@destroy_3d');
        // Crypto 2D Section
        Route::get('section_c2d/{time_section}/edit', 'SectionCrypto2dController@edit');
        Route::put('section_c2d/{time_section}', 'SectionCrypto2dController@update')->name('section_crypto_2d.update');
        //hot block
        Route::get('hotblock', 'HotBlockController@index');
        Route::post('hotblock/{id}', 'HotBlockController@hotblock');
        Route::post('hotblock3d/{id}', 'HotBlockController@hotblock3d');

        // hot block cyrpto 2D
        Route::get('hotblock_c1d', 'HotBlockController@index_c1d');
        Route::post('hotblock_c1d/{hotblock}', 'HotBlockController@hotblock_c1d');
        // hot block 1D
        Route::get('hotblock1d', 'HotBlockController@index_1d');
        Route::post('hotblock1d/{id}', 'HotBlockController@hotblock_1d');
        // hot block cyrpto 2D
        Route::get('hotblock_c2d', 'HotBlockController@index_c2d');
        Route::post('hotblock_c2d/{hotblock}', 'HotBlockController@hotblock_c2d');
        //hot block 3d
        Route::get('hotblock_3d', 'HotBlockController@index_3d');
        Route::post('hot_3d/create', 'HotBlockController@create_hot_3d');
        Route::post('block_3d/create', 'HotBlockController@create_block_3d');
        Route::post('hot_3d/edit/{id}', 'HotBlockController@edit_hot_3d');
        Route::post('block_3d/edit/{id}', 'HotBlockController@edit_block_3d');
        Route::post('hotblock_3d/delete/{id}', 'HotBlockController@delete_hot_block');
        // Crypto 1D
        Route::get('lucky_number_c1d', 'LuckyNumberController@index_c1d');
        Route::get('lucky_number_c1d/create', 'LuckyNumberController@create_c1d');
        Route::post('lucky_number_c1d/approve/{lucky_number}', 'LuckyNumberController@approve_c1d');
        Route::post('lucky_c1d/{id}','LuckyNumberController@add_crypto_1d')->name('lucky_c1d.id');
        // 1D lucky_number
        Route::resource('lucky_number_1d', 'LuckyNumberOneController');
        Route::post('lucky_number/approve_1d/{id}', 'LuckyNumberOneController@approve_1d');
        // 2D lucky_number
        Route::resource('lucky_number', 'LuckyNumberController');
        Route::post('lucky/{id}','LuckyNumberController@add')->name('lucky.admin.id');

        Route::post('luckyOne/{id}','LuckyNumberOneController@add')->name('lucky.id');
        Route::post('lucky_number/approve/{id}', 'LuckyNumberController@approve');
        // 3D lucky_number
        Route::get('lucky_number_3d', 'LuckyNumberController@index_3d');
        Route::post('lucky_number/approve_3d/{id}', 'LuckyNumberController@approve_3d');
        Route::post('lucky_c2d/{id}','LuckyNumberController@add_crypto')->name('lucky_c2d.id');
        // Crypto 2D lucky_number
        Route::get('lucky_number_c2d', 'LuckyNumberController@index_c2d');
        Route::get('lucky_number_c2d/create', 'LuckyNumberController@create_c2d');
        Route::post('lucky_number_c2d/approve/{lucky_number}', 'LuckyNumberController@approve_c2d');
        // Setting
        Route::get('/settings', [SettingController::class, 'index']);
        Route::post('/cashin-mini/{key}', [SettingController::class, 'cashInMinimumAmount'])->name('cashin.mini');
        Route::post('/cashout-mini/{key}', [SettingController::class, 'cashOutMinimumAmount'])->name('cashout.mini');
        Route::post('/cashin-amount/{key}', [SettingController::class, 'cashInAmount'])->name('cashin.amount');
        Route::post('/cashout-amount/{key}', [SettingController::class, 'cashOutAmount'])->name('cashout.amount');
        Route::post('/welcome-bonus/{key}', [SettingController::class, 'welcomeBonus'])->name('welcome.bonus');
        Route::put('/app-update/{id}', [SettingController::class, 'appForceUpdate'])->name('app.update');
        Route::post('/toggle_game_maintenance', [SettingController::class, 'toggleGameMainTenance']);

        Route::post('/toggle_myvip_wave', [SettingController::class, 'MyvipWave']);
        Route::post('/toggle_icasino_wave', [SettingController::class, 'IcasinoWave']);
    });
    // Change Pass/clearance_3d
    Route::get('change_pass', 'AuthController@changePassView');
    Route::post('change_pass', 'AuthController@changePass');
    // Super Admin & Admin & Staff
    Route::get('dashboard', 'DashboardController@dashboard')->middleware('activity_check');
    // 2d
    Route::get('dashboard/section/{section}','DashboardController@section')->name('dashboard.section');

    // 1d
    Route::get('dashboard/section1d/{section}','Dashboard1dController@section')->name('dashboard.section1d');

    // Crypto 2d
    Route::get('dashboard/sectionc2d/{section}','DashboardCrypto2dController@section')->name('dashboard.sectionc2d');

    // Crypto 2d
    Route::get('dashboard/sectionc1d/{section}','DashboardCrypto1dController@section')->name('dashboard.sectionc1d');


    Route::get('dashboard/sectionc3d/{section}','Dashboard3dController@section')->name('dashboard.section3d');

    Route::get('dashboard_1d', 'Dashboard1dController@dashboard')->middleware('activity_check');
    Route::get('dashboard_3d', 'Dashboard3dController@dashboard_3d')->middleware('activity_check');
    Route::get('dashboard_c2d', 'DashboardCrypto2dController@dashboard');
    Route::get('dashboard_c1d', 'DashboardCrypto1dController@dashboard_c1d');
    Route::resource('user', 'UserController');
    Route::get('userstatus', 'UserController@userOnlineStatus');
    Route::get('useronly/{id}',function($id){
        $users = User::where('id',$id)->paginate(10);
        return view('super_admin.user.index', compact('users'));
    });


    Route::get('promotion/dashboard','PromotionDashboard@dashboard');
    Route::get('promotion/index','PromotionDashboard@index');
    Route::post('promotion/approve','PromotionDashboard@approve');
    Route::post('promotion/complete','PromotionDashboard@complete');
    Route::post('promotion/approve/refund','PromotionDashboard@refund');

    Route::post('usernoti','UserController@notionly');
    Route::get('getusers', 'UserController@getusers');
    Route::get('userlvltwo', 'UserController@userlvltwo');
    Route::post('user/user_lvl_2/approve/{id}', 'UserController@user_lvl_2_approve');
    Route::post('user/user_lvl_2/reject/{id}', 'UserController@user_lvl_2_reject');
    Route::resource('profile', 'ProfileController')->middleware('profile_check');
    Route::resource('category', 'CategoriesController');
    Route::resource('service', 'ServiceController');
    Route::resource('lottery_off_day', 'LotteryOffDayController');
    Route::resource('blog', 'BlogController');
    Route::resource('city', 'CityController');
    Route::resource('header_play_text', 'HeaderPLayTextController');
    Route::resource('banner', 'BannerController');

    Route::resource('noti', 'NotiController');
    Route::get('update_noti/{id}', 'NotiController@updateNoti');
    //notification
    Route::get('/notification', 'NotiController@getAllNotiCount');
    // credit in
    Route::get('credit_history', 'CreditController@credit_history');
    Route::get('credit_all_agent', 'CreditController@credit_all_agent');
    Route::post('senior_agent_credit', 'CreditController@senior_agent_credit');
    Route::post('master_agent_credit', 'CreditController@master_agent_credit');
    Route::post('agent_credit', 'CreditController@agent_credit');
    // commission
    Route::get('commission_history', 'CommissionHistroyController@commission_history');
    Route::get('user_refer_history', 'CommissionHistroyController@user_refer_history');
    //user block
    Route::post('user/add_block', 'BlockController@user_add_block');

    //2D Bet Slip
    Route::get('bet_slip', 'BetSlipController@bet_slip');
    Route::get('bet_slip/{id}', 'BetSlipController@bet_slip_detail');
    Route::post('bet_slip/pay_reward/{id}/{amount}', 'BetSlipController@pay_reward');

    //1D Bet Slip
    Route::get('bet_slip_1d', 'BetSlip1dController@bet_slip');
    Route::get('bet_slip_1d/{id}', 'BetSlip1dController@bet_slip_detail');
    Route::post('bet_slip_1d/pay_reward/{id}/{amount}', 'BetSlip1dController@pay_reward');

    // Crypto 1D Bet Slip
    Route::get('bet_slip_c1d', 'BetSlipCrypto1dController@bet_slip_crypto_1d');
    Route::get('bet_slip_c1d/{id}', 'BetSlipCrypto1dController@bet_slip_crypto_1d_detail');

    //3D Bet Slip
    Route::get('bet_slip_3d', 'BetSlip3dController@bet_slip_3d');
    Route::get('bet_slip_3d/{id}', 'BetSlip3dController@bet_slip_detail');

    // Crypto 2D Bet Slip
    Route::get('bet_slip_c2d', 'BetSlipCrypto2dController@bet_slip_crypto_2d');
    Route::get('bet_slip_c2d/{id}', 'BetSlipCrypto2dController@bet_slip_crypto_2d_detail');

    //Cash in Cash out
    Route::get('cash_in/{id}', 'CashInController@cash_in');
    Route::post('cash_in_create/{id}', 'CashInController@cash_in_create');
    Route::get('cash_in_history/{id}', 'CashInController@cash_in_history');

    Route::get('cash_out/{id}', 'CashOutController@cash_out');
    Route::post('cash_out_create/{id}', 'CashOutController@cash_out_create');
    Route::get('cash_out_history/{id}', 'CashOutController@cash_out_history');

    //live
    Route::get('times', [LiveController::class, 'times']);
    Route::post('custom/{id}', [LiveController::class, 'custom']);
    Route::post('custom_c2d/{custom_record}', [LiveController::class, 'custom_c2d']);

    //Dashboard
    Route::post('grant_numbers', 'BetSlipController@all_bets');
    Route::post('daily_grant_numbers', 'BetSlipController@daily_all_bets');
    Route::post('time_statics', 'DashboardController@time_statics');
    Route::post('daily_statics', 'DashboardController@daily_statics');
    Route::post('single_detail', 'BetSlipController@single_bet_detail');
    Route::post('daily_detail', 'BetSlipController@daily_bet_detail');

    Route::post('clearance', 'DashboardController@clearance');
    Route::post('check_password', 'DashboardController@check_password');
    Route::post('bet_slips', 'DashboardController@bet_slips');
    Route::post('daily_bet_slips', 'DashboardController@daily_bet_slips');
    Route::post('check_ref_password', 'DashboardController@check_ref_password');

    // 1d Dashboard
    //Dashboard
    Route::post('grant_numbers_1d', 'BetSlip1dController@all_bets');
    Route::post('daily_grant_numbers_1d', 'BetSlip1dController@daily_all_bets');
    Route::post('time_statics_1d', 'Dashboard1dController@time_statics');
    Route::post('daily_statics_1d', 'Dashboard1dController@daily_statics');
    Route::post('single_detail_1d', 'BetSlip1dController@single_bet_detail');
    Route::post('daily_detail_1d', 'BetSlip1dController@daily_bet_detail');

    Route::post('clearance_1d', 'Dashboard1dController@clearance');
    Route::post('check_password_1d', 'Dashboard1dController@check_password');
    Route::post('bet_slips_1d', 'Dashboard1dController@bet_slips');
    Route::post('daily_bet_slips_1d', 'Dashboard1dController@daily_bet_slips');
    Route::post('check_ref_password_1d', 'Dashboard1dController@check_ref_password');

    // Dashboard Crypto 1D
    Route::post('grant_numbers_c1d', 'BetSlipCrypto1dController@all_bets_c1d');
    Route::post('clearance_c1d', 'DashboardCrypto1dController@clearance_c1d');
    Route::post('clearance_c1d_money', 'DashboardCrypto1dController@clearance_money_c1d');
    Route::post('refund_money_c1d', 'DashboardCrypto1dController@refund_money_c1d');
    Route::post('time_statics_c1d', 'DashboardCrypto1dController@time_statics_c1d');
    Route::post('daily_grant_numbers_c1d', 'BetSlipCrypto1dController@daily_all_bets_c1d');
    Route::post('daily_statics_c1d', 'DashboardCrypto1dController@daily_statics_c1d');
    Route::post('single_detail_c1d', 'BetSlipCrypto1dController@single_bet_detail_c1d');
    Route::post('daily_detail_c1d', 'BetSlipCrypto1dController@daily_bet_detail_c1d');

    Route::post('bet_slips_c1d', 'DashboardCrypto1dController@bet_slips_c1d');
    Route::post('daily_bet_slips_c1d', 'DashboardCrypto1dController@daily_bet_slips_c1d');

    //Dashboard Crypto 2D
    Route::post('grant_numbers_c2d', 'BetSlipCrypto2dController@all_bets');
    Route::post('clearance_c2d', 'DashboardCrypto2dController@clearance');
    Route::post('clearance_c2d_money', 'DashboardCrypto2dController@clearance_money');
    Route::post('refund_money', 'DashboardCrypto2dController@refund_money');
    Route::post('time_statics_c2d', 'DashboardCrypto2dController@time_statics');
    Route::post('daily_grant_numbers_c2d', 'BetSlipCrypto2dController@daily_all_bets');
    Route::post('daily_statics_c2d', 'DashboardCrypto2dController@daily_statics');
    Route::post('single_detail_c2d', 'BetSlipCrypto2dController@single_bet_detail');
    Route::post('daily_detail_c2d', 'BetSlipCrypto2dController@daily_bet_detail');

    Route::post('bet_slips_c2d', 'DashboardCrypto2dController@bet_slips');
    Route::post('daily_bet_slips_c2d', 'DashboardCrypto2dController@daily_bet_slips');

    //Dashboard 3D
    Route::post('grant_numbers_3d', 'Dashboard3dController@grant_numbers_3d');
    Route::post('time_statics_3d', 'Dashboard3dController@time_statics_3d');
    Route::post('single_detail_3d', 'Dashboard3dController@single_detail_3d');
    Route::post('hot_block_3d', 'Dashboard3dController@hot_block_3d');
    Route::post('clearance_3d', 'Dashboard3dController@clearance_3d');
    Route::post('clearance_status_3d', 'Dashboard3dController@clearance_status_3d');
    Route::post('refund_3d', 'Dashboard3dController@refund_3d');

    // Report
    Route::get('report_2d', 'ReportController@report_2d');
    Route::get('report_1d', 'ReportController@report_1d');
    Route::get('report_3d', 'ReportController@report_3d');
    Route::get('report_c2d', 'ReportController@report_crypto_2d');
    Route::get('report_c1d', 'ReportController@report_crypto_1d');
    Route::post('daily_static', 'ReportController@daily_static');
    Route::get('report_user', 'ReportController@report_user');

    //activity log
    Route::get('activity_log', 'ActivityLogController@index');

    Route::post('approveC1d',function(Request $request){
        $lucky_number = LuckyNumber::where('id',$request->id)->first();
        $lucky_number->approve = 1;
        $lucky_number->save();

        $userbets = BettingCrypto1d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('user_bets')->get();
            foreach ($userbets as $bet) {
                if ($bet->bet_number == $lucky_number->lucky_number) {
                    $bet->win = 1;
                    $bet->save();
                } else {
                    $bet->win = 2;
                    $bet->save();
                }
            }

            $bettings = UserBetCrypto1d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('bettings')->get();
            foreach ($bettings as $b) {
                $check = $b->bettings->where('win', '=', 1)->first();
                if (!empty($check)) {
                    $b->win = 1;
                    $b->reward_amount = $check->amount * $check->odd;
                    $b->save();

                    //firebase win noti
                    invokeAll::winLuckyNumberNoti($b->user_id, $lucky_number->lucky_number);
                } else {
                    $b->win = 2;
                    $b->save();
                }
            }

            return back()->with(['flash_message' => 'Approved']);
    });

    Route::post('app_c2d',function(Request $request){
        $lucky_number = LuckyNumber::where('id',$request->id)->first();
        $lucky_number->approve = 1;
        $lucky_number->save();

        $userbets = BettingCrypto2d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('user_bets_c2d')->get();
        foreach ($userbets as $bet) {
            if ($bet->bet_number == $lucky_number->lucky_number) {
                $bet->win = 1;
                $bet->save();
            } else {
                $bet->win = 2;
                $bet->save();
            }
        }

        $bettings = UserBetCrypto2d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('bettings_c2d')->get();
        foreach ($bettings as $b) {
            $check = $b->bettings_c2d->where('win', '=', 1)->first();
            if (!empty($check)) {
                $b->win = 1;
                $b->reward_amount = $check->amount * $check->odd;
                $b->save();

                //firebase win noti
                invokeAll::winLuckyNumberNoti($b->user_id, $lucky_number->lucky_number);
            } else {
                $b->win = 2;
                $b->save();
            }
        }
        return back()->with(['flash_message' => 'Approved']);
    });

    // CashIn
    Route::get('/cash-in', [CashInController::class, 'index']);
    Route::get('/cashinsearch', 'CashInController@search')->name('cashinsearch');
    Route::post('/cashin_confirm/{id}', [CashInController::class, 'cashinConfirm'])->name('cashin.confirm');
    Route::post('/cashin_reject/{id}', [CashInController::class, 'cashinReject'])->name('cashin.reject');

    // CashOut
    Route::get('/cash-out', [CashOutController::class, 'index']);
    Route::post('/cashout_confirm/{id}', [CashOutController::class, 'cashoutConfirm'])->name('cashout.confirm');
    Route::post('/cashout_reject/{id}', [CashOutController::class, 'cashoutReject'])->name('cashout.reject');

    //spin wheel
    Route::get('spin_wheel', 'SpinWheelController@index');
    Route::post('spin_wheel/update/{id}', 'SpinWheelController@update');
    Route::post('spin_wheel/refresh', 'SpinWheelController@refresh');
    Route::post('spin_wheel/range_amount', 'SpinWheelController@range_amount');
    Route::post('spin_wheel/range_amount/{id}', 'SpinWheelController@range_amount_update');
    Route::post('spin_wheel/range_amount/delete/{id}', 'SpinWheelController@range_amount_delete');
    Route::post('spin_wheel/lvl_2_on_off', 'SpinWheelController@lvl_2_on_off');
    Route::post('spin_wheel/spin_free_on_off', 'SpinWheelController@spin_free_on_off');

    //Promo cash in
    Route::get('promo_cash_in', 'PromoCashInController@index');
    Route::post('create_cash_in_promo', 'PromoCashInController@create');
    Route::post('update_cash_in_promo/{id}', 'PromoCashInController@update');
    Route::post('delete_cash_in_promo/{id}', 'PromoCashInController@delete');
    Route::post('status_cash_in_promo', 'PromoCashInController@status');

    //social link
    Route::get('social_link', 'SocialLinkController@index');
    Route::post('social_link_update', 'SocialLinkController@update');
    Route::get('social_link/qr_image_delete', 'SocialLinkController@qr_image_delete');


    // only new betslip
    Route::get('only/betslip/histroy/{code}',[OnlyBetSLipController::class,'history']);
    Route::get('only/betslip/details/{code}',[OnlyBetSLipController::class,'details']);
 

    //shameless games
    Route::resource('game_categories', 'ShamelessGameCategoryController');
    Route::resource('game_providers', 'ShamelessGameProviderController');
    Route::resource('games', 'GameManagementController');

    Route::post('game-category/status', [GameManagementController::class, 'statusChange'])->name('statusChange');
    Route::post('games/status', [GameManagementController::class, 'gameStatusChange'])->name('game.statusChange');
    Route::post('games/all/status', [GameManagementController::class, 'allGameStatusChange'])->name('game.allStatusChange');

    //betslip transaction
    Route::get('betslip/transaction', [BetslipTransactionController::class, 'index']);

    //Transition History
    Route::get('user/transition_history/{code}', [BetslipTransactionController::class, 'userTransition']);
    Route::get('user/transition_detail/{code}', [BetslipTransactionController::class, 'userTransitionDetail'])->name('user.transition.detail');

    // 2d only histoy/
    Route::get('user/1d_betslips_only/{id}', [TwoBetOnlyController::class, 'one_history'])->name('user.twoBetSlipsOnly_one');
    Route::get('user/2d_betslips_only/{id}', [TwoBetOnlyController::class, 'two_history'])->name('user.twoBetSlipsOnly');
    Route::get('user/crypto1d_betslips_only/{id}', [TwoBetOnlyController::class, 'crypto_one_history'])->name('user.cryptoOneBetSlipsOnly');
    Route::get('user/crypto2d_betslips_only/{id}', [TwoBetOnlyController::class, 'crypto_two_history'])->name('user.cryptotwoBetSlipsOnly');
    Route::get('user/3d_betslips_only/{id}', [TwoBetOnlyController::class, 'three_history'])->name('user.twoBetSlipsOnly_three');
    Route::get('user/all_report/{id}', [TwoBetOnlyController::class, 'allReport'])->name('user.allReport');

    //winner history
    Route::get('winner_history', 'WinnerHistoryController@index');
});
// Game
Route::group(['middleware' => ['super_admin'], 'namespace' => 'SuperAdmin', 'prefix' => 'game'], function () {
    // Super Admin & Admin
    Route::group(["middleware" => ['super_admin_or_admin_role']], function () {
        Route::get('/game-statics', [GameController::class, 'index']);
        Route::get('/game-setting', [GameController::class, 'gameSetting']);
    });
    // Super Admin & Admin & Staff

    Route::resource('payment_method_new', 'NewPaymentMethodController');
    Route::post('payment_method/update_cashin_status', 'PaymentMethodController@updateCashinStatus');
    Route::get('/payment-account-new-delete/{id}', [PaymentMethodController::class, 'paymentAccountDeleteNew'])->name('paymentaccount.new.delete');

    // icasion side banner
    Route::get('bannertwo', 'GameController@index');
    Route::get('bannertwo/create', 'GameController@create');
    Route::post('bannertwo/store', 'GameController@store');
    Route::get('bannertwo/{id}/edit', 'GameController@edit');
    Route::put('bannertwo/update/{id}', 'GameController@update');
    Route::delete('bannertwo/destroy/{id}', 'GameController@destroy');
    Route::resource('user', 'UserTwoController');

    // only new betslip
    Route::get('only/betslip/histroy/{code}',[OnlyBetSLipController::class,'history']);
    Route::get('only/betslip/details/{code}',[OnlyBetSLipController::class,'details']);
   
    Route::get('cash_in/{id}', 'CashInController@cash_in');
    Route::post('cash_in_create/{id}', 'CashInController@cash_in_create');
    Route::get('cash_in_history/{id}', 'CashInController@cash_in_history');

    Route::get('cash_out/{id}', 'CashOutController@cash_out');
    Route::post('cash_out_create/{id}', 'CashOutController@cash_out_create');
    Route::get('cash_out_history/{id}', 'CashOutController@cash_out_history');

    Route::get('user/1d_betslips_only/{id}', [TwoBetOnlyController::class, 'one_history'])->name('user.twoBetSlipsOnly_one');
    Route::get('user/2d_betslips_only/{id}', [TwoBetOnlyController::class, 'two_history'])->name('user.twoBetSlipsOnly');
    Route::get('user/crypto1d_betslips_only/{id}', [TwoBetOnlyController::class, 'crypto_one_history'])->name('user.cryptoOneBetSlipsOnly');
    Route::get('user/crypto2d_betslips_only/{id}', [TwoBetOnlyController::class, 'crypto_two_history'])->name('user.cryptotwoBetSlipsOnly');
    Route::get('user/3d_betslips_only/{id}', [TwoBetOnlyController::class, 'three_history'])->name('user.twoBetSlipsOnly_three');
    Route::get('user/all_report/{id}', [TwoBetOnlyController::class, 'allReport'])->name('user.allReport');
    Route::get('user/transition_history/{code}', [BetslipTransactionController::class, 'userTransition']);
    Route::resource('header_play_text_two', 'HeaderPLayTextTwoController');

    // icasion social link
    Route::get('social_link_two', 'SocialLinkController@index_two');
    Route::post('social_link_update_two', 'SocialLinkController@update_two');
    Route::get('social_link/qr_image_delete_two', 'SocialLinkController@qr_image_delete_two');

    // app setting
    Route::get('setting_two', [SettingController::class, 'index_two']);
    Route::put('app-update/{id}', [SettingController::class, 'appForceUpdate_two'])->name('app.update.two');

    Route::get('/icasino', [OthersideController::class, 'index']);
    Route::get('/game-lists', [GameController::class, 'games']);
    Route::get('/game-click', [GameController::class, 'game_click']);

    Route::get('/get_proncat', [GameController::class, 'getPronCat']);
    Route::post('/game-control/{id}', [GameController::class, 'displaycontrol']);
    Route::post('/provider-status', [GameController::class, 'providerStatus']);
    Route::post('/provider-overall', [GameController::class, 'providerOverall'])->name('provider.overall');
    Route::put('/update-provider-amount/{id}', [GameController::class, 'updateProviderAmount'])->name('update.provider.amount');
    Route::get('player_list', 'MemberInfoController@player_list');
    Route::get('transfer_logs/{user}', 'MemberInfoController@transfer_log');
    Route::get('player_detail', [MemberInfoController::class, 'playerDetail']);
    Route::get('active_member', 'MemberInfoController@active_member');
    Route::get('inactive_member', 'MemberInfoController@inactive_member');
    Route::get('transaction_reports', 'MemberInfoController@transactionRecord');
    Route::get('win_lose', 'GameReportController@win_lose');
    Route::get('win_lose_user/{user}/betslips', 'GameReportController@userGameBetSlip');
    Route::get('transaction', 'GameReportController@transaction_log');
    Route::get('transfer_in', 'GameTransferController@transfer_in');
    Route::get('transfer_out', 'GameTransferController@transfer_out');
    Route::get('create-game', [GameController::class, 'createGame']);
    Route::post('create-provider', [GameController::class, 'createProvider'])->name('create.provider');
    Route::post('game-add', [GameController::class, 'gameAdd'])->name('game.add');
    Route::get('claim_refer_game_history', [GameController::class, 'claim_refer_game_history']);
});
