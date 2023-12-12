<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\OverAllSetting;
use App\Http\Controllers\Controller;

class BettingSetting extends Controller
{
    public function index(){
        $over_all_settings = OverAllSetting::all();
        return response()->json([
            'result' => count($over_all_settings),
            'status' => 200,
            'message' => 'success',
            'data' => $over_all_settings
        ]);
    }
}
