<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ShamelessGameCategory;
use App\Models\ShamelessGameProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShamelessGameProviderController extends Controller
{
    public function index()
    {
        $g_prov = ShamelessGameProvider::all();
        return view('super_admin.gameManagement.Provider.index', compact('g_prov'));
    }

    public function create()
    {
        $categories = ShamelessGameCategory::all();
        return view('super_admin.gameManagement.Provider.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $gameprovider = ShamelessGameProvider::create($request->all());
        if ($request->hasFile('image')) {
            $img = uniqid() . $request->image->getClientOriginalName();
            $request->file('image')->storeAs('public/game', $img);
            $gameprovider->image = $img;
            $gameprovider->update();
        }
        $gameprovider->categories()->attach($request->category);
        return redirect()->route('game_providers.index')->with('flash_message', 'Created New Category Successfully.');
    }

    public function edit($id)
    {
        $categories = ShamelessGameCategory::all();
        $gameprovider = ShamelessGameProvider::firstWhere('id', $id);
        return view('super_admin.gameManagement.Provider.edit', compact('gameprovider', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $gameprovider = ShamelessGameProvider::firstWhere('id', $id);
        $gameprovider->update($request->all());

        if ($request->hasFile('image')) {
            if (Storage::exists('public/game/' . $gameprovider->image)) {
                Storage::delete('public/game/' . $gameprovider->image);
            }

            $img = uniqid() . $request->image->getClientOriginalName();
            $request->file('image')->storeAs('public/game', $img);
            $gameprovider->image = $img;
            $gameprovider->update();
        }
        $gameprovider->categories()->sync($request->category);

        return redirect()->route('game_providers.index')->with('flash_message', 'Updated Provider Successfully.');
    }

    public function destroy($id)
    {
        $game = ShamelessGameProvider::find($id);
        if (Storage::exists('public/game/' . $game->image)) {
            Storage::delete('public/game/' . $game->image);
        }
        $game->delete();
        return redirect()->route('game_providers.index')->with('flash_message', 'Deleted Provider Successfully.');
    }
}
