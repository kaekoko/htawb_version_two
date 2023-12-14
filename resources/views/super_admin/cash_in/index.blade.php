@extends('super_admin.backend_layout.app')

@section('title', 'CashIn')

@section('cashin-active', 'active')

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
        <input type="time" class="form-control" name="start_time" id="start_time" value="{{request()->start_time}}">
    </div>

    <div class="p-2">
        <input type="time" class="form-control" name="end_time" id="end_time" value="{{request()->end_time}}">
    </div>

    <div class="p-2">
        <div class="form-group">
            <div class="input-group">
                <select class="form-control select2 " id="side_status">
                    <option value="" >All</option>
                    <option value="HtawB"  @if ('myvip'==request()->side) selected @endif>MYvip</option>
                    <option value="icasino" @if ('icasino'==request()->side) selected @endif>Icasino</option>
                </select>
            </div>
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
        <div class="form-group">
            <div class="input-group">
                <select class="form-control select2 payment">
                    <option value=" ">All</option>
                    @foreach ($payments as $item)
                    <option value="{{ $item->payment_id }}" @if ($item->payment_id == request()->payment) selected @endif>
                        {{ $item->payment_method->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="p-2">
        <form action="{{ route('cashinsearch') }}" method="GET" class="row">
            <div> <input type="text" name="search" required placeholder={{ Request::get('search') ?? 'Enter-Keyword' }} class="form-control form-control-sm"></div>
            <button type="submit" class="btn btn-outline-dark btn-sm">Search</button>
        </form>
    </div>

    <div class="p-2">
        <div class="btn btn-sm btn-success">
            Total Amount <span class="badge badge-light">{{ number_format($total) }}</span>
        </div>
    </div>
</div><br>
<div class="card card-dark card-outline">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <!-- <table id="data_table_super_admin" class="table table-striped table-sm"> -->
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#*</th>
                                
                                <th>User Name</th>
                                <th>site Name</th>
                                <th>Payment</th>
                                <th>Holder Phone</th>
                                <th>Transaction ID</th>
                                <th>Status</th>
                                <th>Old Amount</th>
                                <th>Amount</th>
                                <th>Total Amount</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cash_ins as $key => $cash_in)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a href="{{ url('super_admin/useronly',$cash_in->user->id) }}">{{ $cash_in->user->name ?? '-' }}</a></td>
                                <td>{{ $cash_in->side === null ? 'myvip' : 'i-casino' }}</td>
                                <td class="@if($cash_in->payment_method->id == 5) text-primary  @endif">{{ $cash_in->payment_method->name }}</td>
                                <td>{{ $cash_in->holder_phone ?? '-' }}</td>
                                <td>{{ $cash_in->transaction_id ?? ($cash_in->voucher_code ?? '-') }}</td>
                                <td>
                                    <small class="badge
                                            @if ($cash_in->status == 'Approve') badge-success
                                            @elseif($cash_in->status == 'Reject')
                                                badge-danger
                                            @else
                                                badge-warning @endif
                                        ">
                                        {{ $cash_in->status }}
                                    </small>
                                </td>
                                <td>{{ number_format($cash_in->old_amount ?? '0.00') }} Ks</td>
                                <td>
                                    <span class="text-success font-weight-bold">
                                        @if (empty(!$cash_in->payment_method->exchange_rate))
                                        {{ $cash_in->amount }} ฿
                                        <small class="badge badge-warning mb-1">
                                            {{ $cash_in->amount * $cash_in->payment_method->exchange_rate }}
                                            Ks
                                        </small>
                                        @else
                                        {{ number_format($cash_in->amount ?? '0.00') }} Ks
                                        @endif
                                    </span>
                                    <br>
                                    @if ($cash_in->promo_id)
                                    <small class="badge badge-warning">{{ $cash_in->promo_percent . ' %' }}
                                        promo</small>
                                    @endif
                                </td>
                                <td>
                                    @if (empty(!$cash_in->payment_method->exchange_rate))
                                    {{ $cash_in->old_amount + $cash_in->amount * $cash_in->payment_method->exchange_rate }}
                                    Ks
                                    @else
                                    {{ number_format($cash_in->old_amount + $cash_in->amount) }} Ks
                                    @endif
                                </td>
                                <td>{{ $cash_in->created_at->format('d.m.Y h:i A') }}</td>
                                <td>
                                    @if (!empty($cash_in->payment_method->exchange_rate))
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#screenshoot{{ $cash_in->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="15px" viewBox="0 0 24 24" width="15px" fill="#ffffff">
                                            <g>
                                                <rect fill="none" height="24" width="24" />
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M20,3H4C2.89,3,2,3.89,2,5v12c0,1.1,0.89,2,2,2h4v2h8v-2h4c1.1,0,2-0.9,2-2V5C22,3.89,21.1,3,20,3z M20,17H4V5h16V17z" />
                                                    <polygon points="6.5,7.5 9,7.5 9,6 5,6 5,10 6.5,10" />
                                                    <polygon points="19,12 17.5,12 17.5,14.5 15,14.5 15,16 19,16" />
                                                </g>
                                            </g>
                                        </svg>
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-success" @if ($cash_in->status == 'Approve' || $cash_in->status == 'Reject') disabled @endif data-toggle="modal"
                                        data-target="#approveModal{{ $cash_in->id }}"><i class="fa fa-check" aria-hidden="true"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" @if ($cash_in->status == 'Approve' || $cash_in->status == 'Reject') disabled @endif data-toggle="modal"
                                        data-target="#rejectModal{{ $cash_in->id }}"><i class="fa fa-times" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                            <!-- Screenshot Modal -->
                            <div class="modal fade" id="screenshoot{{ $cash_in->id }}" tabindex="-1" aria-labelledby="screenshootLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="screenshootLabel">Payment Screenshot</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="{{ asset($cash_in->cash_in_photo) }}" style="width: 100%; height: 500px; object-fit:contain;" alt="...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $cash_in->id }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveModalLabel">Approve Confirm</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('cashin.confirm', $cash_in->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $cash_in->user_id }}">
                                                @if (empty(!$cash_in->payment_method->exchange_rate))
                                                <input type="hidden" name="amount" value="{{ $cash_in->amount * $cash_in->payment_method->exchange_rate }}">
                                                @else
                                                <input type="hidden" name="amount" value="{{ $cash_in->amount }}">
                                                @endif
                                                <span class="text-danger"><i class="fas fa-exclamation-circle"></i>
                                                    ဤလုပ်ဆောင်ချက်သည် နောက်ပြန်ဆုတ်လို့မရပါ။</span>
                                                <div class="form-group mt-3">
                                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="cashin_password" class="form-control" required placeholder="Enter Admin Password">
                                                </div>
                                                <div class="float-right">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Confirm</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $cash_in->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel">Reject Confirm</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('cashin.reject', $cash_in->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $cash_in->user_id }}">
                                                <span class="text-danger"><i class="fas fa-exclamation-circle"></i> ဤလုပ်ဆောင်ချက်သည်
                                                    နောက်ပြန်ဆုတ်လို့မရပါ။</span>
                                                <div class="form-group mt-3">
                                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="reject_password" class="form-control" required placeholder="Enter Admin Password">
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label class="form-label">Message <span class="text-muted">(Optional)</span></label>
                                                    <textarea name="message" cols="30" rows="5" class="form-control" placeholder="Enter Reason Message"></textarea>
                                                </div>
                                                <div class="float-right">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Confirm</button>
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
                {!! $cash_ins->appends(Request::all())->links() !!}

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
            window.location = 'cash-in';
        });

        //by date daily filter
        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var promo = $('.promo').val();
            var payment = $('.payment').val();
           var side = $('#side_status').val();
           var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side + '&start_time=' + start_time + '&end_time=' + end_time)
            window.location.reload()
        });

        //Filter
        $('.status').change(function() {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var promo = $('.promo').val();
            var payment = $('.payment').val();
            var side = $('#side_status').val();
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side + '&start_time=' + start_time + '&end_time=' + end_time)
            window.location.reload()
        });

        //Promo Filter
        $('.promo_add').click(function() {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var promo = 'promo';
            var payment = $('.payment').val();
            var side = $('#side_status').val();
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side + '&start_time=' + start_time + '&end_time=' + end_time)
            window.location.reload()
        });

        $('.promo_remove').click(function() {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var promo = '';
            var payment = $('.payment').val();
            var side = $('#side_status').val();
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side + '&start_time=' + start_time + '&end_time=' + end_time)
            window.location.reload()
        });

        $('.payment').change(function() {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var promo = $('.promo').val();
            var payment = $('.payment').val();
            var side = $('#side_status').val();
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side + '&start_time=' + start_time + '&end_time=' + end_time)
            window.location.reload()
        });

        $('#side_status').change(function() {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var promo = $('.promo').val();
            var payment = $('.payment').val();
            var side = $('#side_status').val();
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side + '&start_time=' + start_time + '&end_time=' + end_time)
            window.location.reload()
        });


        $('#side_status').change(function() {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var promo = $('.promo').val();
            var payment = $('.payment').val();
            var side = $('#side_status').val();
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side + '&start_time=' + start_time + '&end_time=' + end_time)
            window.location.reload()
        });

        $('#end_time').change(function() {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var promo = $('.promo').val();
            var payment = $('.payment').val();
            var side = $('#side_status').val();
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side + '&start_time=' + start_time + '&end_time=' + end_time)
                window.location.reload()

        });

        $('#start_time').change(function() {
            var status = $('.status').val();
            var startDate = $('#reportrange').data('daterangepicker').startDate;
            var endDate = $('#reportrange').data('daterangepicker').endDate;
            var start = startDate.format('YYYY-MM-DD');
            var end = endDate.format('YYYY-MM-DD');
            var promo = $('.promo').val();
            var payment = $('.payment').val();
            var side = $('#side_status').val();
            var start_time = $('#start_time').val();
            var end_time = $('#end_time').val();
            history.pushState(null, '', '?start_date=' + start + '&end_date=' + end + '&status=' +
                status + '&payment=' + payment + '&side=' + side + '&start_time=' + start_time + '&end_time=' + end_time)
            window.location.reload()
        });

    });
</script>
@endsection
