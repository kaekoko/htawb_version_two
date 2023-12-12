@extends('super_admin.backend_layout.app')

@section('title', 'Header Play Text')

@section('header-play-text-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/header_play_text/create') }}" class="btn btn-dark btn-sm" title="Add Header Play Text">
        <i class="fa fa-plus" aria-hidden="true"></i> Add Header Play Text
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
                                    <th>#</th><th>Name</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($header_play_texts as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->text ?? '-' }}</td>
                                        <td>
                                            <a href="{{ url('super_admin/header_play_text/'.$item->id.'/edit') }}" title="Edit"><button class="btn btn-primary btn-sm"><i class="fa fa-solid fa-pen"></i></button></a>
                                            <form id="postBtn{{ $item->id }}" method="post" action="{{ route('header_play_text.destroy', $item->id) }}" style="display: inline;">
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
