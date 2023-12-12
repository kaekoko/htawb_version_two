<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Banner;
use App\Models\BannerTwo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    public function index(){
        $banner = Banner::get('image');
        return response()->json([
            'result' => count($banner),
            'status' => 200,
            'message' => 'success',
            'data' => $banner
        ]);
    }

    public function index_two(){
        $banner = BannerTwo::get('image');
        return response()->json([
            'result' => count($banner),
            'status' => 200,
            'message' => 'success',
            'data' => $banner
        ]);
    }

    public function banner_mobile()
    {
        $banner = Banner::get('mb_image');
        return response()->json([
            'result' => count($banner),
            'status' => 200,
            'message' => 'success',
            'banner' => $banner,
        ]);
    }

    public function  banner_mobile_two()
    {
        $banner = BannerTwo::get('mb_image');
        return response()->json([
            'result' => count($banner),
            'status' => 200,
            'message' => 'success',
            'banner' => $banner,
        ]);
    }
   
}
