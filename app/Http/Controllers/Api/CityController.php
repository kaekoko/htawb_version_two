<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(){
        $cities = City::all();
        return response()->json([
            'result' => count($cities),
            'status' => 200,
            'message' => 'success',
            'data' => $cities
        ]);
    }
}
