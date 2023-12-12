@extends('super_admin.backend_layout.app')

@section('title', 'Blog Edit')

@section('blog-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/blog') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('blog.update',$blog->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="title" value="{{ $blog->title }}"  placeholder="Enter Title">
                        </div>
                        <div>
                            <label for="title">Body<span class="text-danger">*</span></label>
                            <textarea id="summernoteNoImage" name="body">
                                {!! $blog->body !!}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="file">Feature Image</label>
                            <input type="file" class="form-control form-control-sm" name="feature_image">
                            <img src="{{ asset($blog->feature_image) }}" width="150" class="mt-1">
                        </div>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\BlogUpdateRequest') !!}
@endsection
