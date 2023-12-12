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

<h5>2D Betslip History user</h5>
<form method="get" action="{{url('super_admin/user/all_report/'. $user_id)}}">

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

<h5>1D OVER ALL SlIP TOTAL</h5>
<div class="col-12 d-flex justify-content-start m-0 p-0">
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-primary d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-sack-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Bet</span>
                <span class="info-box-number" style="font-weight:bolder">{{$oneAmount}}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-info d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Reward</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $oneTotal_bet }}</span>
            </div>
        </div>
    </div>

    @if($twoResult >= 0) <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-success d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-plus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Profit</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $oneResult }}</span>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-danger d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-minus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Loss</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $oneResult }}</span>
            </div>
        </div>
    </div>
    @endif
</div>


<h5>2D OVER ALL SlIP TOTAL</h5>
<div class="col-12 d-flex justify-content-start m-0 p-0">
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-primary d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-sack-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Bet</span>
                <span class="info-box-number" style="font-weight:bolder">{{$twoAmount}}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-info d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Reward</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $twoTotal_bet }}</span>
            </div>
        </div>
    </div>

    @if($twoResult >= 0) <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-success d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-plus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Profit</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $twoResult }}</span>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-danger d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-minus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Loss</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $twoResult }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

<h5>Crypto 1D OVER ALL SlIP TOTAL</h5>
<div class="col-12 d-flex justify-content-start m-0 p-0">
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-primary d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-sack-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Bet</span>
                <span class="info-box-number" style="font-weight:bolder">{{$cryptoOneAmount}}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-info d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Reward</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $cryptoOneTotal_bet }}</span>
            </div>
        </div>
    </div>

    @if($twoResult >= 0) <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-success d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-plus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Profit</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $cryptoOneResult }}</span>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-danger d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-minus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Loss</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $cryptoOneResult }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

<h5>Crypto 2D OVER ALL SlIP TOTAL</h5>
<div class="col-12 d-flex justify-content-start m-0 p-0">
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-primary d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-sack-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Bet</span>
                <span class="info-box-number" style="font-weight:bolder">{{$cryptotwoAmount}}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-info d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Reward</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $cryptotwoTotal_bet }}</span>
            </div>
        </div>
    </div>

    @if($twoResult >= 0) <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-success d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-plus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Profit</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $cryptotwoResult }}</span>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-danger d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-minus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Loss</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $cryptotwoResult }}</span>
            </div>
        </div>
    </div>
    @endif
</div>


{{-- therr d --}}
<h5>3D OVER ALL SlIP TOTAL</h5>
<div class="col-12 d-flex justify-content-start m-0 p-0">
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-primary d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-sack-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Bet</span>
                <span class="info-box-number" style="font-weight:bolder">{{$threeAmount}}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-info d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Reward</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $threeTotal_bet }}</span>
            </div>
        </div>
    </div>

    @if($threeResult >= 0) <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-success d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-plus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Profit</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $threeResult }}</span>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-danger d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-minus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Loss</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $threeResult }}</span>
            </div>
        </div>
    </div>
    @endif
</div>


{{-- game --}}
<h5>GAME OVER ALL SlIP TOTAL</h5>
<div class="col-12 d-flex justify-content-start m-0 p-0">
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-primary d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-sack-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Bet</span>
                <span class="info-box-number" style="font-weight:bolder">{{$game_amount}}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-info d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Reward</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $game_total_bet }}</span>
            </div>
        </div>
    </div>

    @if($game_result >= 0) <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-success d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-plus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Profit</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $game_result }}</span>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-danger d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-minus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Loss</span>
                <span class="info-box-number" style="font-weight:bolder">{{ $game_result }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
@section('scripts')


@endsection