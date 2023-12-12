@extends('super_admin.backend_layout.app')

@section('title', 'Cash Out History')

@section('user-active', 'active')

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
    <div class="p-2">
        <div class="form-group">
            <div class="input-group">
                <select class="form-control select2 status">
                    <option value="" @if (''==request()->status) selected @endif>All</option>
                    <option value="Approve" @if ('Approve'==request()->status) selected @endif>Approve</option>
                    <option value="Pending" @if ('Pending'==request()->status) selected @endif>Pending</option>
                    <option value="Reject" @if ('Reject'==request()->status) selected @endif>Reject</option>
                </select>
            </div>
        </div>
    </div>
    <div class="p-2">
        <div class="btn btn-sm btn-danger">
            Total Amount <span class="badge badge-light">{{ number_format($cash_outs->sum('amount')) }}</span>
        </div>
    </div>
</div><br>
    <a href="{{ url()->previous() }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="datatable_1" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Payment</th>
                                    <th>Account Number</th>
                                    <th>Account Name</th>
                                    <th>Status</th>
                                    <th>Old Amount</th>
                                    <th>Amount</th>
                                    <th>Total Amount</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cash_outs as $i=>$item)
                                    <tr>
                                        <td class="d-none" id="userId">{{$item->user_id}}</td>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $item->user->name ?? '-' }}</td>
                                        <td>{{ $item->payment_method->name ?? '-' }}</td>
                                        <td>{{ $item->user_phone }}</td>
                                        <td>{{ $item->account_name }}</td>
                                        <td>
                                            <span class="badge
                                                @if($item->status == "Approve")
                                                    badge-success
                                                @elseif($item->status == "Reject")
                                                    badge-danger
                                                @else
                                                    badge-warning
                                                @endif
                                            ">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($item->old_amount ?? '0.00') }} MMK</td>
                                        <td><span class="text-danger font-weight-bold">{{ number_format($item->amount ?? '0.00') }} MMK</span></td>
                                        <td>{{ number_format($item->old_amount - $item->amount) }} MMK</td>
                                        <td>{{ $item->created_at->format('d.m.Y h:i A') }}</td>
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
                $user_id = $('#userId').text()
                window.location = $user_id;
            });

            //by date daily filter
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                var status = $('.status').val();
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                var payment = $('.payment').val();
                history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                    status + '&payment=' + payment)
                window.location.reload()
            });

            //Filter
            $('.status').change(function() {
                var status = $('.status').val();
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                    status)
                window.location.reload()
            });

            $('.payment').change(function() {
                var status = $('.status').val();
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                var payment = $('.payment').val();
                history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                    status + '&payment=' + payment)
                window.location.reload()
            });
        });
    </script>
@endsection
