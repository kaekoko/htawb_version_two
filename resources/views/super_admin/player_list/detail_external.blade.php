@extends('super_admin.backend_layout.external')

@section('title', $user_code . "'s Histories")

@section('player-list-active', 'active')

@section('content')
    <div class="d-flex flex-wrap">
        <div class="p-2">
            <button onclick="history.back()">Go Back</button>
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
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="nav-icon fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Turnover</span>
                    <span class="info-box-number">{{ $player_detail->sum('total_turnover') }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-dark elevation-1"><i class="nav-icon fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Payout</span>
                    <span class="info-box-number">{{ $player_detail->sum('total_payout') }}</span>
                </div>
            </div>
        </div>
        @if ($player_detail->sum('total_winloss') > 0)
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Win</span>
                        <span class="info-box-number">{{ $player_detail->sum('total_winloss') }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Lose</span>
                        <span class="info-box-number">{{ $player_detail->sum('total_winloss') }}</span>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="nav-icon fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Commission</span>
                    <span class="info-box-number">{{ $player_detail->sum('total_commission') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>User ID: {{ $user_code }}</h5>
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
                                    <th>Date</th>
                                    <th>TurnOver</th>
                                    <th>Payout</th>
                                    <th>Bet</th>
                                    <th>Commission</th>
                                    <th>W/L</th>
                                    <th>P/L</th>
                                    <th style="display: none"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($player_detail as $date => $detail)
                                    <tr style="cursor: pointer">
                                        <td>{{ $date }}</td>
                                        <td>{{ $detail['total_turnover'] }}</td>
                                        <td>{{ $detail['total_payout'] }}</td>
                                        <td>{{ $detail['total_bet'] }}</td>
                                        <td>{{ $detail['total_commission'] }}</td>
                                        <td>
                                            @if ($detail['total_winloss'] < 0)
                                                <span class="badge badge-danger">{{ $detail['total_winloss'] }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $detail['total_winloss'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($detail['total_profitloss'] < 0)
                                                <span class="badge badge-danger">{{ $detail['total_profitloss'] }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $detail['total_profitloss'] }}</span>
                                            @endif
                                        </td>
                                        <td style="display: none;">{{ $detail['provider_by_date'] }}</td>
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
            var url_name = "{{ $user_code }}";
            $('.reload').click(function() {
                window.location = `/gamereport/player_detail?user_code=${url_name}`;
            });

            //by date daily filter
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                $('.daily_static').empty();
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                history.pushState(null, '', '?user_code=' + url_name + '&start_date=' + start +
                    '&end_date=' + end)
                window.location.reload()
            });

        });

        // Datatable Collpase
        function format(data) {
            // How to show in collpase area?
            return (
                `
                <table class="table table-xs bg-secondary">
                    <thead>
                        <tr>
                            <th>Provider</th>
                            <th>Turnover</th>
                            <th>Payout</th>
                            <th>Bet</th>
                            <th>Commision</th>
                            <th>W/L</th>
                            <th>P/L</th>
                        </tr>
                    </thead>
                    <tbody>
                       ${Object.keys(data).length >0 ? (
                        Object.keys(data).map((provider) => (
                        `               <tr>
                                                                                                        <td>
                                                                                                            <a href="/transaction_reports_external?date=${data[provider]?.date}&provider=${provider}&username={{ $user_code }}">${provider}</a>
                                                                                                        </td>  
                                                                                                        <td>${data[provider]?.total_turnover}</td>  
                                                                                                        <td>${data[provider]?.total_payout}</td>  
                                                                                                        <td>${data[provider]?.total_bet}</td>  
                                                                                                        <td>${data[provider]?.total_commission}</td>    
                                                                                                        <td>
                                                                                                            ${
                                                                                                                data[provider]?.total_winloss < 0 ?
                                                                                                                    `
                                                                        <span class="badge badge-danger"> ${data[provider]?.total_winloss}</span>
                                                                    `
                                                                                                                : `<span class="badge badge-success"> ${data[provider]?.total_winloss}</span>
                                                                `}
                                                                                                        </td>    
                                                                                                        <td>
                                                                                                            ${
                                                                                                                data[provider]?.total_profitloss < 0 ?
                                                                                                                    `
                                                                        <span class="badge badge-danger"> ${data[provider]?.total_profitloss}</span>
                                                                    `
                                                                                                                : `<span class="badge badge-success"> ${data[provider]?.total_profitloss}</span>
                                                                `}
                                                                                                        </td>                                                         </tr>  
                                                                                `
                       ))
                       ): (
                        `<tr><td colspan="6">No Data!</td></tr>`
                       )}
                    </tbody>
                </table>
                `
            );
        }

        $(document).ready(function() {
            let dt = $('#data_table_super_admin').DataTable();
            // Array to track the ids of the details displayed rows
            var detailRows = [];
            $('#data_table_super_admin tbody').on('click', 'tr td', function() {
                var tr = $(this).closest('tr');
                var row = dt.row(tr);
                var idx = detailRows.indexOf(tr.attr('id'));

                if (row.child.isShown()) {
                    tr.removeClass('details');
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice(idx, 1);
                } else {
                    tr.addClass('details');
                    row.child(format(JSON.parse(row.data()[7])).replaceAll(",", "")).show();
                    // Add to the 'open' array
                    if (idx === -1) {
                        detailRows.push(tr.attr('id'));
                    }
                }
            });

            // On each draw, loop over the `detailRows` array and show any child rows
            dt.on('draw', function() {
                detailRows.forEach(function(id, i) {
                    $('#' + id + ' td').trigger('click');
                });
            });
        });
    </script>
@endsection
