@extends('super_admin.backend_layout.app')

@section('title', 'Edit Header Play Text')

@section('header-play-text-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/header_play_text') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('header_play_text.update', $header_play_text->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="text">Text<span class="text-danger">*</span></label>
                            <textarea class="form-control" name="text" rows="3" placeholder="Enter ...">{{ $header_play_text->text }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\HeaderPlayTextRequest') !!}
@endsection
