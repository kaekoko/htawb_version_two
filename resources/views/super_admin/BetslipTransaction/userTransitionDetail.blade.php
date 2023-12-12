@extends('super_admin.backend_layout.app')
@section('content')

<div class="mb-3"><a role="button" onclick="history.back()" class=" btn-sm btn-dark"><i class="fa-solid fa-circle-left mr-2"></i>Back</a></div>
<h5>Select The Providers</h5>
<form method="get">
    <div class="row align-items-center pt-1">
        <div class="form-group col-md-2">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request()->input('start_date', date('Y-m-d')) }}">
        </div>
        <div class="form-group col-md-2">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request()->input('end_date', date('Y-m-d')) }}">
        </div>

        <div class="col-2 mt-2 pt-1">
            <input type="text" class="form-control" value="{{ $code }}" disabled>
        </div>

        <div class="col-2 mt-2 pt-1">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>

</form>
<div class="row mt-3">
    <div class="col-12">
        <div class="table-responsive">
            <table id="data_table_super_admin_betslip" class="table table-lg table-bordered table-hover border border-dark">
                <thead class="thead-dark">
                    <tr class="">
                        <th>Member Name</th>
                        <th>Provider</th>
                        <th>Wager Id</th>
                        <th>before balance</th>
                        <th>balance</th>
                        <th>Game</th>
                        <th>bet amount</th>
                        <th>payout amount</th>
                        <th>Win/Lose</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($slips as $slip)
                    <tr>
                        <td>{{ $code }}</td>
                        <td> {{ $slip->provider_name }}</td>
                        <td>
                            <a href="" onclick="openWindowAtTopLeft({{$slip->wager_id}})">{{$slip->wager_id}}</a>
                        </td>
                        <td>{{ $slip->before_balance ?? '' }}</td>
                        <td>{{ $slip->balance ?? '' }}</td>
                        <td>
                        @php
                            $name = GameName($slip->provider_code, $slip->game);
                            @endphp
                            @if ($name)
                                {{ $name }}
                            @else
                              '-'
                            @endif
                        </td>
                        <td>{{ $slip->bet_amount ?? '' }}</td>
                        <td>{{ $slip->payout_amount ?? '' }}</td>
                        <td>{{ $slip->payout_amount - $slip->bet_amount ?? '' }}</td>
                        <td>{{ $slip->method_name }}</td>
                        <td>{{ $slip->status }}</td>
                        <td>{{ $slip->created_on ?? ''}}</td>
                        <td>{{ $slip->created_at ?? ''}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function openWindowAtTopLeft(warger) {
        var leftPosition = 0;
        var topPosition = 0;
        var windowFeatures = 'width=800,height=600,left=' + leftPosition + ',top=' + topPosition;
        window.open(`https://prod_md.9977997.com/Report/BetDetail?agentCode=E457&WagerID=${warger}`, '_blank',
            windowFeatures);
    }

    $(document).ready(function() {
        $('#data_table_super_admin_betslip').DataTable();
    })
</script>
@endsection
