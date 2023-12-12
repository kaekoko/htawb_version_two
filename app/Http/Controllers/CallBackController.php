<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ShamelessGame;
use App\Models\BetslipTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ShamelessGameProvider;
use App\Models\TempBestslipTransaction;

class CallBackController extends Controller
{
   public function GetBalance(Request $Request)
   {
      $reqdata = (object) $Request;
      $methodname = "getbalance";
      $opcode = "E457";
      $secretkey = "XDMkAl";
      $requesttime = $reqdata->RequestTime;
      $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
      $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->first();
      if ($user) {

         if ($sign == $reqdata->Sign) {
            $getbalance = [
               "ErrorCode" => 0,
               "ErrorMessage" => "Success",
               "Balance" => $user->balance,
               "BeforeBalance" => 0
            ];
            
            return response()->json($getbalance, 200);
         } else {
            $error = [
               "ErrorCode" => 1004,
               "ErrorMessage" => "API Invalid Sign",
            ];
            
            return response()->json($error, 200);
         }

      } else {
         $error = [
            "ErrorCode" => 1000,
            "ErrorMessage" => "API Member Not Exists",
         ];
         
         return response()->json($error, 200);

      }


   }

   public function PlaceBet(Request $Request)
   {
      $reqdata = (object) $Request;
      
      $unique = DB::connection('mysql2')->table($reqdata->MemberName)->where('transaction', $reqdata->Transactions[0]['TransactionID'])->count();
      if ($unique == 0 || $reqdata->Transactions[0]['TransactionID'] == null) {

         $methodname = "placebet";
         $opcode = "E457";
         $secretkey = "XDMkAl";
         $requesttime = $reqdata->RequestTime;
         $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
         $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->first();
         $beforebalance = $user->balance;
         if ($beforebalance < $reqdata->Transactions[0]['BetAmount']) {
            $error = [
               "ErrorCode" => 1001,
               "ErrorMessage" => "API Member Insufficient Balance",
            ];
            return response()->json($error, 200);

         } else {
            $pbalance = $user->balance + $reqdata->Transactions[0]['TransactionAmount'];
            $balance = (string) number_format((float) $pbalance, 2, '.', '');
            $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->update(['balance' => $pbalance]);


            if ($sign == $reqdata->Sign) {
                $provider = ShamelessGameProvider::where('p_code', $reqdata->Transactions[0]['ProductID'])->first();

               SaveTransaction($reqdata, $reqdata->MemberName, $balance,$beforebalance,$provider->p_code,$provider->name,$methodname);
               $getbalance = [
                  "ErrorCode" => 0,
                  "ErrorMessage" => "Success",
                  "Balance" => $balance,
                  "BeforeBalance" => $beforebalance
               ];
               return response()->json($getbalance, 200);
            } else {
               $error = [
                  "ErrorCode" => 1004,
                  "ErrorMessage" => "API Invalid Sign",
               ];
               return response()->json($error, 200);
            }

         }


      } else {
         $error = [
            "ErrorCode" => 1003,
            "ErrorMessage" => "API Duplicate Transaction",
         ];
         return response()->json($error, 200);
      }

   }

