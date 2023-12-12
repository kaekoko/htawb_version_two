@extends('super_admin.backend_layout.app')

@section('title', 'User Detail')

@section('user-active', 'active')

@section('content')
<a href="{{url('/super_admin/user')}}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
<a href="{{ url('super_admin/user/'.$user->id.'/edit') }}" title="Edit"><button class="btn btn-dark btn-sm"><i class="fa fa-solid fa-pen"></i> Edit</button></a>
<div class="row mt-3">
    <div class="col-md-8 offset-md-2">
        <!-- Widget: user widget style 2 -->
        <div class="card card-widget widget-user-2">
            <div class="widget-user-header bg-dark">
                <div class="widget-user-image">
                    @if ($user->image)
                    <img class="img-circle elevation-2" src="{{ asset($user->image) }}">
                    @else
                    <img class="img-circle elevation-2" src="{{ asset('backend/avatar.png') }}">
                    @endif
                </div>
                <h3 class="widget-user-username">{{ $user->name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="callout">
                            <h5>Phone Number</h5>
                            <p>{{ $user->phone }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="callout">
                            <h5>Role</h5>
                            <p>User</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="callout">
                            <h5>Balance</h5>
                            <p>{{ number_format($user->balance ?? '0.00') }} MMK</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="callout">
                            <h5>Email</h5>
                            <p>{{ $user->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="callout">
                            <h5>Birthday</h5>
                            <p>
                                @if($user->birthday)
                                {{ date('d-m-Y', strtotime($user->birthday))}}
                                @else
                                -
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 ">
                        <div class="callout d-flex justify-content-between">
                            <div>
                                <h5>Referral</h5>
                                <p class="copy_text">{{ $user->referral ?? '-' }}</p>
                            </div>
                            <div> <button title="Referral clipboard" onclick="copyTextClick()"><i class="far fa-clipboard"></i></button></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
</div>
@endsection