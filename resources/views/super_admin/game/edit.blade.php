@extends('super_admin.backend_layout.app')

@section('game-dashboard-active', 'active')

@section('create-game-active', 'active')

@section('content')

@section('content')
<a href="{{ url('/game/bannertwo') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('game/bannertwo/update',$banner->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="file">Image</label>
                            <input type="file" class="form-control form-control-sm" name="image">
                            @if($banner->image)
                                <img src="{{ asset($banner->image) }}" width="100" class="mt-1">
                            @else
                                <img src="{{ asset('backend/avatar.png') }}" width="100" class="mt-1">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="file">Mobile Image</label>
                            <input type="file" class="form-control form-control-sm" name="mb_image">
                            @if($banner->mb_image)
                                <img src="{{ asset($banner->mb_image) }}" width="100" class="mt-1">
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