   //game result
   public function GameResult(Request $Request)
   {
      $reqdata = (object) $Request;
     
      $wagger = DB::connection('mysql2')->table($reqdata->MemberName)->where('wager_id', $reqdata->Transactions[0]['WagerID'])->count();
      if ($wagger == 0) {
         $error = [
            "ErrorCode" => 1006,
            "ErrorMessage" => "API Bet Not Exist",
         ];
         return response()->json($error, 200);
      }else{
      $unique = DB::connection('mysql2')->table($reqdata->MemberName)->where('transaction', $reqdata->Transactions[0]['TransactionID'])->count();
      if ($unique == 0 || $reqdata->Transactions[0]['TransactionID'] == null) {

         $methodname = "gameresult";
         $opcode = "E457";
         $secretkey = "XDMkAl";
         $requesttime = $reqdata->RequestTime;
         $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
         $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->first();
         if ($user) {
            $beforebalance = $user->balance;
            $pbalance = $user->balance + $reqdata->Transactions[0]['TransactionAmount'];
            $balance = (string) number_format((float) $pbalance, 2, '.', '');
            $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->update(['balance' => $pbalance]);

         } else {
            $error = [
               "ErrorCode" => 1000,
               "ErrorMessage" => "API Member Not Exists",
            ];
            return response()->json($error, 200);
         }
         if ($sign == $reqdata->Sign) {
            $provider = ShamelessGameProvider::where('p_code', $reqdata->Transactions[0]['ProductID'])->first();
               SaveTransaction($reqdata, $reqdata->MemberName, $balance, $beforebalance, $provider->p_code,$provider->name,$methodname);
            $getbalance = [
               "ErrorCode" => 0,
               "ErrorMessage" => "Success",
               "Balance" => $balance,
               "BeforeBalance" => $beforebalance
            ];
            return response()->json($getbalance, 200);
         } else {
            $error = [
               "ErrorCode" => 1004,
               "ErrorMessage" => "API Invalid Sign",
            ];
            return response()->json($error, 200);
         }


      } else {
         $error = [
            "ErrorCode" => 1003,
            "ErrorMessage" => "API Duplicate Transaction",
         ];
         return response()->json($error, 200);
      }
   }

   }

   //roll back
   public function Rollback(Request $Request)
   {
      $reqdata = (object) $Request;

      $unique = DB::connection('mysql2')->table($reqdata->MemberName)->where('transaction', $reqdata->Transactions[0]['TransactionID'])->count();
      if ($unique == 0) {

         $methodname = "rollback";
         $opcode = "E457";
         $secretkey = "XDMkAl";
         $requesttime = $reqdata->RequestTime;
         $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
         $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->first();
         if ($user) {
            $beforebalance = $user->balance;

            $pbalance = $user->balance + $reqdata->Transactions[0]['TransactionAmount'];

            $balance = (string) number_format((float) $pbalance, 2, '.', '');
            $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->update(['balance' => $pbalance]);


         } else {
            $error = [
               "ErrorCode" => 1000,
               "ErrorMessage" => "API Member Not Exists",
            ];
            return response()->json($error, 200);
         }



         if ($sign == $reqdata->Sign) {
            $provider = ShamelessGameProvider::where('p_code', $reqdata->Transactions[0]['ProductID'])->first();
            SaveTransaction($reqdata, $reqdata->MemberName, $balance, $beforebalance, $provider->p_code,$provider->name,$methodname);
            $getbalance = [
               "ErrorCode" => 0,
               "ErrorMessage" => "Success",
               "Balance" => $balance,
               "BeforeBalance" => $beforebalance
            ];
            return response()->json($getbalance, 200);
         } else {
            $error = [
               "ErrorCode" => 1004,
               "ErrorMessage" => "API Invalid Sign",
            ];
            return response()->json($error, 200);
         }


      } else {
         $error = [
            "ErrorCode" => 1003,
            "ErrorMessage" => "API Duplicate Transaction",
         ];
         return response()->json($error, 200);
      }

   }
   //CancelBet
   public function CancelBet(Request $Request)
   {
      $reqdata = (object) $Request;
      $unique = DB::connection('mysql2')->table($reqdata->MemberName)->where('transaction', $reqdata->Transactions[0]['TransactionID'])->count();
      if ($unique == 0) {

         $methodname = "cancelbet";
         $opcode = "E457";
         $secretkey = "XDMkAl";
         $requesttime = $reqdata->RequestTime;
         $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
         $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->first();
         if ($user) {
            $beforebalance = $user->balance;
            $pbalance = $user->balance + $reqdata->Transactions[0]['TransactionAmount'];
            $balance = (string) number_format((float) $pbalance, 2, '.', '');
            $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->update(['balance' => $pbalance]);


         } else {
            $error = [
               "ErrorCode" => 1000,
               "ErrorMessage" => "API Member Not Exists",
            ];
            return response()->json($error, 200);
         }



         if ($sign == $reqdata->Sign) {
            $provider = ShamelessGameProvider::where('p_code', $reqdata->Transactions[0]['ProductID'])->first();
            SaveTransaction($reqdata, $reqdata->MemberName, $balance, $beforebalance, $provider->p_code,$provider->name,$methodname);
            $getbalance = [
               "ErrorCode" => 0,
               "ErrorMessage" => "Success",
               "Balance" => $balance,
               "BeforeBalance" => $beforebalance
            ];
            return response()->json($getbalance, 200);
         } else {
            $error = [
               "ErrorCode" => 1004,
               "ErrorMessage" => "API Invalid Sign",
            ];
            return response()->json($error, 200);
         }


      } else {
         $error = [
            "ErrorCode" => 1003,
            "ErrorMessage" => "API Duplicate Transaction",
         ];
         return response()->json($error, 200);
      }

   }


