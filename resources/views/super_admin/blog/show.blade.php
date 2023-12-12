@extends('super_admin.backend_layout.app')

@section('title', 'Show Blog')

@section('blog-active', 'active')

@section('content')
    <a href="{{url('/super_admin/blog')}}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <a href="{{ url('super_admin/blog/'.$blog->id.'/edit') }}" title="Edit"><button class="btn btn-dark btn-sm"><i class="fa fa-solid fa-pen"></i> Edit</button></a>
    <br><br>
    <div class="table-responsive">
        <table class="table">
            <tr>
                <th>Title</th>
                <td>{{ $blog->title }}</td>
            </tr>
            <tr>
                <th>Body</th>
                <td>{!! $blog->body !!}</td>
            </tr>
            <tr>
                <th>Feature Image</th>
                <td><img src="{{ asset($blog->feature_image) }}" width="150" class="mt-1"></td>
            </tr>
        </table>
    </div>
@endsection
