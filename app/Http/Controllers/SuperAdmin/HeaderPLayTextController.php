<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Models\HeaderPlayText;
use App\Http\Controllers\Controller;
use App\Http\Requests\HeaderPlayTextRequest;

class HeaderPLayTextController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $header_play_texts = HeaderPlayText::all();
        return view('super_admin.header_play_text.index',compact('header_play_texts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.header_play_text.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HeaderPlayTextRequest $request)
    {
        $header_play_text = new HeaderPlayText();
        $header_play_text->text = $request->text;
        $header_play_text->save();
        return redirect('super_admin/header_play_text')->with('flash_message','Header Play Text Created');
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
        $header_play_text = HeaderPlayText::findOrFail($id);
        return view('super_admin.header_play_text.edit',compact('header_play_text'));
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
        $header_play_text = HeaderPlayText::findOrFail($id);
        $header_play_text->text = $request->text;
        $header_play_text->update();
        return redirect('super_admin/header_play_text')->with('flash_message','Header Play Text Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        HeaderPlayText::find($id)->delete();
        return redirect('super_admin/header_play_text')->with('flash_message','Header Play Text Deleted');
    }
}
