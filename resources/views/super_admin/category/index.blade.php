@extends('super_admin.backend_layout.app')

@section('title', 'Category')

@section('category-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/category/create') }}" class="btn btn-dark btn-sm" title="Add Category">
        <i class="fa fa-plus" aria-hidden="true"></i> Add Category
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
                                    <th>#</th><th>Name</th><th>Odd</th><th>Image</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->name ?? '-' }}</td>
                                        <td>{{ $item->odd ?? '-' }}</td>
                                        <td>
                                            @if ($item->image)
                                                <img src="{{ asset($item->image) }}" width="50" class="mt-1">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('super_admin/category/'.$item->id.'/edit') }}" title="Edit"><button class="btn btn-primary btn-sm"><i class="fa fa-solid fa-pen"></i></button></a>
                                            <form id="postBtn{{ $item->id }}" method="post" action="{{ route('category.destroy', $item->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button  class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button>
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
