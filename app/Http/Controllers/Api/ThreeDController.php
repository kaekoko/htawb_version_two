<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helper\HolidayHelper;
use App\Models\LotteryOffDay;
use App\Models\OverAllSetting;
use App\Helper\republicFunction;
use App\Http\Controllers\Controller;

class ThreeDController extends Controller
{
    public function three_d(Request $request)
    {
        $game = 'open';

        $three_ds = republicFunction::getNumberInfo3d(date('m'));
        $overAllSetting = OverAllSetting::first();

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'overall_amount' => $overAllSetting->over_all_amount_3d,
            'default_amount' => $overAllSetting->over_all_default_amount,
            'odd' => $overAllSetting->odd_3d,
            'tot' => $overAllSetting->tot_3d,
            'game' => $game,
            'data' => $three_ds
        ]);
    }
}
