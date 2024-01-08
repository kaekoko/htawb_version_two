@extends('super_admin.backend_layout.app')

@section('title', 'Crypto 2D Bet Slips')

@section('bet-slip-crypto-2d-active', 'active')

@section('content')
    <div class="row">
        <div class="p-2">
            <button class="btn btn-info btn-sm reload" title="refresh"><i class="nav-icon fas fa-retweet"></i></button>
        </div>
        <div class="p-2">
            <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="bet_date" class="form-control form-control-sm date" value="{{ request()->date ?? date('Y-m-d') }}">
                </div>
            </div>
        </div>
        <div class="p-2">
       
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
                            <option value="{{ \Carbon\Carbon::createFromFormat('H:i:s',$item->time_section)->format('h:i A') }}"
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
    </div>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="bet_slip_datatable" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#Bet Slip</th>
                                    <th>Username</th>
                                   
                                    <th>phone</th>
                                    <th>Section</th>
                                    <th>Total Amount</th>
                                    <th>Total Bet</th>
                                    <th>Win/Lose</th>
                                    <th>Reward Amount</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bet_slips as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        
                                        <td>{{ $item->user->phone }}</td>
                                        <td>{{ $item->section }}</td>
                                        <td>
                                            {{ number_format($item->total_amount) }} MMK
                                        </td>
                                        <td>{{ $item->total_bet }}</td>
                                        <td>
                                            @if ($item->win == 0)
                                                <small class="badge badge-warning">Pending</small>
                                            @endif
                                            @if ($item->win == 1)
                                                <small class="badge badge-success">Win</small>
                                            @endif
                                            @if ($item->win == 2)
                                                <small class="badge badge-danger">Lose</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ number_format($item->reward_amount) }} MMK
                                            <small class="badge badge-success">{{ $item->reward == 1 ? "Paid" : "" }}</small>
                                            @if($item->reward == 0)
                                                <small class="badge badge-danger">{{ $item->reward_amount > 0 ? "Unpaid" : "" }}</small>
                                            @endif
                                        </td>
                                        <td>{!! date('d-M-Y H:i:s', strtotime($item->created_at)) !!}</td>
                                        <td>
                                            <a href="{{ url('/super_admin/bet_slip_c2d/'.$item->id) }}" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row update_time">
        <div class="p-2">
            <div class="custom-control custom-radio">
                <input class="custom-control-input custom-control-input-info" type="radio" checked>
                <label for="customRadio4" class="custom-control-label">Updated <label id="update_time">0</label>s ago</label>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#bet_slip_datatable').DataTable( {
            "info":     false,
            "ordering": false,
        } );

        //Date range picker
        $(function() {
            $('input[name="bet_date"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                maxYear: parseInt(moment().format('YYYY'),11)
            }, function(start, end, label) {
            });
        });

        //Filter data
        $('.date').on('apply.daterangepicker', function(ev, picker) {
            var date = $('.date').val();
            var section = $('.section').val();
            history.pushState(null, '', '?date='+date+'&section='+section)
            window.location.reload()
        });

        $('.section').change(function() {
            var date = $('.date').val();
            var section = $('.section').val();
            var side = $('#side_status').val();
            history.pushState(null, '', '?date='+date+'&section='+section+'&side_stats='+side)
            window.location.reload()
        });

        $('#side_status').change(function() {
            var date = $('.date').val();
            var section = $('.section').val();
            var side = $('#side_status').val();
            history.pushState(null, '', '?date='+date+'&section='+section+'&side_stats='+side)
            window.location.reload()
        });

        $('.reload').click(function(){
            window.location = '/super_admin/bet_slip_c2d';
        });
    </script>
@endsection
