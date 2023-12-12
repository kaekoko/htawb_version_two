@extends('super_admin.backend_layout.app')

@section('title', 'User Create')

@section('game-categories', 'active')

@section('content')
<a href="{{ route('game_categories.index') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
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
        <form method="POST" autocomplete="off" enctype="multipart/form-data" action="{{ route('game_categories.store') }}">
            @csrf

            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="name" placeholder="Enter Name">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="file">Image</label>
                    <input type="file" class="form-control form-control-sm" name="image" onchange="loadFile(event)">
                    <img id="output" width="100" class="mt-2" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Code<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="code" placeholder="Enter Code">
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-dark">Create User</button>
            </div>

        </form>
    </div>
</div>
@endsection
@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\GameCategoryCreateRequest') !!}
@endsection