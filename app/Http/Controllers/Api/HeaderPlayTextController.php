<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\HeaderPlayText;
use App\Models\HeaderPlayTextTwo;
use App\Http\Controllers\Controller;

class HeaderPlayTextController extends Controller
{
    public function index(){
        $header_play_texts = HeaderPlayText::all();
        return response()->json([
            'result' => count($header_play_texts),
            'status' => 200,
            'message' => 'success',
            'data' => $header_play_texts
        ]);
    }

    public function index_new(){
        $header_play_texts = HeaderPlayTextTwo::all();
        return response()->json([
            'result' => count($header_play_texts),
            'status' => 200,
            'message' => 'success',
            'data' => $header_play_texts
        ]);
    }

    
}
