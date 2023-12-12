@extends('super_admin.backend_layout.app')

@section('title', 'Admin Create Account')

@section('admin-active', 'active')

@section('content')
    <a href="{{ url('super_admin/admin') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <div class="card card-dark card-outline">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="name"  placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="phone" placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password<span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm" name="password" placeholder="Enter Password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file">Image</label>
                            <input type="file" class="form-control form-control-sm" name="image" onchange="loadFile(event)">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Role<span class="text-danger">*</span></label>
                        <select name="role_id" class="form-control select2">
                            @foreach ($roles as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <img id="output" width="100"/>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-dark">Create Admin</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\AdminCreateRequest') !!}
@endsection
