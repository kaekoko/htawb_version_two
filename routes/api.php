<?php

use App\Models\User;
use App\Events\ClaimBox;
use App\Models\Category;
use App\Events\WinMessage;
use App\Events\NumberEvent;
use Illuminate\Http\Request;
use App\Models\OverAllSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CallBackController;
use App\Http\Controllers\Api\CashInController;
use App\Http\Controllers\Api\CashOutController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TransitionController;
use App\Http\Controllers\SuperAdmin\LiveController;
use App\Http\Controllers\Api\SeamlessGameController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\SuperAdmin\BetSlipController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\GameManagementController;
use App\Http\Controllers\SuperAdmin\PaymentStatementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/gamehistory/{usercode}/{start_date}/{end_date}', [GameController::class, 'gamehistory']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api',], function () {
    Route::get('section', 'SectionController@index');
    Route::get('section_1d', 'SectionController@index_1d');
    Route::get('section_crypton2d', 'SectionController@section_crypton2d');
    Route::get('section_crypton1d', 'SectionController@section_crypton1d');
    Route::get('section_3d', 'SectionController@section_3d');
    Route::get('web_section', 'SectionController@web');
    Route::get('hot_block', 'SectionController@hot_block');
    Route::get('hot_block_1d', 'SectionController@hot_block_1d');
    Route::get('hot_block_c1d', 'SectionController@hot_block_c1d');
    Route::get('hot_block_c2d', 'SectionController@hot_block_c2d');
    Route::get('hot_block_mobile', 'SectionController@hot_block_mobile');
    Route::get('blog', 'BlogController@index');
    Route::get('service', 'ServiceController@index');
    Route::post('two_d', 'TwoDController@index');
    Route::post('new_two_d', 'TwoDController@nTwoD');
    Route::post('new_one_d', 'OneDController@nTwoD');
    Route::post('crypto_two_d', 'TwoDController@cryptoTwoD');
    Route::post('crypto_one_d', 'TwoDController@Crypto1D');
    Route::get('three_d', 'ThreeDController@three_d');
    Route::get('betting_setting', 'BettingSetting@index');
    Route::get('lottery_off_day', 'LotteryOffDayController@index');
    Route::get('category', 'CategoryController@index');
    Route::get('city', 'CityController@index');
    Route::get('header_play_text', 'HeaderPlayTextController@index');
    Route::get('header_play_text_new', 'HeaderPlayTextController@index_new');
    Route::get('banner', 'BannerController@index');
    Route::get('banner_two', 'BannerController@index_two');
    Route::get('banner_two_mobile', 'BannerController@banner_mobile_two');
    Route::get('banner_mobile', 'BannerController@banner_mobile');
    Route::post('get_agent_phone/{id}', 'GetAgentPhoneController@index');
    Route::post('user/get_token', 'UserController@getToken');
    Route::get('luckynumber_daily', 'LuckyNumberController@luckynumber_daily');
    Route::get('luckynumber3d', 'LuckyNumberController@luckynumber3d');
    Route::get('luckynumbercrypton2d', 'LuckyNumberController@luckynumbercrypton2d');
    Route::get('luckynumbercrypton1d', 'LuckyNumberController@luckynumbercrypton1d');
    Route::get('luckynumber1d', 'LuckyNumberController@luckynumber1d');
    Route::get('social_link', 'SocialLinkController@index');
    Route::get('social_link_two', 'SocialLinkController@index_two');


    Route::post('user/login', 'UserController@login');
    Route::post('user/send_otp', 'UserController@send_otp');
    Route::post('user/verify_otp', 'UserController@verify_otp');
    Route::post('user/verify_register', 'UserController@verify_register');
    Route::post('user/verify_register/new', 'UserController@verify_register');
    Route::post('user/forget_password', 'UserController@forget_password');
    Route::post('user/check_phone', 'UserController@check_phone');

    //Luckynumber History
    Route::get('luckynumber_history/{type}', 'LuckyNumberController@luckyNumberHistory');
    //promo cashin
    Route::get('user/get_promo_cash_in', 'PromoCashInController@get');
    //Winner Users
    Route::get('winner_users/{type}', 'WinnerController@winnerUsers');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('get_game_balance/{id}', [GameController::class, 'getGameBalance']);
        Route::get('user/profile', 'UserController@profile');
        Route::post('user/profile_update', 'UserController@profile_update');
        Route::post('user/new_password', 'UserController@new_password');
        Route::get('user/cash_in_history', 'CashInController@cash_in_history');
        Route::get('user/cash_out_history', 'CashOutController@cash_out_history');
        Route::post('user/betting', 'BettingController@betting');
        Route::post('user/betting_1d', 'BettingController@betting_1d');
        Route::post('user/betting_c2d', 'BettingController@betting_c2d');
        Route::post('user/betting_c1d', 'BettingController@betting_c1d');
        Route::post('user/betting_3d', 'BettingController@betting_3d');
        Route::post('user/logout', 'UserController@logout');
        Route::get('user/bet_slip_history_1d', 'BetSlipController@bet_slip_history_1d');
        Route::get('user/bet_slip_history_2d', 'BetSlipController@bet_slip_history_2d');
        Route::get('user/bet_slip_history_c2d', 'BetSlipController@bet_slip_history_c2d');
        Route::get('user/bet_slip_history_c1d', 'BetSlipController@bet_slip_history_c1d');
        Route::get('user/bet_slip_history_3d', 'BetSlipController@bet_slip_history_3d');
        Route::get('user/refund_history', 'BetSlipController@refund_history');
        Route::post('user/save_device_token', 'NotiController@save_device_token');
        Route::post('user/lvl_2', 'UserController@lvl_2');
        Route::get('user/my_referral_his', 'UserController@my_referral_his');
        Route::get('user/check_game_balance', 'CashInController@check_game_balance');

        //for mobile
        Route::get('user/banner_mobile', 'BannerController@banner_mobile');
        Route::get('user/block', 'UserController@block');
        Route::get('user/win-message/{id}', 'UserController@win_message');
        Route::post('user/status/{bet_id}', 'UserController@noti_status');

        // Route::post('user/claimboxes/{id}', [UserController::class, 'claimApi']);
        // Route::post('user/claim/{id}', [UserController::class, 'claim_data']);

        //transfer balance
        Route::post('user/transfer_in', 'TransferBalanceController@transfer_in');
        Route::post('user/transfer_out', 'TransferBalanceController@transfer_out');

        Route::get('user/transfer_in_his', 'TransferBalanceController@transfer_in_his');
        Route::get('user/transfer_out_his', 'TransferBalanceController@transfer_out_his');

        //spin wheel
        Route::get('user/spin_wheel', 'SpinWheelController@index');
        Route::get('user/spin_wheel_luck', 'SpinWheelController@spin_wheel_luck');
        Route::get('user/free_spin_wheel', 'SpinWheelController@free_spin_wheel');
        Route::post('user/spin_wheel_luck_transfer', 'SpinWheelController@spin_wheel_luck_transfer');

        //Claim game referral
        Route::get('user/claim_game_refer_amt', 'UserController@claim_game_refer_amt');

        // CashIn
        Route::post('/cash-in', [CashInController::class, 'cashin']);
        // CashOut
        Route::post('/cash-out', [CashOutController::class, 'cashout']);

        //Game Report
        Route::get('/reports', [GameController::class, 'transactionreports']);
        Route::get('/report-details', [GameController::class, 'transactiondetail']);

        // for testing
        Route::post('user/betting_c2d_test', 'BettingController@betting_c2d_test');

        // betslips history
        Route::get('transition/history', [TransitionController::class, 'betslip']);

        // betslips details
        Route::get('transition/details', [TransitionController::class, 'details']);
    });

    //luckynumber filter
    Route::get('/dailylists', [LiveController::class, 'dailyList']);
    Route::post('/filter', [LiveController::class, 'filter_date']);
});




