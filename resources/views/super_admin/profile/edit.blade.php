@extends('super_admin.backend_layout.app')

@section('title', 'Edit Profile')

@section('view-account-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/profile/'.$profile->id) }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/profile/'.$profile->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control form-control-sm" name="name" value="{{ $profile->name }}"  placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="number" class="form-control form-control-sm" id="check_phone" value="{{ $profile->phone }}" placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file">Image</label>
                            <input type="file" class="form-control form-control-sm" name="image">
                            @if($profile->image)
                                <img src="{{ asset($profile->image) }}" width="100" class="mt-1">
                            @else
                                <img src="{{ asset('backend/avatar.png') }}" width="100" class="mt-1">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-dark">Update</button>
                    </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\ProfileUpdateRequest') !!}
@endsection

