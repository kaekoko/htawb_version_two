<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameCreateRequest;
use App\Models\ShamelessGame;
use App\Models\ShamelessGameCategory;
use App\Models\ShamelessGameProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GameManagementController extends Controller
{
    public function index(Request $request)
    {
        $categories = ShamelessGameCategory::all();
        $providers = ShamelessGameProvider::all();

        $row = ShamelessGame::with(['category', 'provider', 'created_by'])->select(sprintf('%s.*', (new ShamelessGame)->table));

        if ($request->ajax()) {
            return DataTables::of($row)->addIndexColumn()
                ->addColumn('category_name', function ($row) {
                    return $row->category ? $row->category->name : '';
                })
                ->addColumn('provider_name', function ($row) {
                    return $row->provider ? $row->provider->name : '';
                })
                ->addColumn('image', function ($row) {
                    return  $row->image ? $row->image : '';
                })
                ->addColumn('action', function ($row) {
                    return view('super_admin.gameManagement.Game.actions.actionBtn', compact('row'));
                })
                ->editColumn('html_type', function ($row) {
                    return $row->html_type ? ($row->html_type == 1 ? 'html_yes' : 'html_no') : '';
                })
                ->editColumn('active', function ($row) {
                    return view('super_admin.gameManagement.Game.actions.active', compact('row'));
                })
                ->editColumn('is_hot', function ($row) {
                    return view('super_admin.gameManagement.Game.actions.hot', compact('row'));
                })
                ->editColumn('is_new', function ($row) {
                    return view('super_admin.gameManagement.Game.actions.new', compact('row'));
                })
                ->make(true);
        }
        $activechecked = collect(ShamelessGame::all())->where('active', 0)->all();
        $hotchecked = collect(ShamelessGame::all())->where('is_hot', 0)->all();
        $newchecked = collect(ShamelessGame::all())->where('is_new', 0)->all();

        return view('super_admin.gameManagement.Game.index', compact('categories', 'providers', 'activechecked', 'hotchecked', 'newchecked'));
    }

    public function create()
    {
        $categories = ShamelessGameCategory::all();
        $providers = ShamelessGameProvider::all();
        return view('super_admin.gameManagement.Game.create', compact('categories', 'providers'));
    }

    public function store(GameCreateRequest $request)
    {
        $game = ShamelessGame::create($request->all());
        if ($request->hasFile('image')) {
            $img = uniqid() . $request->image->getClientOriginalName();
            $request->file('image')->storeAs('public/game', $img);
            $game->image = url('storage/game/' . $img);
            $game->update();
        }
        return redirect()->route('games.index')->with('flash_message', 'Created New Game Successfully.');
    }

    public function edit($id)
    {
        $categories = ShamelessGameCategory::all();
        $providers = ShamelessGameProvider::all();
        $game = ShamelessGame::firstWhere('id', $id);
        return view('super_admin.gameManagement.Game.edit', compact('game', 'categories', 'providers'));
    }

    public function update(Request $request, $id)
    {
        $game = ShamelessGame::firstWhere('id', $id);
        $game->update($request->all());

        if ($request->hasFile('image')) {
            if (Storage::exists('public/game/' . $game->image)) {
                Storage::delete('public/game/' . $game->image);
            }

            $img = uniqid() . $request->image->getClientOriginalName();
            $request->file('image')->storeAs('public/game', $img);
            $game->image = url('storage/game/' . $img);
            $game->update();
        }

        return redirect()->route('games.index')->with('flash_message', 'Updated Game Successfully.');
    }

    public function destroy($id)
    {
        $game = ShamelessGame::find($id);
        if (Storage::exists('public/game/' . $game->image)) {
            Storage::delete('public/game/' . $game->image);
        }
        $game->delete();
        return redirect()->route('games.index')->with('flash_message', 'Deleted Game Successfully.');
    }

    //status
    public function statusChange(Request $request)
    {
        switch ($request->type) {
            case "category":
                $gamecat = ShamelessGameCategory::where('id', $request->id)->first();
                $gamecat->active =
                    filter_var($request->status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                $gamecat->update();
                return response([
                    'success' => true,
                    "status" => $gamecat->active,
                    'name' => $gamecat->name
                ], 200);
            case "provider":
                $gameprov = ShamelessGameProvider::where('id', $request->id)->first();
                if ($request->hot) {
                    $gameprov->hot =
                        filter_var($request->status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                    $gameprov->update();
                    return response([
                        'success' => true,
                        "status" => $gameprov->hot,
                        'name' => $gameprov->name,
                    ], 200);
                } else {
                    $gameprov->active =
                        filter_var($request->status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                    $gameprov->update();
                    return response([
                        'success' => true,
                        "status" => $gameprov->active,
                        'name' => $gameprov->name
                    ], 200);
                }

            default:
                return;
        }
    }

    public function gameStatusChange(Request $request)
    {
        $game = ShamelessGame::where('id', $request->id)->first();
        switch ($request->type) {
            case 'active':
                $game->active =
                    filter_var($request->status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                $game->update();
                return response([
                    'success' => true,
                    "status" => $game->active,
                    'name' => $game->name
                ], 200);
            case 'hot':
                $game->is_hot =
                    filter_var($request->status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                $game->update();
                return response([
                    'success' => true,
                    "status" => $game->is_hot,
                    'name' => $game->name
                ], 200);
            case 'new':
                $game->is_new =
                    filter_var($request->status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                $game->update();
                return response([
                    'success' => true,
                    "status" => $game->is_new,
                    'name' => $game->name
                ], 200);
            default:
                return;
        }
    }

    public function allGameStatusChange(Request $request)
    {
        switch ($request->type) {
            case 'active':
                if ($request->status == 1) {
                    ShamelessGame::where('active', 0)->update(['active' => 1]);
                } else {
                    ShamelessGame::where('active', 1)->update(['active' => 0]);
                }
                return response([
                    'success' => true,
                ], 200);
            case 'hot':
                if ($request->status == 1) {
                    ShamelessGame::where('is_hot', 0)->update(['is_hot' => 1]);
                } else {
                    ShamelessGame::where('is_hot', 1)->update(['is_hot' => 0]);
                }
                return response([
                    'success' => true,
                ], 200);
            case 'new':
                if ($request->status == 1) {
                    ShamelessGame::where('is_new', 0)->update(['is_new' => 1]);
                } else {
                    ShamelessGame::where('is_new', 1)->update(['is_new' => 0]);
                }
                return response([
                    'success' => true,
                ], 200);
            default:
                return;
        }
    }
}
