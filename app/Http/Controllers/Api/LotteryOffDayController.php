<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LotteryOffDay;
use Illuminate\Http\Request;

class LotteryOffDayController extends Controller
{
    public function index(){
        $lottery_off_days = LotteryOffDay::all();
        return response()->json([
            'result' => count($lottery_off_days),
            'status' => 200,
            'message' => 'success',
            'data' => $lottery_off_days
        ]);
    }
}
