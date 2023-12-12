@extends('super_admin.backend_layout.app')

@section('title', 'Activity Logs')

@section('activity-log-active', 'active')

@section('content')
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
        <div class="p-2">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="nav-icon fas fa-users-cog"></i>
                        </span>
                    </div>
                    <select class="form-control select2 admin_id">
                        <option value="">All</option>
                        @foreach ($admin_staffs as $item)
                            <option value="{{ $item->id }}"
                                @if ($item->id ==  request()->admin_id)
                                    selected
                                @endif >
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table_super_admin" class="table table-striped table-sm data_table_report">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Log Name</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activity_logs as $i=>$item)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $item->super_admin->name }}</td>
                                        <td>{{ $item->super_admin->admin_role->name }}</td>
                                        <td>{{ $item->log_name }}</td>
                                        <td>{{ date('h:i A', strtotime($item->created_at)) }}</td>
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
                }
            }, cb);

            cb(start, end);

            //calendar

            //refresh
            $('.reload').click(function(){
                window.location = '/super_admin/activity_log';
            });

            if(urlParams.has('start_date')){
                start = urlParams.get('start_date');
                end = urlParams.get('end_date');
            }else{
                start = start.format('YYYY-MM-DD');
                end = end.format('YYYY-MM-DD');
            }

            //by date daily filter
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                $('.daily_static').empty();
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                var admin_id = $('.admin_id').val();
                history.pushState(null, '', '?start_date='+start+'&end_date='+end+'&admin_id='+admin_id)
                window.location.reload()
            });

            //admin filter
            $('.admin_id').change(function() {
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                var admin_id = $('.admin_id').val();
                history.pushState(null, '', '?start_date='+start+'&end_date='+end+'&admin_id='+admin_id)
                window.location.reload()
            });

        });

    </script>
@endsection
