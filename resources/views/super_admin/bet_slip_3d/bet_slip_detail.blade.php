@extends('super_admin.backend_layout.app')

@section('title', "3D Bet Slips # $bet_slip_detail->id Detail")

@section('bet-slip-3d-active', 'active')

@section('content')
    <a href="{{ url()->previous() }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left"
                aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline bet_slip">
        <div class="card-body">
            <table class="table table-bordered table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>3D Open Date</th>
                        <th>Category</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $bet_slip_detail->user->name }}</td>
                        <td>{{ date('d-m-Y', strtotime($bet_slip_detail->bet_date)) }}</td>
                        <td>{{ $bet_slip_detail->bettings_3d[0]->category->name ?? '-' }}</td>
                        <td>{!! date('d-M-Y H:i:s', strtotime($bet_slip_detail->created_at)) !!}</td>
                    </tr>
                </tbody>
            </table>

            <br>
            <table class="table table-bordered table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th>Bet Number</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bet_slip_detail->bettings_3d as $bet)
                        @php $bet_win = $bet->win @endphp
                        <tr>
                            <td>{{ $bet->bet_number }}</td>
                            <td>
                                {!! $bet->win == 1 ? "<small class='badge badge-success'>Win</small>" : '' !!}
                                {!! $bet->tot == 1 ? "<small class='badge badge-success'>Tot</small>" : '' !!}
                            </td>
                            <td>
                                {{ number_format($bet->amount) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">
                            <h6 class="float-right">Total</h6>
                        </td>
                        <td>
                            @php
                                $total = 0;
                                foreach ($bet_slip_detail->bettings_3d as $bet) {
                                    $total += $bet->amount;
                                }
                            @endphp
                            {{ number_format($total) }} MMK
                        </td>
                    </tr>
                </tbody>
            </table>

            @php
                $check_bet_win = 0;
                foreach ($bet_slip_detail->bettings_3d as $bet) {
                    if ($bet->win == 1) {
                        $check_bet_win = 1;
                    }
                }
            @endphp
            @if ($check_bet_win == 1)
                <br>
                <table class="table table-bordered table-sm table-responsive-sm">
                    <thead>
                        <tr>
                            <th>Win Bet Number</th>
                            <th>Odd</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bet_slip_detail->bettings_3d as $bet)
                            @if ($bet->win == 1)
                                <tr>
                                    <td>{{ $bet->bet_number }}</td>
                                    @if ($bet->tot == 0)
                                        <td>{{ $bet->odd }}</td>
                                    @else
                                        <td>{{ $bet->tot_odd }}</td>
                                    @endif
                                    <td>{{ number_format($bet->amount) }}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2">
                                <h6 class="float-right">Total</h6>
                            </td>
                            <td>
                                {{ number_format($bet_slip_detail->reward_amount) }} MMK
                                <b class="text-success">{{ $bet_slip_detail->reward == 1 ? '(Payment Complete)' : '' }}</b>
                                {{-- @if ($total_win_amount > 0)
                                    @if ($bet_slip_detail->reward == 0)
                                        <form id="postBtn{{ $bet_slip_detail->id }}" method="post" action="{{ url('super_admin/bet_slip/pay_reward/'.$bet_slip_detail->id.'/'.$total_win_amount) }}" style="display: inline;">
                                            @csrf
                                        </form>
                                        <button class="btn btn-warning btn-sm confirmBtn" data-id={{ $bet_slip_detail->id }}>Pay Reward</button>
                                    @endif
                                @endif --}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif

        </div>
    </div>
@endsection
