@extends('super_admin.backend_layout.app')

@section('title', 'Cash In')

@section('user-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/user') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/cash_in_create/'.$user->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">User Name</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $user->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="holder_name">Phone</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $user->phone }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="amount">Amount<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="amount"  placeholder="Enter Amount">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="transaction_id">Wave AND Kpay Transaction ID<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="transaction_id"  placeholder="Enter Amount">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Payment<span class="text-danger">*</span></label>
                                    <select name="payment_id" class="form-control select2">
                                        @foreach ($payments as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\CashInRequest') !!}
@endsection
