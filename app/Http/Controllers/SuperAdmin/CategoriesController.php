<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('super_admin.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $category = new Category();
            $category->name = $request->name;
            $category->odd = $request->odd;
            $category->image = $image->store('category');
            $category->save();
        }else{
            $category = new Category();
            $category->name = $request->name;
            $category->odd = $request->odd;
            $category->save();
        }
        return redirect('super_admin/category')->with('flash_message','Category Created');
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
        $category = Category::findOrFail($id);
        return view('super_admin.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->odd = $request->odd;
        if($request->hasFile('image')) {
            Storage::delete($category->image);
            $category->image =  $request->file('image') ? $request->file('image')->store('category') : $category->image;
        }
        $category->update();
        return redirect('super_admin/category')->with('flash_message','Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if($category->image){
            Storage::delete($category->image);
        }
        $category->delete();
        return redirect('super_admin/category')->with('flash_message','Category Deleted');
    }
}
