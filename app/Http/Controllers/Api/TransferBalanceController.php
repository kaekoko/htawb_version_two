<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\TransferIn;
use App\Models\TransferOut;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\FirebaseController;

class TransferBalanceController extends Controller
{

    public function transfer_in(Request $request)
    {
        if(gameMaintainance() == 1){
            return response()->json([
                'message' => 'Game Sever Maintaince please wait 30 Minutes'
            ],400);
        }
        $validator = Validator::make($request->all(), [
            'transfer_balance' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'post data invalid'
            ],400);
        }

        $id = Auth::user()->id;
        $user = User::find($id);

        $get_balance = new FirebaseController();
        $user_balance = $get_balance->getValue($user->id);
        $provider = $user_balance['last_provider'];
        $referenceid = date("YmdHis").rand(111111,999999);

        if($user->turnover > 0){
            if($user_balance['last_balance'] > 500){
                return response()->json([
                    'message' => 'you transfer main wallet at turnover 0 and under game wallet 500'
                ],400);
            }
        }

        if($user->balance < $request->transfer_balance){
            return response()->json([
                'message' => 'Not enough amount'
            ],400);
        }

        $array = transferFund([
            "provider" => $provider,
            "username" => $user->user_code,
            "password" => "alexaung57",
            "referenceid" => $referenceid,
            "type" => "0",
            "amount" => $request->transfer_balance
        ]);
         if($array == 0){
            $user->balance = $user->balance - $request->transfer_balance;
            $user->turnover = 0;
            $user->update();
            $lastbalance = getbalance($provider, $user->user_code, 'alexaung57');
            $get_balance->updateProvider($provider, $user->id, $lastbalance['balance']);
            $tran_in = new TransferIn();
            $tran_in->user_id = $user->id;
            $tran_in->balance = $user->balance;
            $tran_in->game_balance = $lastbalance['balance'];
            $tran_in->transfer_balance = $request->transfer_balance;
            $tran_in->referenceid = $referenceid;
            $tran_in->message = 'success';
            $tran_in->error_code = $array;
            $tran_in->save();

         }else{
            return response()->json([
                'message' => $request->transfer_balance.' Maintanace  ',
            ]);

         }
       
        return response()->json([
            'message' => $request->transfer_balance.' successfully transfer to game wallet',
        ]);
    }

    public function transfer_out(Request $request)
    {
        if(gameMaintainance() == 1){
            return response()->json([
                'message' => 'Game Sever Maintaince please wait 30 Minutes'
            ],400);
        }
        
        $validator = Validator::make($request->all(), [
            'transfer_balance' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'post data invalid'
            ],400);
        }

        $id = Auth::user()->id;
        $user = User::find($id);

        if($user->turnover > 0){
            return response()->json([
                'message' => 'you transfer main wallet at turnover 0'
            ], 400);
        }

        $get_balance = new FirebaseController();
        $user_balance = $get_balance->getValue($user->id);
        $provider = $user_balance['last_provider'];
        $referenceid = date("YmdHis").rand(111111,999999);

        $total_amount = getbalance($provider, $user->user_code, 'alexaung57');

        if ((int)$request->transfer_balance <= (int)$total_amount['balance']) {
            $array = transferFund([
                "provider" => $provider,
                "username" => $user->user_code,
                "password" => "alexaung57",
                "referenceid" => $referenceid,
                "type" => "1",
                "amount" => $request->transfer_balance
            ]);
 
            if($array == 0){
                $user->balance = $user->balance + $request->transfer_balance;
                $user->update();
                $tran_out = new TransferOut();
                $tran_out->user_id = $user->id;
                $tran_out->referenceid = $referenceid;
                $tran_out->message = 'success';
                $tran_out->error_code = $array;
                $tran_out->game_balance = $total_amount['balance'];
                $tran_out->main_balance = $user->balance;
                $tran_out->transfer_balance = $request->transfer_balance;
                $tran_out->save();
                $update_provider = new FirebaseController();
                $update_provider->updateProvider($provider, $user->id, $total_amount['balance']);
            }else{
                return response()->json([
                    'message' => $request->transfer_balance.'Game Maintanace'
                ], 200);

            }
            
            return response()->json([
                'message' => $request->transfer_balance.' successfully transfer to main wallet'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Not enough amount'
            ], 400);
        }

    }

    public function transfer_in_his(Request $request)
    {
        $id = Auth::user()->id;
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now();
            $start = $start->format('Y-m-d');
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
            $end = $end->format('Y-m-d');
        }
        $data = TransferIn::orderBy('id', 'DESC')->where('user_id', $id)->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"])->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function transfer_out_his(Request $request)
    {
        $id = Auth::user()->id;
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now();
            $start = $start->format('Y-m-d');
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
            $end = $end->format('Y-m-d');
        }
        $data = TransferOut::orderBy('id', 'DESC')->where('user_id', $id)->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"])->get();
        return response()->json([
            'data' => $data
        ]);
    }
}