<?php

namespace App\Http\Controllers\Api;

use App\Models\PromoCashIn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PromoBonus;
use Illuminate\Support\Facades\Auth;

class PromoCashInController extends Controller
{
    public function get()
    {
        $data = PromoCashIn::all();
        return response()->json([
            'data' => $data
        ],200);
    }
}
