@extends('super_admin.backend_layout.app')

@section('title', 'CashOut')

@section('cashout-active', 'active')

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
                        <option value="" @if ('' == request()->status) selected @endif>All</option>
                        <option value="Approve" @if ('Approve' == request()->status) selected @endif>Approve</option>
                        <option value="Pending" @if ('Pending' == request()->status) selected @endif>Pending</option>
                        <option value="Reject" @if ('Reject' == request()->status) selected @endif>Reject</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-2">
            <div class="form-group">
                <div class="input-group">
                    <select class="form-control select2 payment">
                        <option value=" ">All</option>
                        @foreach ($payments as $item)
                            <option value="{{ $item->payment_id }}" @if ($item->payment_id == request()->payment) selected @endif>
                                {{ $item->payment_method->name }}</option>
                        @endforeach
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
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive" >
                        <table id="data_table_super_admin" style='height:200px;' class="table table-bordered table-striped table-sm">
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
                                    <th></th>
                                    <th>check</th>
                                    
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cash_outs as $key => $cash_out)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $cash_out->user->name ?? '-' }}</td>
                                        
                                        <td>{{ $cash_out->payment_method->name }}-{{ $cash_out->user_phone ?? '-' }}</td>
                                        <td>{{ $cash_out->user->phone ?? '-' }}</td>
                                        <td>{{ $cash_out->account_name ?? '-' }}</td>
                                        <td>
                                            <small
                                                class="badge
                                                @if ($cash_out->status == 'Approve') badge-success
                                                @elseif($cash_out->status == 'Reject')
                                                    badge-danger
                                                @else
                                                    badge-warning @endif
                                            ">
                                                {{ $cash_out->status }}
                                            </small>
                                        </td>
                                        <td>{{ number_format($cash_out->old_amount ?? '0.00') }} Ks</td>
                                        <td>
                                            <span class="text-danger font-weight-bold">
                                                @if (empty(!$cash_out->payment_method->exchange_rate))
                                                    {{ number_format($cash_out->amount ?? '0.00') }} Ks
                                                    <small class="badge badge-warning">
                                                        {{ $cash_out->amount / $cash_out->payment_method->exchange_rate }}
                                                        ฿
                                                    </small>
                                                @else
                                                    {{ number_format($cash_out->amount ?? '0.00') }} Ks
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ number_format($cash_out->old_amount - $cash_out->amount) }} Ks</td>
                                        <td>{{ $cash_out->created_at->format('d.m.Y h:i A') }}</td>
                                        <td><a href="{{ url('super_admin/user/all_report/' . $cash_out->user->id ) }}" title="Report"><button class="btn btn-danger btn-sm"><i class="fa-brands fa-stack-exchange"></i></button></a></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-tasks"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                   
                                                    <a class="dropdown-item"
                                                        href="{{ url('/super_admin/cash_in_history/' . $cash_out->user->id) }}">Cash
                                                        In History</a>
                                                    <a class="dropdown-item"
                                                        href="{{ url('/super_admin/cash_out_history/' . $cash_out->user->id) }}">Cash
                                                        Out History</a>
                                                        <a class="dropdown-item py-1" href="{{ url('/super_admin/user/1d_betslips_only/' .  $cash_out->user->id) }}">1D Bet History
                                                        </a>
                                                        <a class="dropdown-item py-1" href="{{ url('/super_admin/user/2d_betslips_only/' .  $cash_out->user->id) }}">2D Bet History
                                                        </a>
                                                        <a class="dropdown-item py-1" href="{{ url('/super_admin/user/crypto1d_betslips_only/' .  $cash_out->user->id) }}">Crypto 1D Bet History
                                                        </a>
                                                        <a class="dropdown-item py-1" href="{{ url('/super_admin/user/crypto2d_betslips_only/' .  $cash_out->user->id) }}">Crypto 2D Bet History
                                                        </a>
                                                        <a class="dropdown-item py-1" href="{{ url('/super_admin/user/3d_betslips_only/' .  $cash_out->user->id) }}">3D Bet History
                                                        </a>  

                                                        <a class="dropdown-item" href="{{ url('/super_admin/only/betslip/histroy',$cash_out->user->user_code) }}">Game Bet new</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success"
                                                @if ($cash_out->status == 'Approve' || $cash_out->status == 'Reject') disabled @endif data-toggle="modal"
                                                data-target="#approveModal{{ $cash_out->id }}"><i class="fa fa-check"
                                                    aria-hidden="true"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                @if ($cash_out->status == 'Approve' || $cash_out->status == 'Reject') disabled @endif data-toggle="modal"
                                                data-target="#rejectModal{{ $cash_out->id }}"><i class="fa fa-times"
                                                    aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $cash_out->id }}" tabindex="-1"
                                        aria-labelledby="approveModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="approveModalLabel">Approve Confirm</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('cashout.confirm', $cash_out->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <span class="text-danger"><i class="fas fa-exclamation-circle"></i>
                                                            ဤလုပ်ဆောင်ချက်သည် နောက်ပြန်ဆုတ်လို့မရပါ။</span>
                                                        <div class="form-group mt-3">
                                                            <label class="form-label">Password <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="password" name="cashout_password"
                                                                class="form-control" required
                                                                placeholder="Enter Admin Password">
                                                        </div>
                                                        <div class="float-right">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $cash_out->id }}" tabindex="-1"
                                        aria-labelledby="rejectModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel">Reject Confirm</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('cashout.reject', $cash_out->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="user_id"
                                                            value="{{ $cash_out->user_id }}">
                                                        <input type="hidden" name="amount"
                                                            value="{{ $cash_out->amount }}">
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-circle"></i> ဤလုပ်ဆောင်ချက်သည်
                                                            နောက်ပြန်ဆုတ်လို့မရပါ။</span>
                                                        <div class="form-group mt-3">
                                                            <label class="form-label">Password <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="password" name="cashout_reject_password"
                                                                class="form-control" required
                                                                placeholder="Enter Admin Password">
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            <label class="form-label">Message <span
                                                                    class="text-muted">(Optional)</span></label>
                                                            <textarea name="message" cols="30" rows="5" class="form-control" placeholder="Enter Reason Message"></textarea>
                                                        </div>
                                                        <div class="float-right">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Confirm</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                window.location = 'cash-out';
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
                var payment = $('.payment').val();
                history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                    status + '&payment=' + payment)
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

            $('#side_status').change(function() {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var payment = $('.payment').val();
            var side = $('#side_status').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side)
            window.location.reload()
        });
        });
    </script>
@endsection
