@extends('super_admin.backend_layout.app')

@section('title', $user->name . "'s Game Transfer Logs")

@section('player-list-active', 'active')

@section('content')
    <div class="d-flex flex-wrap">
        <div class="p-2">
            <a href="{{ url('game/player_list') }}" class="btn btn-warning btn-sm mb-3">Back</a>
        </div>
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
                        <table class="table table-striped table-sm player-list-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reference ID</th>
                                    <th>Provider Code</th>
                                    <th>Type</th>
                                    <th>Message</th>
                                    <th>Error Code</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
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
                let url = "{{ '/game/transfer_logs/' . $user->id }}"
                window.location = url;
            });

            //by date daily filter
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                $('.daily_static').empty();
                history.pushState(null, '', '?start_date=' + start + '&end_date=' + end)
                window.location.reload()
            });

            // Datatable
            let dataFetchUrl = "{{ url('game/transfer_logs/' . $user->id) }}";
            let start_date = "{{ request()->start_date }}";
            let end_date = "{{ request()->end_date }}";
            let dtOverrideGlobals = {
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: `${dataFetchUrl}?start_date=${start_date}&end_date=${end_date}`,
                order: [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'index'
                    },
                    {
                        data: 'referenceid',
                        name: 'referenceid'
                    },
                    {
                        data: 'p_code',
                        name: 'p_code'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'message',
                        name: 'message'
                    },
                    {
                        data: "error_code",
                        name: "error_code"
                    },
                    {
                        data: "amount",
                        name: "amount"
                    },
                    {
                        data: "created_at",
                        name: "created_at"
                    }
                ],
                pageLength: 50,
            };
            $('.player-list-data-table').DataTable(dtOverrideGlobals);
        });
    </script>
@endsection
