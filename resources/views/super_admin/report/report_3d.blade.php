@extends('super_admin.backend_layout.app')

@section('title', '3D Report')

@section('report-3d-active', 'active')

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
                <select class="form-control select2 year">
                    @foreach ($years as $key=>$item )
                        <option value="{{ $item }}"
                            @if(request()->year)
                                @if ( $item == request()->year)
                                    selected
                                @endif
                            @else
                                @if ( $item == $cur_year)
                                    selected
                                @endif
                            @endif>
                            {{ $item }} &nbsp;&nbsp;&nbsp;&nbsp;
                        </option>
                    @endforeach
                </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="nav-icon fas fa-file-invoice"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">All Bet Amounts</span>
                <span class="info-box-number">{{ number_format($all_bet_amount) }}</span>
            </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-trophy"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Reward</span>
                <span class="info-box-number">{{ number_format($total_reward) }}</span>
            </div>
            </div>
        </div>
        <div class="clearfix hidden-md-up"></div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1"><i class="fa-sm fas fa-percent"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total User Refer Amount</span>
                <span class="info-box-number">{{ number_format($user_referral) }}</span>
            </div>
            </div>
        </div>
        @if($profit > 0)
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-plus-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Profit</span>
                    <span class="info-box-number">{{ number_format($profit) }}</span>
                </div>
                </div>
            </div>
        @else
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-minus-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Loss</span>
                    <span class="info-box-number">{{ number_format($profit) }}</span>
                </div>
                </div>
            </div>
        @endif
    </div>
    <br>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Lucky Number</th>
                                    <th>All Bet Amount</th>
                                    <th>Total Reward</th>
                                    <th>User Referral</th>
                                    <th>Profit</th>
                                    <th>3D Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bet_date_3d_history as $i=>$item)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $item->lucky_number ?? '-' }} </td>
                                        <td>{{ number_format($item->all_bet_amount) }} </td>
                                        <td>{{ number_format($item->total_reward) }} </td>
                                        <td>{{ number_format($item->user_referral) }} </td>
                                        <td>{{ number_format($item->profit) }} </td>
                                        <td>{{ $item->bet_date }}</td>
                                        <td>
                                            <a href="{{ url('/super_admin/dashboard_3d?bet_date='.$item->bet_date) }}" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a>
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
@endsection
@section('scripts')
    <script>
        //refresh
        $('.reload').click(function(){
            window.location = '/super_admin/report_3d';
        });

        //Filter
        $('.year').change(function() {
            var year = $('.year').val();
            history.pushState(null, '', '?year='+year)
            window.location.reload()
        });
    </script>
@endsection
