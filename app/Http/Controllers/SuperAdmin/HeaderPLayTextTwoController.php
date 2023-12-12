<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Models\HeaderPlayText;
use App\Models\HeaderPlayTextTwo;
use App\Http\Controllers\Controller;
use App\Http\Requests\HeaderPlayTextRequest;

class HeaderPLayTextTwoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $header_play_texts = HeaderPlayTextTwo::all();
        return view('super_admin.haeder_play_text_two.index',compact('header_play_texts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.haeder_play_text_two.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HeaderPlayTextRequest $request)
    {
        $header_play_text = new HeaderPlayTextTwo();
        $header_play_text->text = $request->text;
        $header_play_text->save();
        return redirect('game/header_play_text_two')->with('flash_message','Header Play Text Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $header_play_text = HeaderPlayTextTwo::findOrFail($id);
        return view('super_admin.haeder_play_text_two.edit',compact('header_play_text'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HeaderPlayTextRequest $request, $id)
    {
        $header_play_text = HeaderPlayTextTwo::findOrFail($id);
        $header_play_text->text = $request->text;
        $header_play_text->update();
        return redirect('game/header_play_text_two')->with('flash_message','Header Play Text Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        HeaderPlayTextTwo::find($id)->delete();
        return redirect('game/header_play_text_two')->with('flash_message','Header Play Text Deleted');
    }
}
