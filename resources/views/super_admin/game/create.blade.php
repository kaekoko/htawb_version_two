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
                    <form method="POST" enctype="multipart/form-data" action="{{ url('/game/bannertwo/store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="file">Image</label>
                            <input type="file" class="form-control form-control-sm" name="image">
                        </div>
                        <div class="form-group">
                            <label for="file">Mobile Image</label>
                            <input type="file" class="form-control form-control-sm" name="mb_image">
                        </div>
                        <button type="submit" class="btn btn-dark">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@endsection