   //Bonus
   public function Bonus(Request $Request)
   {
      $reqdata = (object) $Request;
     
      $unique = DB::connection('mysql2')->table($reqdata->MemberName)->where('transaction', $reqdata->Transactions[0]['TransactionID'])->count();
      if ($unique == 0) {

         $methodname = "bonus";
         $opcode = "E457";
         $secretkey = "XDMkAl";
         $requesttime = $reqdata->RequestTime;
         $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
         $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->first();
         if ($user) {
            $beforebalance = $user->balance;
            $pbalance = $user->balance + $reqdata->Transactions[0]['TransactionAmount'];
            $balance = (string) number_format((float) $pbalance, 2, '.', '');
            $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->update(['balance' => $pbalance]);

         } else {
            $error = [
               "ErrorCode" => 1000,
               "ErrorMessage" => "API Member Not Exists",
            ];
            return response()->json($error, 200);
         }



         if ($sign == $reqdata->Sign) {
            $provider = ShamelessGameProvider::where('p_code', $reqdata->Transactions[0]['ProductID'])->first();
            SaveTransaction($reqdata, $reqdata->MemberName, $balance, $beforebalance, $provider->p_code,$provider->name,$methodname);
            $getbalance = [
               "ErrorCode" => 0,
               "ErrorMessage" => "Success",
               "Balance" => $balance,
               "BeforeBalance" => $beforebalance
            ];
            return response()->json($getbalance, 200);
         } else {
            $error = [
               "ErrorCode" => 1004,
               "ErrorMessage" => "API Invalid Sign",
            ];
            return response()->json($error, 200);
         }


      } else {
         $error = [
            "ErrorCode" => 1003,
            "ErrorMessage" => "API Duplicate Transaction",
         ];
         return response()->json($error, 200);
      }

   }

   //Jackpot
   public function Jackpot(Request $Request)
   {
      $reqdata = (object) $Request;
   
      $unique = DB::connection('mysql2')->table($reqdata->MemberName)->where('transaction', $reqdata->Transactions[0]['TransactionID'])->count();
      if ($unique == 0) {

         $methodname = "jackpot";
         $opcode = "E457";
         $secretkey = "XDMkAl";
         $requesttime = $reqdata->RequestTime;
         $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
         $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->first();
         if ($user) {
            $beforebalance = $user->balance;

            $pbalance = $user->balance + $reqdata->Transactions[0]['TransactionAmount'];


            $balance = (string) number_format((float) $pbalance, 2, '.', '');
            $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->update(['balance' => $pbalance]);


         } else {
            $error = [
               "ErrorCode" => 1000,
               "ErrorMessage" => "API Member Not Exists",
            ];
            return response()->json($error, 200);
         }



         if ($sign == $reqdata->Sign) {
            $provider = ShamelessGameProvider::where('p_code', $reqdata->Transactions[0]['ProductID'])->first();
            SaveTransaction($reqdata, $reqdata->MemberName, $balance, $beforebalance, $provider->p_code,$provider->name,$methodname);
            $getbalance = [
               "ErrorCode" => 0,
               "ErrorMessage" => "Success",
               "Balance" => $balance,
               "BeforeBalance" => $beforebalance
            ];
            return response()->json($getbalance, 200);
         } else {
            $error = [
               "ErrorCode" => 1004,
               "ErrorMessage" => "API Invalid Sign",
            ];
            return response()->json($error, 200);
         }


      } else {
         $error = [
            "ErrorCode" => 1003,
            "ErrorMessage" => "API Duplicate Transaction",
         ];
         return response()->json($error, 200);
      }

   }

