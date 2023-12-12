@extends('super_admin.backend_layout.app')

@section('title', 'Transaction Logs')

@section('transaction-log-active', 'active')

@section('content')
    <div class="d-flex">
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
                        <table id="bet_slip_datatable" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User id</th>
                                    <th>Game id</th>
                                    <th>Balance</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>093322334</td>
                                    <td>dew233lee</td>
                                    <td>23,000 MMK</td>
                                    <td>31.3.2022 5:00 PM</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>093322334</td>
                                    <td>dew233lee</td>
                                    <td>23,000 MMK</td>
                                    <td>31.3.2022 5:00 PM</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>093322334</td>
                                    <td>dew233lee</td>
                                    <td>23,000 MMK</td>
                                    <td>31.3.2022 5:00 PM</td>
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
        //calendar
        var start = moment().subtract(29, 'days');
        var end = moment();

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
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        //calendar

        //refresh
        $('.reload').click(function(){
            window.location = 'player_list';
        });
    </script>
@endsection
