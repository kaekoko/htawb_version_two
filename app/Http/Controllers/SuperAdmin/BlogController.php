<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BlogUpdateRequest;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all();
        return view('super_admin.blog.index',compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogRequest $request)
    {
        $body = $request->body;
        $dom = new \DomDocument();
        $dom->loadHtml($body, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $images = $dom->getElementsByTagName('img');

        foreach($images as $k => $img){
            $data = $img->getAttribute('src');
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= "/blog/" . time().$k.'.png';
            $path = public_path() . $image_name;
            file_put_contents($path, $data);
            $img->removeAttribute('src');
            $img->setAttribute('src', $image_name);
        }
        $request->body = $dom->saveHTML();

        $feature_image = $request->file('feature_image');
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->body = $request->body;
        $blog->feature_image = $feature_image->store('blog');
        $blog->save();
        return redirect('super_admin/blog')->with('flash_message','Blog Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('super_admin.blog.show',compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('super_admin.blog.edit',compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogUpdateRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $dom = new \DomDocument();
        if(strpos($blog->body,'<img') === false){
            $dom->loadHtml($request->body, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        }else{
            $dom->loadHtml($id);
        }
        $images = $dom->getElementsByTagName('img');

        foreach($images as $k => $img){
            $data = $img->getAttribute('src');
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= "/blog/" . time().$k.'.png';
            $path = public_path() . $image_name;
            file_put_contents($path, $data);
            $img->removeAttribute('src');
            $img->setAttribute('src', $image_name);
        }

        if(strpos($blog->body,'<img') === false){
            $request->body = $dom->saveHTML();
        }

        $blog->title = $request->title;
        $blog->body = $request->body;
        if($request->hasFile('feature_image')) {
            Storage::delete($blog->feature_image);
            $blog->feature_image =  $request->file('feature_image') ? $request->file('feature_image')->store('blog') : $blog->feature_image;
        }
        $blog->update();
        return redirect('super_admin/blog')->with('flash_message','Blog Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        Storage::delete($blog->feature_image);
        $blog->delete();
        return redirect('super_admin/blog')->with('flash_message','Blog Deleted');
    }
}