Route::get('/dailylists', [LiveController::class, 'dailyList']);

Route::post('/filter', [LiveController::class, 'filter_date']);

Route::get('/tingo/get_num', [LiveController::class, 'live']);

Route::get('/tt', [LiveController::class, 'testing']);

//update
Route::post('/time_statics', [DashboardController::class, 'time_statics']);

Route::post('/daily_statics', [DashboardController::class, 'daily_statics']);

// Route::get('/testoff', [DashboardController::class, 'testoff']);

Route::post('/daily_grant_numbers', [BetSlipController::class, 'daily_all_bets']);

Route::post('/single_detail', [BetSlipController::class, 'single_bet_detail']);

Route::post('/daily_detail', [BetSlipController::class, 'daily_bet_detail']);

Route::post('/sma_grant_numbers/{id}', [BetSlipController::class, 'sma_section_grant_number']);

Route::post('/sma_grant_numbers_daily/{id}', [BetSlipController::class, 'sma_daily_grant_number']);

Route::post('/sma_single_detail/{id}', [BetSlipController::class, 'sma_single_detail']);

Route::post('/sma_daily_detail/{id}', [BetSlipController::class, 'sma_daily_detail']);

Route::post('/sma_section_statics/{id}', [BetSlipController::class, 'sma_section_statics']);

Route::post('/sma_daily_statics/{id}', [BetSlipController::class, 'sma_daily_statics']);

