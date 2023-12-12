<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function user_add_block(Request $request)
    {
        $block = User::find($request->id);
        $block->block = $request->status;
        $block->update();
        return [
            "name" => $block->name,
            "message" => $request->status
        ];
    }
}