   //mobile login
   public function MobileLogin(Request $Request)
   {
      $reqdata = (object) $Request;
      $methodname = "mobilelogin";
      $opcode = "E457";
      $secretkey = "XDMkAl";
      $requesttime = $reqdata->RequestTime;
      $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
      if ($sign == $reqdata->Sign) {
          if ('myvip2023'== $reqdata->Password) {
         $user = User::where('user_code', $reqdata->MemberName)->first();
         $getbalance = [
            "ErrorCode" => 0,
            "ErrorMessage" => "Success",
            "Balance" => $user->balance,
            "BeforeBalance" => '0'
         ];
         return response()->json($getbalance, 200);
        }else{
            $error = [
               "ErrorCode" => 16,
               "ErrorMessage" => "Failed",
            ];
            return response()->json($error, 200);

        }

      } else {
         $error = [
            "ErrorCode" => 1004,
            "ErrorMessage" => "API Invalid Sign",
         ];
         return response()->json($error, 200);
      }



   }


   //buyin
   public function BuyIn(Request $Request)
   {
      $reqdata = (object) $Request;
   
   
      $unique = DB::connection('mysql2')->table($reqdata->MemberName)->where('transaction', $reqdata['Transaction']['TransactionID'])->count();

      if ($unique == 0) {

         $methodname = "buyin";
         $opcode = "E457";
         $secretkey = "XDMkAl";
         $requesttime = $reqdata['RequestTime'];
         $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
         $user = User::select('balance','user_code')->where('user_code', $reqdata['MemberName'])->first();

         if ($user) {
            $beforebalance = $user->balance;

            $pbalance = $user->balance + $reqdata['Transaction']['TransactionAmount'];
              if($pbalance < 0){
               $error = [
                  "ErrorCode" => 1001,
                  "ErrorMessage" => "API Member Insufficient Balance",
               ];
               return response()->json($error, 200);

              }

            $balance = (string) number_format((float) $pbalance, 2, '.', '');
            $user = User::select('balance','user_code')->where('user_code', $reqdata['MemberName'])->update(['balance' => $pbalance]);


         } else {
            $error = [
               "ErrorCode" => 1000,
               "ErrorMessage" => "API Member Not Exists",
            ];
            return response()->json($error, 200);
         }



         if ($sign == $reqdata->Sign) {

            $provider = ShamelessGameProvider::where('p_code', $reqdata['Transaction']['ProductID'])->first();
            SaveTransactionTwo($reqdata, $reqdata->MemberName, $balance, $beforebalance, $provider->p_code,$provider->name,$methodname);
            $getbalance = [
               "ErrorCode" => 0,
               "ErrorMessage" => "Success",
               "Balance" => $balance,
               "BeforeBalance" => $beforebalance
            ];
            return response()->json($getbalance, 200);
         } else {
            $error = [
               "ErrorCode" => 1004,
               "ErrorMessage" => "API Invalid Sign",
            ];
            return response()->json($error, 200);
         }


      } else {
         $error = [
            "ErrorCode" => 1003,
            "ErrorMessage" => "API Duplicate Transaction",
         ];
         return response()->json($error, 200);
      }
   }
   //buyout
   public function BuyOut(Request $Request)
   {
      $reqdata = (object) $Request;
   
      
      $unique = DB::connection('mysql2')->table($reqdata->MemberName)->where('transaction', $reqdata['Transaction']['TransactionID'])->count();

      if ($unique == 0) {

         $methodname = "buyout";
         $opcode = "E457";
         $secretkey = "XDMkAl";
         $requesttime = $reqdata['RequestTime'];
         $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
         $user = User::select('balance','user_code')->where('user_code', $reqdata['MemberName'])->first();

         if ($user) {
            $beforebalance = $user->balance;

            $pbalance = $user->balance + $reqdata['Transaction']['TransactionAmount'];


            $balance = (string) number_format((float) $pbalance, 2, '.', '');
            $user = User::select('balance','user_code')->where('user_code', $reqdata['MemberName'])->update(['balance' => $pbalance]);


         } else {
            $error = [
               "ErrorCode" => 1000,
               "ErrorMessage" => "API Member Not Exists",
            ];
            return response()->json($error, 200);
         }



         if ($sign == $reqdata->Sign) {
            $provider = ShamelessGameProvider::where('p_code', $reqdata['Transaction']['ProductID'])->first();
            SaveTransactionTwo($reqdata, $reqdata->MemberName, $balance, $beforebalance, $provider->p_code,$provider->name,$methodname);
            $getbalance = [
               "ErrorCode" => 0,
               "ErrorMessage" => "Success",
               "Balance" => $balance,
               "BeforeBalance" => $beforebalance
            ];
            return response()->json($getbalance, 200);
         } else {
            $error = [
               "ErrorCode" => 1004,
               "ErrorMessage" => "API Invalid Sign",
            ];
            return response()->json($error, 200);
         }


      } else {
         $error = [
            "ErrorCode" => 1003,
            "ErrorMessage" => "API Duplicate Transaction",
         ];
         return response()->json($error, 200);
      }
   }


