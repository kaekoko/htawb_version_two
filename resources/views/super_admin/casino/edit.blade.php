@extends('super_admin.backend_layout.app')

@section('title', 'User Edit')

@section('user-active', 'active')

@section('content')
    <a href="{{ url('/game/user') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <form method="POST" enctype="multipart/form-data" action="{{ url('game/user/'.$user->id) }}">
                @csrf
                @method('PUT')
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control form-control-sm" name="name" value="{{ $user->name }}"  placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control form-control-sm" id="check_phone" value="{{ $user->phone }}" placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control form-control-sm" name="password" placeholder="Enter Password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file">Image</label>
                            <input type="file" class="form-control form-control-sm" name="image">
                            @if($user->image)
                                <img src="{{ asset($user->image) }}" width="100" class="mt-1">
                            @else
                                <img src="{{ asset('backend/avatar.png') }}" width="100" class="mt-1">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-dark">Edit User</button>
                    </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\AccountUpdateRequest') !!}
@endsection
