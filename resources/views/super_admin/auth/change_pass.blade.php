@extends('super_admin.backend_layout.app')

@section('title', 'Change Password')

@section('content')
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" action="{{ url('super_admin/change_pass') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Old Password<span class="text-danger">*</span></label>
                            <input type="password" required class="form-control form-control-sm" name="old_password"
                                placeholder="Enter Old Password">
                        </div>
                        <div class="form-group">
                            <label for="name">New Password<span class="text-danger">*</span></label>
                            <input type="password" required class="form-control form-control-sm" name="new_password"
                                placeholder="Enter New Password">
                        </div>
                        <div class="form-group">
                            <label for="name">Confirm Password<span class="text-danger">*</span></label>
                            <input type="password" required class="form-control form-control-sm"
                                name="new_password_confirmation" placeholder="Enter Confirm Password">
                        </div>
                        <button type="submit" class="btn btn-dark">Change</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\ChangePassRequest') !!}
@endsection
