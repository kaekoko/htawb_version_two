<?php

use Pusher\Pusher;
use App\Models\User;
use App\Models\ShamelessGame;
use App\Models\OverAllSetting;
use App\Models\GameTransferLog;
use App\Models\BetslipTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ShamelessGameCategory;
use App\Models\ShamelessGameProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\TempBestslipTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

function gameMaintainance(){
    $setting=OverAllSetting::first();
    return $setting->game_maintenance;
}

function create_table($user_code){
    Schema::connection('mysql2')->create($user_code, function($table)
    {
        $table->bigIncrements('id');
        $table->string('member')->nullable();
        $table->string('operator')->nullable();
        $table->string('member_code')->nullable();
        $table->string('product')->nullable();
        $table->string('provider_code')->nullable();
        $table->string('provider_name')->nullable();
        $table->string('method_name')->nullable();
        $table->string('provider')->nullable();
        $table->string('provider_line')->nullable();
        $table->integer('currency')->nullable();
        $table->integer('game_type')->nullable();
        $table->bigInteger('wager_id')->nullable();
        $table->decimal('Fee', 15, 2)->nullable();
        $table->string('game')->nullable();
        $table->string('game_round')->nullable();
        $table->decimal('valid_bet_amount', 15, 2)->nullable();
        $table->decimal('bet_amount', 15, 2)->nullable();
        $table->decimal('transaction_amount', 15, 2)->nullable();
        $table->string('transaction')->nullable();
        $table->decimal('payout_amount', 15, 2)->nullable();
        $table->longText('payout_detail')->nullable();
        $table->longText('bet_detail')->nullable();
        $table->decimal('commision_amount', 15, 2)->nullable();
        $table->decimal('jackpot_amount', 15, 2)->nullable();
        $table->date('settlement_date')->nullable();
        $table->decimal('jp_bet', 15, 2)->nullable();
        $table->decimal('balance', 15, 2)->nullable();
        $table->decimal('before_balance', 15, 2)->nullable();
        $table->integer('status')->nullable();
        $table->datetime('created_on')->nullable();
        $table->datetime('modified_on')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

function GetGameList($productid, $gametype, $platform)
{

    if($productid !== 1022){
        $opcode = "E550";
    $requesttime = date("Ymdhis");
    $methodname = "getgamelist";
    $secretkey = "S82pvr";
    $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
    $data = [
        "OperatorCode" => $opcode,
        "ProductID" => $productid,
        "GameType" => $gametype,
        "LanguageCode" => 1,
        "Platform" => $platform,
        "Sign" => $sign,
        "RequestTime" => $requesttime
    ];

    $response = Http::withHeaders(['Content-Type' => 'application/json',])
        ->post('https://swmd.6633663.com/Seamless/getgamelist', $data);
        logger($response);
    return $response;

    }else{

        $opcode = "E550";
        $requesttime = date("Ymdhis");
        $methodname = "getgamelist";
        $secretkey = "S82pvr";
        $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
        $data = [
            "OperatorCode" => $opcode,
            "ProductID" => 1005,
            "GameType" => $gametype,
            "LanguageCode" => 1,
            "Platform" => $platform,
            "Sign" => $sign,
            "RequestTime" => $requesttime
        ];

        $response = Http::withHeaders(['Content-Type' => 'application/json',])
        ->post('https://swmd.6633663.com/Seamless/getgamelist', $data);

        return $response;
    }

}

//get game launch
function LaunchGame($membercode, $productid, $gametype, $gameid, $platform)
{
    if($gameid == '0'){
        $gameid = '';
    }
    $opcode = "E550";
    $password = "htawb2023";
    $requesttime = date("Ymdhis");
    $methodname = "launchgame";
    $secretkey = "S82pvr";
    $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
    $data = [
        "OperatorCode" => $opcode,
        "MemberName" => $membercode,
        "Password" => $password,
        "ProductID" => $productid,
        "GameType" => $gametype,
        "GameID" => $gameid,
        "LanguageCode" => 1,
        "Platform" => $platform,
        "Sign" => $sign,
        "RequestTime" => $requesttime
    ];
    $response = Http::withHeaders(['Content-Type' => 'application/json',])
        ->post('https://swmd.6633663.com/Seamless/LaunchGame', $data);
    return $response;


}
function SaveTransaction($reqdata, $member_code, $balance, $before_balance, $provider_code, $provider_name, $methodname)
{
    DB::connection('mysql2')->table($member_code)->insert([
        'member' => $reqdata->Transactions[0]['MemberID'],
        'operator' => $reqdata->Transactions[0]['OperatorID'],
        'product' => $reqdata->Transactions[0]['ProductID'],
        'provider' => $reqdata->Transactions[0]['ProviderID'],
        'provider_line' => $reqdata->Transactions[0]['ProviderLineID'],
        'provider_code' => $provider_code,
        'provider_name' => $provider_name,
        'method_name' => $methodname,
        'wager_id' => $reqdata->Transactions[0]['WagerID'],
        'currency' => $reqdata->Transactions[0]['CurrencyID'],
        'game_type' => $reqdata->Transactions[0]['GameType'],
        'game' => $reqdata->Transactions[0]['GameID'],
        'game_round' => $reqdata->Transactions[0]['GameRoundID'],
        'valid_bet_amount' => $reqdata->Transactions[0]['ValidBetAmount'],
        'bet_amount' => $methodname === "gameresult" ? 0 : $reqdata->Transactions[0]['BetAmount'],
        'Fee' => '0',
        'transaction_amount' => $reqdata->Transactions[0]['TransactionAmount'],
        'transaction' => $reqdata->Transactions[0]['TransactionID'],
        'payout_amount' => $reqdata->Transactions[0]['PayoutAmount'],
        'payout_detail' => $reqdata->Transactions[0]['PayoutDetail'],
        'bet_detail' => $reqdata->Transactions[0]['MemberID'],
        'commision_amount' => $reqdata->Transactions[0]['CommissionAmount'],
        'jackpot_amount' => $reqdata->Transactions[0]['JackpotAmount'],
        'settlement_date' => $reqdata->Transactions[0]['SettlementDate'],
        'jp_bet' => $reqdata->Transactions[0]['JPBet'],
        'status' => $reqdata->Transactions[0]['Status'],
        'created_on' => $reqdata->Transactions[0]['CreatedOn'],
        'modified_on' => $reqdata->Transactions[0]['ModifiedOn'],
        'member_code' => $member_code,
        'balance' => $balance,
        'before_balance' => $before_balance,
        'created_at' => Carbon::now(),
    ]);

    
}

function SaveTransactionTwo($reqdata, $member_code, $balance, $before_balance, $provider_code, $provider_name, $methodname)
{

    DB::connection('mysql2')->table($member_code)->insert([
        'member' => $reqdata['Transaction']['MemberID'],
        'operator' => $reqdata['Transaction']['OperatorID'],
        'product' => $reqdata['Transaction']['ProductID'],
        'provider' => $reqdata['Transaction']['ProviderID'],
        'provider_line' => $reqdata['Transaction']['ProviderLineID'],
        'provider_code' => $provider_code,
        'provider_name' => $provider_name,
        'method_name' => $methodname,
        'wager_id' => $reqdata['Transaction']['WagerID'],
        'currency' => $reqdata['Transaction']['CurrencyID'],
        'game_type' => $reqdata['Transaction']['GameType'],
        'game' => $reqdata['Transaction']['GameID'],
        'game_round' => $reqdata['Transaction']['GameRoundID'],
        'valid_bet_amount' => $reqdata['Transaction']['ValidBetAmount'],
        'bet_amount' => $methodname === "gameresult" ? 0 : $reqdata['Transaction']['BetAmount'],
        'Fee' => '0',
        'transaction_amount' => $reqdata['Transaction']['TransactionAmount'],
        'transaction' => $reqdata['Transaction']['TransactionID'],
        'payout_amount' => $reqdata['Transaction']['PayoutAmount'],
        'payout_detail' => $reqdata['Transaction']['PayoutDetail'],
        'bet_detail' => $reqdata['Transaction']['MemberID'],
        'commision_amount' => $reqdata['Transaction']['CommissionAmount'],
        'jackpot_amount' => $reqdata['Transaction']['JackpotAmount'],
        'settlement_date' => $reqdata['Transaction']['SettlementDate'],
        'jp_bet' => $reqdata['Transaction']['JPBet'],
        'status' => $reqdata['Transaction']['Status'],
        'created_on' => $reqdata['Transaction']['CreatedOn'],
        'modified_on' => $reqdata['Transaction']['ModifiedOn'],
        'member_code' => $member_code,
        'balance' => $balance,
        'before_balance' => $before_balance,
        'created_at' => Carbon::now(),
    ]);

}

function pusherNoti($message){
    
    
    // Initialize Pusher
    $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), [
        'cluster' => 'ap1',
        'encrypted' => true, // You can set this to true if you are using HTTPS
    ]);

    // Send the message to a specific channel (e.g., "chat")
    $pusher->trigger('chat', 'message', ['text' => $message]);

    return response()->json(['status' => 'Message sent']);
}

function sendNotification($title, $body, $token)
{
    $firebaseToken = User::where('id',$token)->first();
    $firebaseToken = $firebaseToken->device_token;
        if ($firebaseToken !== null) {
            $SERVER_API_KEY = 'AAAAY9kKSiQ:APA91bFLTAiseWMlnFx4Zyuyp0WTjthUQsXq54v4sfdM8nUpPh2i2Q0Cz7BK2c_zugvoCvzZFkRxxXvV_RM08yVluZtxQefa4n7KbvKVoDDsYPrwOosrmHmlkAgpFS1hd05qkUumPr87';

            $data = [
                "to" => $firebaseToken,
                "notification" => [
                    "title" => 'MYVIP',
                    "body" =>  $body,
                ],
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
        }
}
function GameName($provider_code, $game_code){
 $game=ShamelessGame::where('p_code', $provider_code)->where('g_code', $game_code)->first();

 if($game == null){
    return '-';
 }
 return $game->name;
}