@extends('user.backend_layout.app')

@section('title', 'Edit Profile')

@section('dashboard-active', 'active')

@section('content')
    <a href="{{ url('/user/profile/'.$profile->id) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('user/profile/'.$profile->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control form-control-sm" name="name" value="{{ $profile->name }}"  placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="number" class="form-control form-control-sm" id="check_phone" value="{{ $profile->phone }}" placeholder="Enter Phone Number">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control form-control-sm" name="password" placeholder="Enter Password">
                        </div>
                        <div class="form-group">
                            <label for="file">Image</label>
                            <input type="file" class="form-control form-control-sm" name="image">
                            @if($profile->image)
                                <img src="{{ asset($profile->image) }}" width="100" class="mt-1">
                            @else
                                <img src="{{ asset('backend/avatar.png') }}" width="100" class="mt-1">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\ProfileUpdateRequest') !!}
@endsection

