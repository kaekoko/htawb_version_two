@extends('super_admin.backend_layout.app')

@section('title', 'User Create')

@section('game-providers', 'active')

@section('content')
<a href="{{ route('game_providers.index') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
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
        <form method="POST" autocomplete="off" enctype="multipart/form-data" action="{{ route('game_providers.store') }}">
            @csrf

            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="name" placeholder="Enter Name">
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Code<span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm" name="p_code" placeholder="Enter Code">
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Category<span class="text-danger">*</span></label>
                    <select name="category[]" multiple class="form-control select2">
                        @foreach ($categories as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="file">Image</label>
                    <input type="file" class="form-control form-control-sm" name="image" onchange="loadFile(event)">
                    <img id="output" width="100" class="mt-2" />
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-dark">Create Provider</button>
            </div>

        </form>
    </div>
</div>
@endsection
@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\GameProviderCreateRequest') !!}
@endsection