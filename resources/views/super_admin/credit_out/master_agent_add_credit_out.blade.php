@extends('super_admin.backend_layout.app')

@section('title', 'Add Credit Out')

@section('credit-history-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/view_account') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('/super_admin/master_agent_update_credit_out/'.$agent->id) }}">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control form-control-sm" value="{{ $agent->name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-control form-control-sm" value="Master Agent" disabled>
                        </div>
                        <div class="form-group">
                            <label for="credit">Credit<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="credit"  placeholder="Enter Credit Amount">
                        </div>
                        <div class="form-group payment_method_senior_agent">
                            <label>Payment Method<span class="text-danger">*</span></label>
                            <select name="payment_method_id" class="form-control select2">
                                @foreach ($payment_methods as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark">Add Credit Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\CreditRequest') !!}
@endsection
