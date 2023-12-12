@extends('super_admin.backend_layout.app')

@section('title', 'Blog')

@section('blog-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/blog/create') }}" class="btn btn-dark btn-sm" title="Add Blog">
        <i class="fa fa-plus" aria-hidden="true"></i> Add Blog
    </a>
    <br/>
    <br/>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th><th>Title</th><th>Feature Image</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blogs as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->title }}</td>
                                        <td><img src="{{ asset($item->feature_image) }}" width="50" class="mt-1"></td>
                                        <td>
                                            <a href="{{ url('/super_admin/blog/'.$item->id) }}" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a>
                                            <a href="{{ url('super_admin/blog/'.$item->id.'/edit') }}" title="Edit"><button class="btn btn-primary btn-sm"><i class="fa fa-solid fa-pen"></i></button></a>
                                            <form id="postBtn{{ $item->id }}" method="post" action="{{ route('blog.destroy', $item->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
