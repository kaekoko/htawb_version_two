<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(){
        $services = Service::all();
        return response()->json([
            'result' => count($services),
            'status' => 200,
            'message' => 'success',
            'data' => $services
        ]);
    }
}
