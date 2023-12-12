@extends('super_admin.backend_layout.app')

@section('title', 'Commisssion History')

@section('commission-history-active', 'active')

@section('content')

    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table_super_admin" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th><th colspan="2">Senior Agent Commission</th><th colspan="2">Master Agent Commission</th><th colspan="2">Agent Commission</th><th>Total Commission</th><th>User Name</th><th>Bet Slip Id</th><th>Section</th><th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commission_histories as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->senior_agent->name ?? '-' }}</td>
                                        <td>{{ $item->senior_agent_commission === '0.00' ? '-' : number_format($item->senior_agent_commission).' MMK' }}</td>
                                        <td>{{ $item->master_agent->name ?? '-' }}</td>
                                        <td>{{ $item->master_agent_commission === '0.00' ? '-' : number_format($item->master_agent_commission).' MMK' }}</td>
                                        <td>{{ $item->agent->name ?? '-' }}</td>
                                        <td>{{ $item->agent_commission === '0.00' ? '-' : number_format($item->agent_commission).' MMK' }}</td>
                                        <td>{{ number_format($item->total_commission) ?? '-' }} MMK</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->bet_slip_id }}</td>
                                        <td>{{ $item->section }}</td>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
