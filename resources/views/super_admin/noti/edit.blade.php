@extends('super_admin.backend_layout.app')

@section('title', 'Edit Notification')

@section('noti-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/noti') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('noti.update',$noti->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="title" value="{{ $noti->title }}"  placeholder="Enter Title">
                        </div>
                        <div class="form-group">
                            <label>Body<span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="3" name="body" placeholder="Enter ...">{{ $noti->body }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\NotiRequest') !!}
@endsection
