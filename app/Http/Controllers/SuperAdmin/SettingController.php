<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Setting;
use App\Models\AppUpdate;
use App\Models\AppUpdatTwo;
use App\Models\WaveOpenOff;
use Illuminate\Http\Request;
use App\Models\OverAllSetting;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $cash_in_amount = Setting::where('key', 'cash_in_amount')->first();
        $cash_in_mini = Setting::where('key', 'cash_in_minimum_amount')->first();
        $cash_out_mini = Setting::where('key', 'cash_out_minimum_amount')->first();
        $cash_out_amount = Setting::where('key', 'cash_out_amount')->first();
        $welcome_bonus = Setting::where('key', 'welcome_bonus')->first();
        $app_update = AppUpdate::where('id', 1)->first();
        $over_all_setting = OverAllSetting::first();

        $wave_setting = WaveOpenOff::first();
        return view('super_admin.setting.index', compact('cash_in_amount', 'cash_out_amount', 'cash_in_mini', 'cash_out_mini', 'app_update', 'over_all_setting','welcome_bonus','wave_setting'));
    }

    public function index_two()
    {
        $app_update = AppUpdatTwo::where('id', 1)->first();
        return view('super_admin.game.setting', compact('app_update'));
    }

    public function cashInMinimumAmount(Request $request, $key)
    {
        $cash_in_mini = Setting::where('key', $key)->first();
        $cash_in_mini->value = $request->cash_in_mini_amount;
        $cash_in_mini->save();

        return redirect()->back()->with('flash_message', 'Saved');
    }

    public function cashOutMinimumAmount(Request $request, $key)
    {
        $cash_out_mini = Setting::where('key', $key)->first();
        $cash_out_mini->value = $request->cash_out_mini_amount;
        $cash_out_mini->save();

        return redirect()->back()->with('flash_message', 'Saved');
    }

    public function cashInAmount(Request $request, $key)
    {
        $cash_in_amount = Setting::where('key', $key)->first();
        $cash_in_amount->value = $request->cash_in_amount;
        $cash_in_amount->save();

        return redirect()->back()->with('flash_message', 'Saved');
    }

    public function cashOutAmount(Request $request, $key)
    {
        $cash_out_amount = Setting::where('key', $key)->first();
        $cash_out_amount->value = $request->cash_out_amount;
        $cash_out_amount->save();

        return redirect()->back()->with('flash_message', 'Saved');
    }
    public function welcomeBonus(Request $request, $key)
    {
        $welcome_bonus = Setting::where('key', $key)->first();
        $welcome_bonus->value = $request->welcome_bonus;
        $welcome_bonus->save();

        return redirect()->back()->with('flash_message', 'Saved');
    }

    public function appForceUpdate(Request $request, $id)
    {
        $app_update = AppUpdate::findOrFail($id);
        $app_update->version_code = $request->version_code;
        $app_update->version_name = $request->version_name;
        $app_update->playstore = $request->playstore;
        $app_update->description = $request->description;
        $app_update->wallet_hide_version = $request->wallet_hide_version;
        $app_update->force_update = $request->force_update ? $request->force_update : 0;
        $app_update->show_wallet = $request->show_wallet ? $request->show_wallet : 0;
        $app_update->save();

        return redirect()->back()->with('flash_message', 'Saved');
    }

    public function appForceUpdate_two(Request $request, $id)
    {
        $app_update = AppUpdatTwo::findOrFail($id);
        $app_update->version_code = $request->version_code;
        $app_update->version_name = $request->version_name;
        $app_update->playstore = $request->playstore;
        $app_update->description = $request->description;
        $app_update->wallet_hide_version = $request->wallet_hide_version;
        $app_update->force_update = $request->force_update ? $request->force_update : 0;
        $app_update->show_wallet = $request->show_wallet ? $request->show_wallet : 0;
        $app_update->save();

        return redirect()->back()->with('flash_message', 'Saved');
    }

    public function toggleGameMainTenance(Request $request)
    {
        $overall_setting = OverAllSetting::first();
        $overall_setting->game_maintenance = $request->is_open;
        $res = $overall_setting->save();
        if ($res) {
            if ($request->is_open) {
                return response()->json([
                    'message' => 'Game Maintenance Opened Successful.'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Game Maintenance Closed Successful.'
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Please try again.'
            ], 400);
        }
    }


    public function MyvipWave(Request $request)
    {
        $overall_setting = WaveOpenOff::first();
        $overall_setting->myvip_wave = $request->is_open;
        $res = $overall_setting->save();
        if ($res) {
            if ($request->is_open) {
                return response()->json([
                    'message' => 'Myvip auto wave Opened Successful.'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Myvip auto wave  Closed Successful.'
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Please try again.'
            ], 400);
        }
    }

    

    public function IcasinoWave(Request $request)
    {
        $overall_setting = WaveOpenOff::first();
        $overall_setting->icasino_wave = $request->is_open;
        $res = $overall_setting->save();
        if ($res) {
            if ($request->is_open) {
                return response()->json([
                    'message' => 'Icasino auto wave Opened Successful.'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Icasino auto wave  Closed Successful.'
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Please try again.'
            ], 400);
        }
    }

    
}
