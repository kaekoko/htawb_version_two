<?php

namespace App\Http\Controllers\Api;

use App\Models\AppUpdate;
use App\Models\AppUpdatTwo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function appSetting()
    {
        $app_setting = AppUpdate::first();
        return response()->json([
            'data' => $app_setting
        ]);
    }

    
    public function appSetting_two()
    {
        $app_setting = AppUpdatTwo::first();
        return response()->json([
            'data' => $app_setting
        ]);
    }
}
