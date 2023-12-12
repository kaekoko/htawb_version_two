<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index(){
        $blogs = Blog::all();
        return response()->json([
            'result' => count($blogs),
            'status' => 200,
            'message' => 'success',
            'data' => $blogs
        ]);
    }
}
