@extends('super_admin.backend_layout.app')

@section('title', '2D Report')

@section('report-2d-active', 'active')

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
    </div>
    <br>
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
                        <table id="data_table_super_admin" class="table table-striped table-sm data_table_report">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>All Bet Amount</th>
                                    <th>Total Reward</th>
                                    <th>User Referral</th>
                                    <th>Profit</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daily_statics as $i=>$item)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ number_format($item->all_bet_amount) }}</td>
                                        <td>{{ number_format($item->total_reward) }}</td>
                                        <td>{{ number_format($item->user_referral) }}</td>
                                        <td>{{ number_format($item->profit) }}</td>
                                        <td>{{ $item->date }}</td>
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
    <script src="{{ asset('custom/report_2d.js') }}"></script>
@endsection
