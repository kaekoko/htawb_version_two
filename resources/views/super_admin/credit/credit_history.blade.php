@extends('super_admin.backend_layout.app')

@section('title', 'Credit History')

@section('credit-history-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/credit_all_agent') }}" class="btn btn-dark btn-sm" title="Add Credit All Agents">
        <i class="fa fa-money-bill" aria-hidden="true"></i> Add Credit All Agents
    </a>
    <br/>
    <br/>
    <div class="card card-dark card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fa fa-money-bill"></i> Credit Histories
            </h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="account" role="tablist">
                <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="false">Credit In</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Credit Out</a>
                </li>
            </ul>
            <div class="tab-content" id="accountContent">
                <div class="tab-pane fade active show" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_custom" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th><th>SuperAdmin Name</th><th>Agent Name</th><th>Credit</th><th>Payment Name</th><th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($credit_histories as $item)
                                            <tr>
                                                <td></td>
                                                <td>{{ $item->super_admin->name ?? '' }}</td>
                                                <td>
                                                    {{ $item->senior_agent->name ?? '' }}
                                                    {{ $item->master_agent->name ?? '' }}
                                                    {{ $item->agent->name ?? '' }}
                                                </td>
                                                <td>{{ number_format($item->credit) }}</td>
                                                <td>{{ $item->payment_method->name ?? '-' }}</td>
                                                <td>{{ $item->created_at->format('d.m.Y h:i A') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_auto" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th><th>SuperAdmin Name</th><th>Agent Name</th><th>Credit</th><th>Payment Name</th><th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($credit_out_histories as $item)
                                            <tr>
                                                <td></td>
                                                <td>{{ $item->super_admin->name ?? '' }}</td>
                                                <td>
                                                    {{ $item->senior_agent->name ?? '' }}
                                                    {{ $item->master_agent->name ?? '' }}
                                                    {{ $item->agent->name ?? '' }}
                                                </td>
                                                <td>{{ number_format($item->credit) }}</td>
                                                <td>{{ $item->payment_method->name ?? '-' }}</td>
                                                <td>{{ $item->created_at->format('d.m.Y h:i A') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
