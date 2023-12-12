@extends('super_admin.backend_layout.app')

@section('title', "Transaction Report: $date")

@section('win-lose-active', 'active')

@section('content')
    <a href="{{ url()->previous() }}" title="Back">
        <button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i>
            Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table_super_admin" class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>Game Type</th>
                                    <th>Bet Time</th>
                                    <th>Bet</th>
                                    <th>Turnover</th>
                                    <th>Commission</th>
                                    <th>W/L</th>
                                    <th>P/L</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $key => $data)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $data['username'] }}</td>
                                        <td>{{ $data['gametype'] }}</td>
                                        <td>{{ $data['bet_time'] }}</td>
                                        <td>{{ $data['bet'] }}</td>
                                        <td>{{ $data['turnover'] }}</td>
                                        <td>{{ $data['commission'] }}</td>
                                        <td>
                                            @if ($data["winloss"] < 0)
                                                <span class="badge badge-danger">{{ $data["winloss"] }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $data["winloss"] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data["profitloss"] < 0)
                                                <span class="badge badge-danger">{{ $data["profitloss"] }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $data["profitloss"] }}</span>
                                            @endif
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
@endsection
