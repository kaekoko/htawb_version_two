<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return response()->json([
            'result' => count($categories),
            'status' => 200,
            'message' => 'success',
            'data' => $categories
        ]);
    }
}
