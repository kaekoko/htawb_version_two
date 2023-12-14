@extends('super_admin.backend_layout.app')

@section('title', 'Winner History')

@section('winner-history', 'active')

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
                <select class="form-control select2 " id="side_status">
                    <option value="" >All</option>
                    <option value="HtawB"  @if ('myvip'==request()->side_stats) selected @endif>MYvip</option>
                    <option value="icasino" @if ('icasino'==request()->side_stats) selected @endif>Icasino</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="p-2">
        <div class="form-group">
            <div class="input-group">
                <select class="form-control select2 type">
                    <option value="2D"  @if ( '2D' == $type || $type == null) selected @endif>2d winner</option>
                    <option value="1D" @if ( '1D' == $type) selected @endif>1d Winner</option>
                    <option value="3D" @if ( '3D' == $type) selected @endif>3d Winner</option>
                    <option value="C2D" @if ( 'C2D' == $type) selected @endif>Crypton Winner</option>
                    <option value="C1D" @if ( 'C1D' == $type) selected @endif>Crypton 1d Winner</option>
                </select>
            </div>
        </div>
    </div>

    <div class="p-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                    <i class="nav-icon fas fa-clock"></i>
                    </span>
                </div>
                <select name="category_id" class="form-control select2 section">
                    <option value="">ALL SECTION</option>
                    @foreach ($sections as $item)
                        <option  value="{{ \Carbon\Carbon::createFromFormat('H:i:s',$item->time_section)->format('h:i A') }}"
                            @if (\Carbon\Carbon::createFromFormat('H:i:s',$item->time_section)->format('h:i A') ==  request()->section)
                                selected
                            @endif >
                            {{ \Carbon\Carbon::createFromFormat('H:i:s',$item->time_section)->format('h:i A') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

</div><br>
<div class="card card-dark card-outline">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="data_table_super_admin" class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>SITE</th>
                                @if($type=='3D')
                                  <th>Date 3D</th>
                                @endif
                                <th>Section</th>
                               
                                <th>Reward Amount</th>
                                <th>Total amount</th>
                                <th>Total Bet</th>
                                <th>date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($winners as $key=>$winner)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $winner->user->name ?? '-' }}</td>
                                    <td>{{ $winner->user->side == null ? 'myvip' : 'icasino' }}</td>
                                    @if($type=='3D')
                                      <th>{{$winner->bet_date}}</th>
                                     @endif
                                     <td>{{ $winner->section ?? '-' }}</td>
                                 
                                    <td>{{ $winner->reward_amount ?? '-' }}</td>
                                    <td>{{ $winner->total_amount ?? '-' }}</td>
                                    <td>{{ $winner->total_bet ?? '-' }}</td>
                                    
                                    <td>{{ $winner->date ?? "-" }}</td>
                                    @if($type=='3D')
                                    <td><a href="{{ url('/super_admin/bet_slip_3d/'.$winner->id) }}" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a></td>
                                    @elseif($type=='1D')
                                    <td><a href="{{ url('/super_admin/bet_slip_1d/'.$winner->id) }}" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a></td>
                                    @else
                                    <td><a href="{{ url('/super_admin/bet_slip/'.$winner->id) }}" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a></td>
                                    @endif
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
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            //calendar

            //refresh
            $('.reload').click(function(){
                window.location = 'cash-in';
            });

            //by date daily filter
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                var status = $('.status').val();
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                var type = $('.type').val();
                var section = $('.section').val();
                var side = $('#side_status').val();
            history.pushState(null, '', '?start_date='+start+'&end_date='+end+'&type='+type+'&section='+section+'&side_stats='+side)
                window.location.reload()
            });

            //Filter
        

         $('.type').change(function(){
            var status = $('.status').val();
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                var type = $('.type').val();
                var section = $('.section').val();
                var side = $('#side_status').val();
            history.pushState(null, '', '?start_date='+start+'&end_date='+end+'&type='+type+'&section='+section+'&side_stats='+side)
                window.location.reload()
            });

            $('.section').change(function() {
                var status = $('.status').val();
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                var type = $('.type').val();
                var section = $('.section').val();
                var side = $('#side_status').val();
            history.pushState(null, '', '?start_date='+start+'&end_date='+end+'&type='+type+'&section='+section+'&side_stats='+side)
                window.location.reload()
        });

        $('#side_status').change(function() {
                var status = $('.status').val();
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                var type = $('.type').val();
                var section = $('.section').val();
                var side = $('#side_status').val();
            history.pushState(null, '', '?start_date='+start+'&end_date='+end+'&type='+type+'&section='+section+'&side_stats='+side)
                window.location.reload()
        });
        });

    </script>
@endsection
