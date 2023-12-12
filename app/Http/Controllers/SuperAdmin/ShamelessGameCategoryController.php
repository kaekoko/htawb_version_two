<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ShamelessGameCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShamelessGameCategoryController extends Controller
{
    public function index()
    {
        $g_cat = ShamelessGameCategory::all();
        return view('super_admin.gameManagement.Category.index', compact('g_cat'));
    }

    public function create()
    {
        return view('super_admin.gameManagement.Category.create');
    }

    public function store(Request $request)
    {
        $gamecategory = ShamelessGameCategory::create($request->all());
        if ($request->hasFile('image')) {
            $img = uniqid() . $request->image->getClientOriginalName();
            $request->file('image')->storeAs('public/game', $img);
            $gamecategory->image = $img;
            $gamecategory->update();
        }

        return redirect()->route('game_categories.index')->with('flash_message', 'Created New Category Successfully.');
    }

    public function edit($id)
    {
        $gamecategory = ShamelessGameCategory::firstWhere('id', $id);
        return view('super_admin.gameManagement.Category.edit', compact('gamecategory'));
    }

    public function update(Request $request, $id)
    {
        $gamecategory = ShamelessGameCategory::firstWhere('id', $id);
        $gamecategory->update($request->all());
        if ($request->hasFile('image')) {
            if (Storage::exists('public/game/' . $gamecategory->image)) {
                Storage::delete('public/game/' . $gamecategory->image);
            }
            $img = uniqid() . $request->image->getClientOriginalName();
            $request->file('image')->storeAs('public/game', $img);
            $gamecategory->image = $img;
            $gamecategory->update();
        }
        return redirect()->route('game_categories.index')->with('flash_message', 'Updated Category Successfully.');
    }

    public function destroy($id)
    {
        $game = ShamelessGameCategory::find($id);
        if (Storage::exists('public/game/' . $game->image)) {
            Storage::delete('public/game/' . $game->image);
        }
        $game->delete();
        return redirect()->route('game_categories.index')->with('flash_message', 'Deleted Category Successfully.');
    }
}
