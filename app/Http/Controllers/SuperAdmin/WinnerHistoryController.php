<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\helper;
use App\Models\Section;
use App\Models\UserBet;
use App\Models\Section1d;
use App\Models\Section3d;
use App\Models\UserBet1d;
use App\Models\UserBet3d;
use App\Models\Sectionc1d;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\SectionCrypto2d;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class WinnerHistoryController extends Controller
{
    public function index(Request $request)

    {

        $type=$request->type;
        if($request->start_date){
            $start = $request->start_date;
        }else{
            $start = Carbon::now()->subDays(1);
        }

        if($request->end_date){
            $end = $request->end_date;
        }else{
            $end = Carbon::now();
        }
      
       if($type == '' || $type == '2D'){
       
        if($request->section){
            $winners = UserBet::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->where('section',$request->section)->orderBy("date", "Desc")->get();
        }elseif($request->side_stats){
            $winners = UserBet::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0);
            $winners = $winners->whereHas('user', function ($query) use($request)  {
                if($request->side_stats == 'myvip'){
                 return $query->where('side',null);
                }elseif($request->side_stats == 'icasino'){
                 return $query->where('side',1);
                }
             })->orderBy("date", "Desc")->get();
        }elseif($request->section && $request->side_stats){
            $winners = UserBet::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->where('section',$request->section);
            $winners = $winners->whereHas('user', function ($query) use($request)  {
                if($request->side_stats == 'myvip'){
                 return $query->where('side',null);
                }elseif($request->side_stats == 'icasino'){
                 return $query->where('side',1);
                }
             })->orderBy("date", "Desc")->get();
        }else{
            $winners = UserBet::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->orderBy("date", "Desc")->get();
        }

        $sections = Section::where('is_open',1)->get();
       }
       if($type == '1D'){
        if($request->section){
            $winners = UserBet1d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->where('section',$request->section)->orderBy("date", "Desc")->get();
        }elseif($request->side_stats){
            $winners =UserBet1d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0);
            $winners = $winners->whereHas('user', function ($query) use($request)  {
                if($request->side_stats == 'myvip'){
                 return $query->where('side',null);
                }elseif($request->side_stats == 'icasino'){
                 return $query->where('side',1);
                }
             })->orderBy("date", "Desc")->get();
        }elseif($request->section && $request->side_stats){
            $winners =UserBet1d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->where('section',$request->section);
            $winners = $winners->whereHas('user', function ($query) use($request)  {
                if($request->side_stats == 'myvip'){
                 return $query->where('side',null);
                }elseif($request->side_stats == 'icasino'){
                 return $query->where('side',1);
                }
             })->orderBy("date", "Desc")->get();
        }else{
            $winners = UserBet1d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->orderBy("date", "Desc")->get();
        }
        $sections = Section1d::where('is_open',1)->get();
       }
       if($type == '3D'){
       

        if($request->side_stats){
            $winners =UserBet3d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0);
            $winners = $winners->whereHas('user', function ($query) use($request)  {
                if($request->side_stats == 'myvip'){
                 return $query->where('side',null);
                }elseif($request->side_stats == 'icasino'){
                 return $query->where('side',1);
                }
             })->orderBy("date", "Desc")->get();
        }else{
            $winners = UserBet3d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->orderBy("date", "Desc")->get();
        }
        $sections = [];
       }
       if($type == 'C2D'){
        
        if($request->section){
            $winners = UserBetCrypto2d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->where('section',$request->section)->orderBy("date", "Desc")->get();
        }elseif($request->side_stats){
            $winners =UserBetCrypto2d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0);
            $winners = $winners->whereHas('user', function ($query) use($request)  {
                if($request->side_stats == 'myvip'){
                 return $query->where('side',null);
                }elseif($request->side_stats == 'icasino'){
                 return $query->where('side',1);
                }
             })->orderBy("date", "Desc")->get();
        }elseif($request->section && $request->side_stats){
            $winners =UserBetCrypto2d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->where('section',$request->section);
            $winners = $winners->whereHas('user', function ($query) use($request)  {
                if($request->side_stats == 'myvip'){
                 return $query->where('side',null);
                }elseif($request->side_stats == 'icasino'){
                 return $query->where('side',1);
                }
             })->orderBy("date", "Desc")->get();
        }else{
            $winners = UserBetCrypto2d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->orderBy("date", "Desc")->get();
        }
        $sections = SectionCrypto2d::get();
       }
       if($type == 'C1D'){
        if($request->section){
            $winners = UserBetCrypto1d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->where('section',$request->section)->orderBy("date", "Desc")->get();
        }elseif($request->side_stats){
            $winners = UserBetCrypto1d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0);
            $winners = $winners->whereHas('user', function ($query) use($request)  {
                if($request->side_stats == 'myvip'){
                 return $query->where('side',null);
                }elseif($request->side_stats == 'icasino'){
                 return $query->where('side',1);
                }
             })->orderBy("date", "Desc")->get();
        }elseif($request->section && $request->side_stats){
            $winners = UserBetCrypto1d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->where('section',$request->section);
            $winners = $winners->whereHas('user', function ($query) use($request)  {
                if($request->side_stats == 'myvip'){
                 return $query->where('side',null);
                }elseif($request->side_stats == 'icasino'){
                 return $query->where('side',1);
                }
             })->orderBy("date", "Desc")->get();
        }else{
            $winners = UserBetCrypto1d::where("win", 1)->whereBetween('date', [$start, $end])->where("reward", 1)->where("reward_amount", ">", 0)->orderBy("date", "Desc")->get();
        }
        $sections = Sectionc1d::where('is_open',1)->get();
       }
    
        return view('super_admin.winner_history.index', compact('type','winners','sections'));
    }
}