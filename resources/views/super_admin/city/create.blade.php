@extends('super_admin.backend_layout.app')

@section('title', 'Create City')

@section('city-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/city') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('city.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="name"  placeholder="Enter City Name">
                        </div>
                        <button type="submit" class="btn btn-dark">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\CityRequest') !!}
@endsection
