@extends('super_admin.backend_layout.app')

@section('title', 'Win Lose')

@section('win-lose-active', 'active')

@section('content')
    {{-- <div class="d-flex flex-wrap">
        <div class="p-2">
            <i class="fas fa-chess-queen"></i>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">0 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">1 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">2 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">3 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">4 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">5 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">6 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">7 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">8 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">9 Gaming</a>
        </div>
    </div>
    <div class="d-flex flex-wrap">
        <div class="p-2">
            <i class="fas fa-chess"></i>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">0 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">1 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">2 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">3 Gaming</a>
        </div>
    </div>
    <div class="d-flex flex-wrap">
        <div class="p-2">
            <i class="fas fa-futbol"></i>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">0 Gaming</a>
        </div>
        <div class="p-2 winlose_provider">
            <a href="">1 Gaming</a>
        </div>
    </div> --}}
    <div class="d-flex flex-wrap">
        <div class="p-2">
            <button class="btn btn-info btn-sm reload" title="refresh"><i class="nav-icon fas fa-retweet"></i></button>
        </div>
        <div class="p-2">
            <div id="reportrange">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down"></i>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-trophy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Bet</span>
                    <span class="info-box-number">{{ $bet_histories->sum('bet') }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-trophy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Payout</span>
                    <span class="info-box-number">{{ $bet_histories->sum('payout') }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-trophy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Turnover</span>
                    <span class="info-box-number">{{ $bet_histories->sum('turnover') }}</span>
                </div>
            </div>
        </div>
        <div class="clearfix hidden-md-up"></div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-trophy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Win/Loss</span>
                    @php
                        $total_winloss = $bet_histories->sum('payout') - $bet_histories->sum('bet');
                    @endphp
                    @if ($total_winloss < 0)
                        <span class="info-box-number text-danger">{{ $total_winloss }}</span>
                    @else
                        <span class="info-box-number text-success">{{ $total_winloss }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="clearfix hidden-md-up"></div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-trophy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Profit/Loss</span>
                    @php
                        $total_profitloss = $bet_histories->sum('payout') - $bet_histories->sum('bet');
                    @endphp
                    @if ($total_profitloss < 0)
                        <span class="info-box-number text-danger">{{ $total_profitloss }}</span>
                    @else
                        <span class="info-box-number text-success">{{ $total_profitloss }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table_super_admin" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Username</th>
                                    <th>User ID</th>
                                    <th>Bet</th>
                                    <th>Payout</th>
                                    <th>TurnOver</th>
                                    <th>W/L</th>
                                    <th>P/L</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user_bettings as $key => $history)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a
                                                href="{{ url('game/win_lose_user/' . $history->user->id . '/betslips?start_date=' . request()->query('start_date') . '&end_date=' . request()->query('end_date')) }}">
                                                {{ $history->user->name }}
                                            </a>
                                            {{-- username --}}
                                        </td>
                                        <td>
                                            {{ $history->username }}
                                            {{-- user-code --}}
                                        </td>
                                        <td>{{ $history->total_bet }}</td>
                                        <td>{{ $history->total_payout }}</td>
                                        <td>{{ $history->total_turnover }}</td>
                                        <td>
                                            @if ($history->total_winloss < 0)
                                                <span class="badge badge-danger">{{ $history->total_winloss }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $history->total_winloss }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($history->total_profitloss < 0)
                                                <span class="badge badge-danger">{{ $history->total_profitloss }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $history->total_profitloss }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Total Bet</th>
                                    <th>Total Payout</th>
                                    <th>Total Turnover</th>
                                    <th>Total Win/Loss</th>
                                    <th>Total Profit/Loss</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $bet_histories->sum('bet') }}</td>
                                    <td>{{ $bet_histories->sum('payout') }}</td>
                                    <td>{{ $bet_histories->sum('turnover') }}</td>
                                    <td>
                                        @php
                                            $total_winloss = $bet_histories->sum('payout') - $bet_histories->sum('bet');
                                        @endphp
                                        @if ($total_winloss < 0)
                                            <span class="info-box-number text-danger">{{ $total_winloss }}</span>
                                        @else
                                            <span class="info-box-number text-success">{{ $total_winloss }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $total_profitloss = $bet_histories->sum('payout') - $bet_histories->sum('bet');
                                        @endphp
                                        @if ($total_profitloss < 0)
                                            <span class="info-box-number text-danger">{{ $total_profitloss }}</span>
                                        @else
                                            <span class="info-box-number text-success">{{ $total_profitloss }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {

            var urlParams = new URLSearchParams(window.location.search);
            //calendar
            if (urlParams.has('start_date')) {
                var start = moment(urlParams.get('start_date'));
                var end = moment(urlParams.get('end_date'));
            } else {
                var start = moment();
                var end = moment();
            }

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            //calendar

            //refresh
            $('.reload').click(function() {
                window.location = 'win_lose';
            });

            if (urlParams.has('start_date')) {
                start = urlParams.get('start_date');
                end = urlParams.get('end_date');
            } else {
                start = start.format('YYYY-MM-DD');
                end = end.format('YYYY-MM-DD');
            }

            //by date daily filter
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                history.pushState(null, '', '?start_date=' + start + '&end_date=' + end)
                window.location.reload()
            });

        });
    </script>
@endsection
