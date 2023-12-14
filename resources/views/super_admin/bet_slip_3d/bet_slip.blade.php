@extends('super_admin.backend_layout.app')

@section('title', '3D Bet Slips')

@section('bet-slip-3d-active', 'active')

@section('content')
    <div class="row">
        <div class="p-2">
            <button class="btn btn-info btn-sm reload" title="refresh"><i class="nav-icon fas fa-retweet"></i></button>
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
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <select class="form-control select2 year">
                        @foreach ($years as $key => $item)
                            <option value="{{ $item }}"
                                @if (request()->year) @if ($item == request()->year)
                                    selected @endif
                            @else @if ($item == $cur_year) selected @endif @endif>
                                {{ $item }} &nbsp;&nbsp;&nbsp;&nbsp;
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="p-2">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <select class="form-control select2 month">
                        @foreach ($months as $key => $item)
                            <option value="{{ $key }}"
                                @if (request()->month) @if ($key == request()->month)
                                    selected @endif
                            @else @if ($key == $cur_month) selected @endif @endif>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="p-2">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                    </div>
                    <select class="form-control select2 date3d">
                        <option value="">Filter Date</option>
                        @foreach ($date as $item)
                            <option value="{{ $item->date }}" @if ($item->date == request()->date3d) selected @endif>
                                {{ $item->date }}
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
                                    <th>3D date</th>
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
                                        <td>{{ $item->date_3d }}</td>
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
                                            <small
                                                class="badge badge-success">{{ $item->reward == 1 ? 'Paid' : '' }}</small>
                                            @if ($item->reward == 0)
                                                <small
                                                    class="badge badge-danger">{{ $item->reward_amount > 0 ? 'Unpaid' : '' }}</small>
                                            @endif
                                        </td>
                                        <td>{!! date('d-M-Y H:i:s', strtotime($item->created_at)) !!}</td>
                                        <td>
                                            <a href="{{ url('/super_admin/bet_slip_3d/' . $item->id) }}"
                                                title="View"><button class="btn btn-info btn-sm"><i
                                                        class="fa fa-eye"></i></button></a>
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
                <label for="customRadio4" class="custom-control-label">Updated <label id="update_time">0</label>s
                    ago</label>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#bet_slip_datatable').DataTable({
            "info": false,
            "ordering": false,
        });

        //Filter
        $('.year').change(function() {
            var year = $('.year').val();
            var month = $('.month').val();
            var date3d = $('.date3d').val();
            var side = $('#side_status').val();
            history.pushState(null, '', '?year=' + year + '&month=' + month + '&date3d=' + date3d + '&side_stats=' + side)
            window.location.reload()
        });

        $('.month').change(function() {
            var year = $('.year').val();
            var month = $('.month').val();
            var date3d = $('.date3d').val();
            var side = $('#side_status').val();
            history.pushState(null, '', '?year=' + year + '&month=' + month + '&date3d=' + date3d + '&side_stats=' + side)
            window.location.reload()
        });

        $('.date3d').change(function() {
            var year = $('.year').val();
            var month = $('.month').val();
            var date3d = $('.date3d').val();
            var side = $('#side_status').val();
            history.pushState(null, '', '?year=' + year + '&month=' + month + '&date3d=' + date3d + '&side_stats=' + side)
            window.location.reload()
        });

        $('#side_status').change(function() {
            var year = $('.year').val();
            var month = $('.month').val();
            var date3d = $('.date3d').val();
            var side = $('#side_status').val();
            history.pushState(null, '', '?year=' + year + '&month=' + month + '&date3d=' + date3d + '&side_stats=' + side)
            window.location.reload()
        });

        $('.reload').click(function() {
            window.location = '/super_admin/bet_slip_3d';
        });

        //
    </script>
@endsection
