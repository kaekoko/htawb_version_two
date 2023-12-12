<?php

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\User;
use App\Helper\helper;
use App\Models\CashIn;
use App\Events\MyEvent;
use App\Models\Promotion;
use App\Helper\checkPhone;
use App\Invoker\invokeAll;
use App\Models\TransferIn;
use App\Models\LuckyNumber;
use App\Models\PromoReport;
use App\Models\BettingCrypto1d;
use App\Models\BettingCrypto2d;
use App\Models\GameTransferLog;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use Illuminate\Support\Facades\DB;
use CasinoGames\Facade\CasinoGames;
use Illuminate\Support\Facades\Route;
use App\Notifications\NewNotification;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\CreateTableController;
use App\Notifications\SendTelegramNotification;
use App\Http\Controllers\SuperAdmin\MemberInfoController;
use App\Http\Controllers\SuperAdmin\LuckyNumberController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','HomeController@index');
//Logout
Route::get('logout','HomeController@logout');

//Login
Route::get('login','HomeController@login')->middleware('login_check');
Route::post('check','HomeController@check');

require __DIR__ . '/super_admin.php';
require __DIR__ . '/user.php';
//external report
Route::get('/gamereport/player_detail', [MemberInfoController::class, 'playerDetailExternal']);
Route::get('transaction_reports_external', [MemberInfoController::class,'transactionRecordExternal']);

//testing
Route::get('get-balance', function () {
    $get_balance = getbalance('L4', '2c2814fe5a', 'alexaung57');
    return $get_balance;
});
Route::get('get-onlineusers', function () {
$threeDaysAgo = Carbon::now()->subDays(1)->format('Y-m-d');
$usersid = TransferIn::whereDate('created_at', '>=', $threeDaysAgo)->distinct()->pluck('user_id');
return $usersid;
});
Route::get('get-gametransferusers', function () {
$threeDaysAgo = Carbon::now()->subDays(1)->format('Y-m-d');
$usersid = GameTransferLog::whereDate('created_at', '>=', $threeDaysAgo)->distinct()->pluck('user_id');
return $usersid;
});
Route::get('get-gamelists', function () {
  return "fine";

});
Route::get('get-signature', function () {
    $user = User::where('id', 57)->firstOrFail();
    $data = [
        'username' => $user->user_code,
        'password' => $user->password
    ];

    CasinoGames::connectDOC('createMember', $data);
  
    return "ok";
});

Route::get('/getgamelaunch/{membername}/{productid}/{gametype}/{gameid}/{platform}', function ($membercode, $productid, $gametype, $gameid, $platform) {
    return LaunchGame($membercode, $productid, $gametype, $gameid, $platform);
});
Route::get('getgamelist/{provider}/{type}', function ($provider, $type) {
    return GetGameList($provider, $type, 0);
});


Route::get('message',function(){
    pusherNoti('sit lalsd');
    return 'ok';
});
Route::get('gamename',function(){
    pusherNoti('sit lalsd');
    return 'ok';
});

Route::get('delete_re',function(){
   User::where('phone','0921347123')->update(['side' => 1]);
   return 'success';
});

Route::get('eeee',function() {
    Promotion::truncate();
    PromoReport::truncate();
});


Route::get('approveC1d',function(){
    $lucky_number = LuckyNumber::where('create_date','2023-09-27')->where('category_id','5')->where('lucky_number','5')->first();


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

        return 'success';
});


Route::get('app_c2d',function(){
    $lucky_number = LuckyNumber::where('create_date','2023-09-27')->where('category_id','3')->where('lucky_number','95')->first();


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
        return 'success';
});


Route::get('clear_1d',function(){
    $change_read = LuckyNumber::where('section', '02:00 PM')->where('category_id', 5)->where('create_date','2023-09-27')->first();
            if (isset($change_read)) {
                $change_read->read = 1;
                $change_read->save();
            }
            $transaction = new BetslipTransaction();
            $total_status = UserBetCrypto1d::where('date', '2023-09-27')->where('section', '10:00 AM')->where('win', 1)->get();
            foreach ($total_status as $status) {
                $status->reward = 1;
                $status->claim = 1;
                $status->save();

                $user = User::where('id', $status->user_id)->first();
                $user->balance += $status->reward_amount;
                $user->save();
            }

            return 'sucess';
});

Route::get('create/{user_code}',function($user_code){
    create_table($user_code);
});
Route::get('getsavetable/{user_code}',function($user_code){
    $userslip = DB::connection('mysql2')->table($user_code)->get();
    return $userslip;
});
Route::get('savetable',function(){
    $arr = [
        [
            'member' => 'kaekoko',
            'operator' => 'kkk',
        ],
     
     
    ];
    $transaction = DB::connection('mysql2')->table('kaekoko')->insert($arr);
  return $transaction;
   
});


Route::get('oknarsar',function(){
    $firebasecontroller = new FirebaseController;
    $firebasecontroller->index('ok is the bet');

    return 'success';
});
