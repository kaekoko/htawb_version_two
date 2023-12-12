@extends('super_admin.backend_layout.app')

@section('title', 'Service Edit')

@section('service-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/service') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('service.update',$service->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="name" value="{{ $service->name }}"  placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="name">Phone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="phone" value="{{ $service->phone }}"  placeholder="Enter Phone">
                        </div>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\ServiceRequest') !!}
@endsection