   //PushBet
   public function PushBet(Request $Request)
   {
      $reqdata = (object) $Request;
  
      $unique = DB::connection('mysql2')->table($reqdata->MemberName)->where('transaction', $reqdata->Transactions[0]['TransactionID'])->count();

      if ($unique == 0 || $reqdata->Transactions[0]['TransactionID'] == null ) {

         $methodname = "pushbet";
         $opcode = "E457";
         $secretkey = "XDMkAl";
         $requesttime = $reqdata['RequestTime'];
         $sign = MD5($opcode . $requesttime . $methodname . $secretkey);
         $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->first();

         if ($user) {
            $beforebalance = $user->balance;
            $pbalance = $user->balance + $reqdata->Transactions[0]['TransactionAmount'];
            $balance = (string) number_format((float) $pbalance, 2, '.', '');
            $user = User::select('balance','user_code')->where('user_code', $reqdata->MemberName)->update(['balance' => $pbalance]);
         } else {
            $error = [
               "ErrorCode" => 1000,
               "ErrorMessage" => "API Member Not Exists",
            ];
            return response()->json($error, 200);
         }

         if ($sign == $reqdata->Sign) {
            $provider = ShamelessGameProvider::where('p_code', $reqdata->Transactions[0]['ProductID'])->first();
            if('1020' == $reqdata->Transactions[0]['ProductID']){
                 $wagercount = DB::connection('mysql2')->table($reqdata->MemberName)->where('wager_id', $reqdata->Transactions[0]['WagerID'])->count();
                 if($wagercount == 0){
                   SaveTransaction($reqdata, $reqdata->MemberName, $balance, $beforebalance, $provider->p_code,$provider->name,$methodname);
                 }
            }else{
                SaveTransaction($reqdata, $reqdata->MemberName, $balance, $beforebalance, $provider->p_code,$provider->name,$methodname);
            }

            $getbalance = [
               "ErrorCode" => 0,
               "ErrorMessage" => "Success",
               "Balance" => $balance,
               "BeforeBalance" => $beforebalance
            ];
            return response()->json($getbalance, 200);
         } else {
            $error = [
               "ErrorCode" => 1004,
               "ErrorMessage" => "API Invalid Sign",
            ];
            return response()->json($error, 200);
         }


      } else {
         $error = [
            "ErrorCode" => 1003,
            "ErrorMessage" => "API Duplicate Transaction",
         ];
         return response()->json($error, 200);
      }
   }
}