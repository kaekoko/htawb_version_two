@extends('super_admin.backend_layout.app')

@section('user-active', 'active')

@section("css")
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        font-weight: bold;
        font-size: .9rem;
        color: black;
    }

    td {
        font-weight: bolder;

    }
</style>
@endsection

@section('content')
<div class="mb-3"><a role="button" onclick="history.back()" class=" btn-sm btn-dark"><i class="fa-solid fa-circle-left mr-2"></i>Back</a></div>
<h5>Select The Providers</h5>
<form method="get">
    <div class="row mb-1 d-flex align-items-center">
        <div class="col-12">

            <select class="form-control form-control-lg select2" placeholder="select the providers" multiple name="provider[]">
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                @foreach ($providers as $p)
                @php
                $selected = in_array($p->p_code, request()->query('provider', [])) || empty(request()->query('provider'));
                $queryString = http_build_query(array_merge(request()->query(), ['provider[]' => $p->p_code]));
                $url = request()->url() . '?' . $queryString;
                @endphp

                <option class="" value="{{ $p->p_code }}" {{ $selected ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
                @endforeach
            </select>
        </div>


    </div>
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
            <input type="text" class="form-control" value="{{ $code }}" disabled>
        </div>

        <div class="col-2 mt-2 pt-1">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>

</form>


@php
 $total_bet=0;
 $total_payout=0;
 foreach ($provider_slip as $slip){
    $total_bet +=$slip->bet_amount;
    $total_payout +=$slip->payout_amount;
 }
 $result=$total_payout - $total_bet;
@endphp



<div class="col-12 d-flex justify-content-start m-0 p-0">
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-primary d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-sack-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Bet</span>
                <span class="info-box-number" style="font-weight:bolder">@php echo $total_bet; @endphp</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-info d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Total Payout</span>
                <span class="info-box-number" style="font-weight:bolder">@php echo $total_payout; @endphp</span>
            </div>
        </div>
    </div>

    @if($result >= 0) <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-success d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-plus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Profit</span>
                <span class="info-box-number" style="font-weight:bolder">@php echo $result; @endphp</span>
            </div>
        </div>
    </div>
    @else
    <div class="col-12 col-sm-6 col-md-2 p-0 mb-2">
        <div class="info-box bg-danger d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-minus"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">Loss</span>
                <span class="info-box-number" style="font-weight:bolder">@php echo $result; @endphp</span>
            </div>
        </div>
    </div>
    @endif

    <a href="{{url('super_admin/only/betslip/details',$code)}}" class="col-12 col-sm-6 col-md-2 p-0 mb-2 mr-3">
        <div class="info-box bg-info d-flex flex-wrap py-3 align-items-center justify-content-around px-1 rounded-lg">
            <i style="font-size: 3rem;" class="nav-icon fa-solid fa-file-invoice-dollar"></i>
            <div class="d-flex flex-column flex-wrap text-center text-white">
                <span class="info-box-text" style="font-size: 1rem;">All Betslip View</span>
                <span class="info-box-number" style="font-weight:bolder"></span>
            </div>
        </div>
    </a>
</div>

<div class="card card-dark card-outline">
    <div class="card-body">
        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="data_table_super_admin" class="table table-hover table-lg table-bordered border border-dark">
                        <thead class="thead-dark">
                            <tr class="text-uppercase">
                                <th>Product</th>
                                <th>bet amount</th>
                                <th>payout amount</th>
                                <th>Win/Lose</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($provider_slip as $slip)
                            <tr>

                                <td>
                                    {{ $slip->provider_name }}
                                </td>
                                <td class='bet_amount'>{{ $slip->bet_amount }}</td>
                                <td class='payout_amount'>{{ $slip->payout_amount }}</td>
                                <td class='payout_amount'>{{ $slip->payout_amount - $slip->bet_amount }}</td>
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
    // function openWindowAtTopLeft(warger) {
    //     var leftPosition = 0;
    //     var topPosition = 0;
    //     var windowFeatures = 'width=800,height=600,left=' + leftPosition + ',top=' + topPosition;
    //     window.open(`https://prod_md.9977997.com/Report/BetDetail?agentCode=E550&WagerID=${warger}`, '_blank',
    //         windowFeatures);
    // }

    // $(document).ready(function() {
    //     $('#data_table_super_admin_betslip').DataTable();
    // })
</script>
@endsection