Route::post('/record_lists/{req}', [DashboardController::class, 'recordlists']);

Route::post('/sma_record_lists/{req}', [PaymentStatementController::class, 'smaLists']);

//payment statement route
Route::post('/superadmin_payment_statement/{req}', [DashboardController::class, 'superadminPaymentStatement']);
Route::post('/sma_agent_payment_statement/{req}', [DashboardController::class, 'smaAgentPaymentStatement']);

// CashIn Amount
Route::get('/cashin-amount', [CashInController::class, 'cashInAmount']);

// CashOut Amount
Route::get('/cashout-amount', [CashOutController::class, 'cashOutAmount']);

Route::get('/mark_history', [UserController::class, 'mark_histories']);


// Game
Route::get('/game-categories', [GameController::class, 'gameCategories']);
Route::get('/game-providers/{p_type}', [GameController::class, 'gameProviders']);
Route::get('/getgame_by_pro', [GameController::class, 'getGameByPro']);
Route::get('/games/{name}', [GameController::class, 'game_ui']);
Route::get('/game_view/{id}', [GameController::class, 'game_view']);
Route::get('/sport_game/{id}', [GameController::class, 'sport_game']);
Route::get('/livecasino_game/{id}', [GameController::class, 'livecasino_game']);
Route::get('/search', [GameController::class, 'search']);
Route::get('/provider-minimum-amount', [GameController::class, 'providerMinimumAmount']);


Route::get('get_balance/{id}', [GameController::class, 'getBalance']);
//Route::get('get_game_balance/{id}', [GameController::class, 'getGameBalance']);

// PaymentMethod
Route::get('/payment-method/show-cashin', [PaymentMethodController::class, 'showCashIn']);
Route::get('/payment-method/show-cashout', [PaymentMethodController::class, 'showCashOut']);
Route::get('/payment-method-new/show-cashin', [PaymentMethodController::class, 'showCashInNew']);
//app-setting
Route::get('/app-setting', [SettingController::class, 'appSetting']);

Route::get('/app-setting-two', [SettingController::class, 'appSetting_two']);


//Seamless Games
Route::get('g/categories', [SeamlessGameController::class, 'categories']);
Route::get('g/providers/{id}', [SeamlessGameController::class, 'providers']);
Route::get('g', [SeamlessGameController::class, 'games']);

//Hot Providers
Route::get('provider/hot/{category}', [SeamlessGameController::class, 'HotProvider']);


// 2d3dc2d1dc2d
Route::get('lottery/list',function(){
    $data = Category::orderByRaw("FIELD(id, 4, 1, 5, 3, 2)")->get();
 
     return response()->json($data, 200);
 });

//GSC API CALLBACK ROUTE
Route::post('/Seamless/GetBalance', [CallBackController::class, 'GetBalance']);
Route::post('/Seamless/PlaceBet', [CallBackController::class, 'PlaceBet']);
Route::post('/Seamless/GameResult', [CallBackController::class, 'GameResult']);
Route::post('/Seamless/Rollback', [CallBackController::class, 'Rollback']);
Route::post('/Seamless/CancelBet', [CallBackController::class, 'CancelBet']);
Route::post('/Seamless/Bonus', [CallBackController::class, 'Bonus']);
Route::post('/Seamless/Jackpot', [CallBackController::class, 'Jackpot']);
Route::post('/Seamless/MobileLogin', [CallBackController::class, 'MobileLogin']);
Route::post('/Seamless/BuyIn', [CallBackController::class, 'BuyIn']);
Route::post('/Seamless/BuyOut', [CallBackController::class, 'BuyOut']);
Route::post('/Seamless/PushBet', [CallBackController::class, 'PushBet']);

Route::get('/getgamelaunch/{membername}/{productid}/{gametype}/{gameid}/{platform}', function ($membercode, $productid, $gametype, $gameid, $platform) {
    $overall_setting = OverAllSetting::first();
    $maintain = $overall_setting->game_maintenance;
    if ($maintain) {
        $response = [
            'Url' => 'https://splendorous-rolypoly-dd589c.netlify.app/',
            'ErrorCode' => 0,
            'ErrorMessage' => null,
        ];

        return response(json_encode($response));
    }
    return LaunchGame($membercode, $productid, $gametype, $gameid, $platform);
});


Route::get('/user_by_code/{id}',function($id){
    $user = User::select('balance','user_code')->where('user_code',$id)->first();
    return response()->json($user, 200);
});
Route::get('/allusers',function(){
    $user = User::all();
    return response()->json($user, 200);
});


Route::get('/user_by_id/{id}',function($id){
    $user = User::select('id','balance','user_code')->where('id',$id)->first();
    return response()->json($user, 200);
});
