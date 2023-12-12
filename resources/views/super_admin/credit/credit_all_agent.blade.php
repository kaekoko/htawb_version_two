@extends('super_admin.backend_layout.app')

@section('title', 'Create Credit All Agents')

@section('credit-history-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/credit_history') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-money-bill"></i>
            Credit
        </h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="account" role="tablist">
                <li class="nav-item">
                <a class="nav-link" id="senior-agent-tab" data-toggle="pill" href="#senior-agent" role="tab" aria-controls="senior-agent" aria-selected="false">Senior Agent</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" id="master-agent-tab" data-toggle="pill" href="#master-agent" role="tab" aria-controls="master-agent" aria-selected="false">Master Agent</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" id="agent-tab" data-toggle="pill" href="#agent" role="tab" aria-controls="agent" aria-selected="true">Agent</a>
                </li>
            </ul>
            <div class="tab-content" id="accountContent">
                <div class="tab-pane fade" id="senior-agent" role="tabpanel" aria-labelledby="senior-agent-tab">
                    <div class="row mt-3">
                        <div class="col-12">
                            <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/senior_agent_credit') }}">
                                @csrf
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
                                <button type="submit" class="btn btn-dark">Create Credit Senior Agent</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="master-agent" role="tabpanel" aria-labelledby="master-agent-tab">
                    <div class="row mt-3">
                        <div class="col-12">
                            <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/master_agent_credit') }}">
                                @csrf
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
                                <button type="submit" class="btn btn-dark">Create Credit Master Agent</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="agent" role="tabpanel" aria-labelledby="agent-tab">
                    <div class="row mt-3">
                        <div class="col-12">
                            <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/agent_credit') }}">
                                @csrf
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
                                <button type="submit" class="btn btn-dark">Create Credit Agent</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\CreditRequest') !!}
@endsection
