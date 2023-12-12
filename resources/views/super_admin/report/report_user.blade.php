@extends('super_admin.backend_layout.app')

@section('title', 'User Report')

@section('report-user-active', 'active')

@section('content')

    <ul class="nav nav-tabs" id="account" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Users</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Cash In</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="profile-1-tab" data-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="false">Cash Out</a>
        </li>
    </ul>
    <br><br>
    <div class="tab-content" id="accountContent">
        <div class="tab-pane active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card card-dark card-outline">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_super_admin" class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>User Code</th>
                                            <th>Balance</th>
                                            <th>Referral</th>
                                            <th>Image</th>
                                            <th>Create Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $n=>$item)
                                            <tr>
                                                <td>{{ $n+1 }}</td>
                                                <td>{{ $item->name ?? '-' }}</td>
                                                <td>{{ $item->user_code ?? '-' }}</td>
                                                <td>{{ number_format($item->balance ?? '0.00') }} MMK</td>
                                                <td>{{ $item->referral ?? '-' }}</td>
                                                <td>
                                                    @if ($item->image)
                                                        <img src="{{ asset($item->image) }}" width="50" class="mt-1">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $item->created_at->format('d.m.Y h:i A') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-money-bill"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                          <a class="dropdown-item" href="{{ url('/super_admin/cash_in_history/'.$item->id) }}">Cash In History</a>
                                                          <a class="dropdown-item" href="{{ url('/super_admin/cash_out_history/'.$item->id) }}">Cash Out History</a>
                                                        </div>
                                                    </div>
                                                </td>
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
        <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card card-dark card-outline">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_senior_agent" class="table table-striped table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>User Name</th>
                                            <th>Payment</th>
                                            <th>Time</th>
                                            <th>Old Amount</th>
                                            <th>Amount</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cash_ins as $i=>$item)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $item->user->name ?? '-' }}</td>
                                            <td>{{ $item->payment_method->name ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d.m.Y h:i A') }}</td>
                                            <td>{{ number_format($item->old_amount ?? '0.00') }} MMK</td>
                                            <td>{{ number_format($item->amount ?? '0.00') }} MMK</td>
                                            <td>{{ number_format($item->old_amount + $item->amount) }} MMK</td>
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
        <div class="tab-pane" id="profile-1" role="tabpanel" aria-labelledby="profile-1-tab">
            <div class="card card-dark card-outline">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_master_agent" class="table table-striped table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>User Name</th>
                                            <th>Payment</th>
                                            <th>Time</th>
                                            <th>Old Amount</th>
                                            <th>Amount</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cash_outs as $i=>$item)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $item->user->name ?? '-' }}</td>
                                                <td>{{ $item->payment_method->name ?? '-' }}</td>
                                                <td>{{ $item->created_at->format('d.m.Y h:i A') }}</td>
                                                <td>{{ number_format($item->old_amount ?? '0.00') }} MMK</td>
                                                <td>{{ number_format($item->amount ?? '0.00') }} MMK</td>
                                                <td>{{ number_format($item->old_amount - $item->amount) }} MMK</td>
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
