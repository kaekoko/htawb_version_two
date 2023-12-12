@extends('super_admin.backend_layout.app')

@section('title', 'Transfer Main Wallet To Game Wallet')

@section('transfer-in-active', 'active')

@section('content')
    <div class="row">
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
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table_super_admin" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Phone Number</th>
                                    <th>Game id</th>
                                    <th>Main Balance</th>
                                    <th>Game Balance</th>
                                    <th>Transfer</th>
                                    <th>Error Code</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transfer_ins as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->phone }}</td>
                                        <td>{{ $item->user->user_code }}</td>
                                        <td>{{ number_format($item->balance) }} </td>
                                        <td>{{ $item->game_balance }}</td>
                                        <td><small
                                                class="badge badge-success">{{ number_format($item->transfer_balance) }}</small>
                                        </td>
                                        <td>{{ $item->error_code }}</td>
                                        <td>{!! date('g:i A', strtotime($item->created_at)) !!}</td>
                                        <td>{!! date('d-M-Y', strtotime($item->created_at)) !!}</td>
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
                window.location = 'transfer_in';
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
