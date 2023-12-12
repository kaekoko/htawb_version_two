@extends('super_admin.backend_layout.app')

@section('title', 'Profile')

@section('view-account-active', 'active')

@section('content')
    <a href="{{ url('super_admin/profile/'.$profile->id.'/edit') }}" title="Edit"><button class="btn btn-default btn-sm"><i class="fa fa-solid fa-pen"></i> Edit</button></a>
    <div class="row mt-3">
        <div class="col-md-8 offset-md-2">
            <div class="card card-widget widget-user-2">
                <div class="widget-user-header bg-dark">
                    <div class="widget-user-image">
                        @if ($profile->image)
                            <img class="img-circle elevation-2" src="{{ asset($profile->image) }}">
                        @else
                        <img class="img-circle elevation-2" src="{{ asset('backend/avatar.png') }}">
                        @endif
                    </div>
                    <h3 class="widget-user-username">{{ $profile->name }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="callout">
                                <h5>Phone Number</h5>
                                <p>{{ $profile->phone }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="callout">
                                <h5>Role</h5>
                                <p>Super Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
    </div>
@endsection
