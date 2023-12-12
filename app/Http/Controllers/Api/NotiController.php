<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotiController extends Controller
{
    public function save_device_token(Request $request)
    {
        $auth_id = Auth::user()->id;
        $token = User::find($auth_id);
        $token->device_token = $request->token;
        $token->update();

        return[
            'message'=>'success',
        ];
    }
}
