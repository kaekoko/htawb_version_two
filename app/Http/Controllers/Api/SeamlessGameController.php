<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShamelessGame;
use App\Models\ShamelessGameCategory;
use App\Models\ShamelessGameProvider;
use Illuminate\Http\Request;

class SeamlessGameController extends Controller
{
    public function categories()
    {
        return response([
            'success' => true,
            'data' => ShamelessGameCategory::with('categoryGameProviders')
                ->where('active', 1)
                ->orderByRaw("FIELD(id, 3, 2, 1, 8, 6, 9)")
                ->get()
        ], 200);
    }

    public function providers($id)
    {
        $providers = ShamelessGameProvider::with('categories')
            ->whereHas('categories', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->where('active', 1)
            ->get();
        return response([
            'success' => true,
            'data' => $providers
        ], 200);
    }

    public function games(Request $request)
    {
        return ShamelessGame::where('active', 1)
            ->where('category_id', $request->category)
            ->where('provider_id', $request->provider)
            ->get();
    }

    //Hot Providers
    public function HotProvider($category)
    {
        $providers =
            ShamelessGameProvider::whereHas('categories', function ($query) use ($category) {
                return $query->where('id', $category);
            })->where('hot', 1)->get();
        return response(['success' => true, 'data' => $providers]);
    }
}
