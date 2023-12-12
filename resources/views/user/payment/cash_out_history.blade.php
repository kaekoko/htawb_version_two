@extends('user.backend_layout.app')

@section('title', 'Cash Out History')

@section('payment-active', 'active')

@section('content')
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="datatable_1" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th><th>User Name</th><th>Payment</th><th>Agent</th><th>Time</th><th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($cash_outs as $item)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $item->user->name ?? '-' }}</td>
                                        <td>{{ $item->payment_method->name ?? '-' }}</td>
                                        <td>
                                            {{ $item->super_admin->name ?? '' }}
                                            {{ $item->senior_agent->name ?? '' }}
                                            {{ $item->master_agent->name ?? '' }}
                                            {{ $item->agent->name ?? '' }}
                                        </td>
                                        <td>{{ $item->created_at->format('d.m.Y h:i A') }}</td>
                                        <td><span class="text-danger font-weight-bold">{{ number_format($item->amount ?? '0.00') }} MMK</span></td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
