@extends('super_admin.backend_layout.app')

@section('title', 'Admin')

@section('admin-active', 'active')

@section('content')
    <a href="{{ url('super_admin/admin/create') }}" class="btn btn-dark btn-sm" title="Add Admin">
        <i class="fa fa-plus" aria-hidden="true"></i> Add Admin
    </a>
    <br>
    <br>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table_super_admin" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Id</th><th>Name</th><th>Phone Number</th><th>Role</th><th>Image</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $n=>$item)
                                    <tr>
                                        <td>{{ $n+1 }}</td>
                                        <td>{{ $item->name ?? '-' }}</td>
                                        <td>{{ $item->phone ?? '-' }}</td>
                                        <td>{{ $item->admin_role->name }}</td>
                                        <td>
                                            @if ($item->image)
                                                <img src="{{ asset($item->image) }}" width="50" class="mt-1">
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ url('super_admin/admin/'.$item->id.'/edit') }}" title="Edit"><button class="btn btn-primary btn-sm"><i class="fa fa-solid fa-pen"></i></button></a>
                                            @if (Auth::guard('super_admin')->user()->id == 1)
                                            <form id="postBtn{{ $item->id }}" method="post" action="{{ route('admin.destroy', $item->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
@endsection
