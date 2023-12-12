@extends('super_admin.backend_layout.app')
@section('title', 'I CASINO Banner')
@section('game-dashboard-active', 'active')
@section('content')
@php
$role_id = Auth::guard('super_admin')->user()->role_id;
@endphp
<a href="{{ url('/game/bannertwo/create') }}" class="btn btn-dark btn-sm" title="Add City">
        <i class="fa fa-plus" aria-hidden="true"></i> Add Slider
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
                                <th>#</th><th>Banner Image</th><th>Mobile Banner Image</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banners as $item)
                                    <tr>
                                        <td></td>
                                        <td><img src="{{ asset($item->image) }}" width="50" class="mt-1"></td>
                                        <td>
                                            @if ( $item->mb_image )
                                                <img src="{{ asset($item->mb_image) }}" width="50" class="mt-1">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('game/bannertwo/'.$item->id.'/edit') }}" title="Edit"><button class="btn btn-primary btn-sm"><i class="fa fa-solid fa-pen"></i></button></a>
                                            <form id="postBtn{{ $item->id }}" method="post" action="{{ url('game/bannertwo/destroy', $item->id) }}" style="display: inline;">
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
@section('scripts')
<script src="{{ asset('custom/game_dashboard.js') }}"></script>
@endsection