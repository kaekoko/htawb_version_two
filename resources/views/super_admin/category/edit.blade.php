@extends('super_admin.backend_layout.app')

@section('title', 'Edit Category Section')

@section('category-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/category') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('category.update', $category->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="name" value="{{ $category->name }}"  placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="odd">Odd<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="odd" value="{{ $category->odd }}"  placeholder="Enter Odd">
                        </div>
                        <div class="form-group">
                            <label for="file">Image</label>
                            <input type="file" class="form-control form-control-sm" name="image">
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" width="100" class="mt-1">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\CategoryRequest') !!}
@endsection
