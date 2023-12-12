@extends('super_admin.backend_layout.app')

@section('title', 'Commisssion History')

@section('user-refer-history-active', 'active')

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
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table_super_admin" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th><th>User</th><th>Refer User</th><th>Amount</th><th>Type</th><th>Time</th><th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($refer_histories as $key=>$item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->main_user->name }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ number_format($item->amount) }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td>{{ date('g:i A', strtotime($item->created_at)) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
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
            if(urlParams.has('start_date')){
                var start = moment(urlParams.get('start_date'));
                var end = moment(urlParams.get('end_date'));
            }else{
                var start = moment().subtract(29, 'days');
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
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            //calendar

            //refresh
            $('.reload').click(function(){
                window.location = 'user_refer_history';
            });

            //by date daily filter
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                history.pushState(null, '', '?start_date='+start+'&end_date='+end)
                window.location.reload()
            });

        });

    </script>
@endsection
