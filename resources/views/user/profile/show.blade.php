@extends('user.backend_layout.app')

@section('title', 'Profile')

@section('dashboard-active', 'active')

@section('content')
    <a href="{{ url('user/profile/'.$profile->id.'/edit') }}" title="Edit"><button class="btn btn-dark btn-sm"><i class="fa fa-solid fa-pen"></i> Edit</button></a>
    <div class="row mt-3">
        <div class="col-md-8 offset-md-2">
            <!-- Widget: user widget style 2 -->
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
              <div class="card-footer p-0">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link">
                        {{ $profile->phone }} <span class="float-right badge bg-primary">Phone</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link">
                        User <span class="float-right badge bg-primary">Role</span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /.widget-user -->
          </div>
    </div>
@endsection
