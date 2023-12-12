<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\City;
use Illuminate\Http\Request;

class GetAgentPhoneController extends Controller
{
    public function index($id)
    {
        $agent = Agent::where('city_id',$id)->inRandomOrder()->first();
        if(!empty($agent)){
            $count = Agent::where('city_id',$id)->count();
            $city = City::find($id);
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'number' => $agent->phone,
                'name' => $agent->name,
                'city' => $city->name,
                'count' => $count,
            ],200);
        } else {
            return response()->json([
                "status" => 404,
                "message" => 'agent not found.',
                'number' => '',
                'name' => '',
                'city' => '',
                'count' => 0,
            ],200);
        }
       
    }
}
