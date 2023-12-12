@extends('super_admin.backend_layout.app')

@section('title', 'Service')

@section('service-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/service/create') }}" class="btn btn-dark btn-sm" title="Add Service">
        <i class="fa fa-plus" aria-hidden="true"></i> Add Service
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
                                    <th>#</th><th>Service Name</th><th>Phone</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->name ?? '-' }}</td>
                                        <td>{{ $item->phone ?? '-' }}</td>
                                        <td>
                                            <a href="{{ url('super_admin/service/'.$item->id.'/edit') }}" title="Edit"><button class="btn btn-primary btn-sm"><i class="fa fa-solid fa-pen"></i></button></a>
                                            <form id="postBtn{{ $item->id }}" method="post" action="{{ route('service.destroy', $item->id) }}" style="display: inline;">
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
