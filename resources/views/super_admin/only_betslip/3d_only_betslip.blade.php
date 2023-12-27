@extends('super_admin.backend_layout.app')

@section('user-active', 'active')

@section("css")
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        font-weight: bold;
        font-size: .9rem;
        color: black;
    }
</style>
@endsection

@section('content')
<div class="mb-3"><a role="button" onclick="history.back()" class=" btn-sm btn-dark"><i class="fa-solid fa-circle-left mr-2"></i>Back</a></div>

<h5>3D Betslip History user</h5>
<form method="get" action="{{url('super_admin/user/3d_betslips_only/'. $user_id)}}">

    <div class="row align-items-center pt-1">
        <div class="form-group col-md-2">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request()->input('start_date', date('Y-m-d')) }}">
        </div>
        <div class="form-group col-md-2">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request()->input('end_date', date('Y-m-d')) }}">
        </div>

        <div class="col-2 mt-2 pt-1">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>

</form>
<div class="col-12 d-flex justify-content-start m-0 p-0">
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-primary d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-sack-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Bet</span>
                <span class="info-box-number" style="font-weight:bolder">{{$amount}}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-info d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Reward</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $total_bet }}</span>
            </div>
        </div>
    </div>

    @if($result >= 0) <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-success d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-plus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Profit</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $result }}</span>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-danger d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-minus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Loss</span>
                <span class="info-box-number" style="font-weight:bolder">{{$result}}</span>
            </div>
        </div>
    </div>
    @endif
</div>


<div class="card card-dark card-outline">
    <div class="card-body">
        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="data_table_super_admin" class="table table-striped table-sm">
                        <thead>
                            <tr class="text-uppercase">
                                <th></th>
                                <th>ID</th>
                                <th>3D Open Date</th>
                                <th>Section</th>
                                <th>Total Amount</th>
                                <th>Total Bet Numbers</th>
                                <th>Win Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bet_history_3d as $slip)
                            <tr class="cursor-pointer" data-toggle="collapse" data-target="#collpase_{{$slip->id}}" aria-expanded="false" aria-controls="collpase_{{$slip->id}}">
                                <td>
                                    <button class="btn btn-info btn-sm" data-toggle="collapse" data-target="#collpase_{{$slip->id}}" aria-expanded="false" aria-controls="collpase_{{$slip->id}}"><i class="fa fa-eye"></i></button>
                                </td>
                                <td>{{ $slip->id ?? '' }}</td>
                                <td>{{ $slip->bet_date ?? '' }}</td>
                                <td>{{ $slip->section ?? ''}}</td>
                                <td>{{ $slip->total_amount ?? ''}}</td>
                                <td>{{ $slip->total_bet ?? ''}}</td>
                                <td>{{ $slip->reward_amount ?? '' }}</td>
                                <td>
                                    <span class="px-2 rounded-3 {{ $slip->win === 0 ? 'bg-warning' : ($slip->win === 1 ? 'bg-success' : 'bg-danger') }}">
                                        {{ $slip->win === 0 ? 'pending' : ($slip->win === 1 ? 'win' : 'Lose') }}
                                    </span>
                                </td>
                                <td>{{ $slip->date ?? '' }}</td>

                            </tr>
                            <tr>
                                <td colspan="9">
                                    <div class="collapse" id="collpase_{{$slip->id}}">
                                        <table class="table bg-white">
                                            <thead>
                                                <tr class="text-black text-center">
                                                    <th>Bet Number</th>
                                                    <th>3D Open Date</th>
                                                    <th>Section</th>
                                                    <th>Amount</th>
                                                    <th>Odd</th>
                                                    <th>Win Amount</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                @foreach ($slip->bettings_3d as $s )
                                                <tr class="text-center">
                                                    <td>{{$s->bet_number}}</td>
                                                    <td>{{$s->bet_date}}</td>
                                                    <td>{{$s->section}}</td>
                                                    <td>{{$s->amount}}</td>
                                                    <td>{{$tot === 1 ? $too_odd : $s->odd}}</td>
                                                    <td>

                                                        {{$s->win === 1 ? ($s->tot === 1 ? $s->tot_odd * $s->amount : $s->odd * $s->amount) : 0}}
                                                    </td>
                                                    <td>
                                                        <span class="px-2 rounded-3 {{ $s->win === 0 ? 'bg-warning' : ($s->win === 1 ? 'bg-success' : 'bg-danger') }}">
                                                            {{ $s->win === 0 ? 'pending' : ($s->win === 1 ? 'win' : 'Lose') }}
                                                        </span>
                                                    </td>
                                                    <td>{{$s->date}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <!-- Add your data for the collapsed section here if needed -->
                                        </table>
                                    </div>
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
    function openWindowAtTopLeft(warger) {
        var leftPosition = 0;
        var topPosition = 0;
        var windowFeatures = 'width=800,height=600,left=' + leftPosition + ',top=' + topPosition;
        window.open(`https://prod_md.9977997.com/Report/BetDetail?agentCode=E550&WagerID=${warger}`, '_blank',
            windowFeatures);
    }
</script>
@endsection