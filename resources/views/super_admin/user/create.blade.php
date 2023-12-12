@extends('super_admin.backend_layout.app')

@section('title', 'User Create')

@section('user-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/user') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
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
            <form method="POST" autocomplete="off" enctype="multipart/form-data" action="{{ url('super_admin/user') }}">
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
                    {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="balance">Balance</label>
                            <input type="text" id="balance" value="0" class="form-control form-control-sm" name="balance" placeholder="Enter Balance">
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password<span class="text-danger">*</span></label>
                            <input type="password" id="password" class="form-control form-control-sm" name="password" placeholder="Enter Password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file">Image</label>
                            <input type="file" class="form-control form-control-sm" name="image" onchange="loadFile(event)">
                            <img id="output" width="100" class="mt-2"/>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="form-group payment_method_user">
                            <label>Payment Method<span class="text-danger">*</span></label>
                            <select name="payment_id" class="form-control select2">
                                @foreach ($payment_methods as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-dark">Create User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\UserCreateRequest') !!}
    <script>
        $('.payment_method_user').hide();
        $('.balance_user').keyup(function() {
            if($(this).val()){
                $('.payment_method_user').show();
            }else{
                $('.payment_method_user').hide();
            }
        });
    </script>
@endsection
