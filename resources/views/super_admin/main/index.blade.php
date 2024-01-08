@extends('super_admin.backend_layout.app')

@section('title', 'Main Daily Dashboard')

@section('main-dashboard-active', 'active')

@section('content')
<div class="content pb-5">
    <!-- <h3 class='text-center'>Main Dashboard</h3> -->
    <form class="d-flex justify-content-start align-items-center">
        <div class="form-group m-3">
            <span class="text-black font-weight-bold">Start Date</span>
            <div class="input-group bg-white text-black">
                <div class="input-group-prepend bg-dark">
                    <span class="input-group-text bg-dark">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                <input style="height: 40px;" type="date" name="start_date" class="bg-dark form-control form-control-sm date" id="date" value="{{ request()->start_date ?? date('Y-m-d') }}">
            </div>
        </div>

        <div class="form-group m-3">
            <span class="text-black font-weight-bold">End Date</span>
            <div class="input-group bg-white text-black">

                <div class="input-group-prepend bg-dark">
                    <span class="input-group-text bg-dark">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                <input style="height: 40px;" type="date" name="end_date" class="bg-dark form-control form-control-sm date" id="date" value="{{ request()->end_date ?? date('Y-m-d') }}">
            </div>
        </div>

        <div class="mt-4">
            <button onclick="second()" class="btn btn-primary py-2">Search</button>
        </div>
    </form>

    <section id="cashin_out" class="px-3 mt-2">
        <h4 class="text-muted">Cashin/out report</h4>
        <div class="row">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-muted text-lg">Cashin total</span>
                        <span class="info-box-number text-xl">{{ $total_cashin->amount ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Cashout total</span>
                        <span class="info-box-number text-xl">{{ $total_cashout->amount ?? 0}}</span>
                    </div>
                </div>
            </div>

            @if($different_cashin_out >= 0)
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Profit</span>
                        <span class="info-box-number text-xl">{{ $different_cashin_out ?? 0}}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-minus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Loss</span>
                        <span class="info-box-number text-xl">{{$different_cashin_out ?? 0}}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <hr>
    <section id="one_d" class="px-3 mt-2">
        <h4 class="text-muted">One D report</h4>
        <div class="row">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-muted text-lg">oneD bet total</span>
                        <span class="info-box-number text-xl">{{ $one->one_bet_amount ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">One d Reward</span>
                        <span class="info-box-number text-xl">{{ $one->one_payout_amount ?? 0}}</span>
                    </div>
                </div>
            </div>

            @if($one_result >= 0)
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Profit</span>
                        <span class="info-box-number text-xl">{{ $one_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-minus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Loss</span>
                        <span class="info-box-number text-xl">{{$one_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <hr>

    <section id="two_d" class="px-3 mt-2">
        <h4 class="text-muted">Two D report</h4>
        <div class="row">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-muted text-lg">TwoD bet total</span>
                        <span class="info-box-number text-xl">{{ $two->two_bet_amount ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">TwoD Reward</span>
                        <span class="info-box-number text-xl">{{ $two->two_payout_amount ?? 0}}</span>
                    </div>
                </div>
            </div>

            @if($two_result >= 0)
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Profit</span>
                        <span class="info-box-number text-xl">{{ $two_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-minus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Loss</span>
                        <span class="info-box-number text-xl">{{$two_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <hr>

    <section id="crypto_one_d" class="px-3 mt-2">
        <h4 class="text-muted">Crypto One D report</h4>
        <div class="row">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-muted text-lg">CryptoOne Bet total</span>
                        <span class="info-box-number text-xl">{{ $cryptoOne->cryptoOne_bet_amount ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">CryptoOne Reward</span>
                        <span class="info-box-number text-xl">{{ $cryptoOne->cryptoOne_payout_amount ?? 0}}</span>
                    </div>
                </div>
            </div>

            @if($cryptoOne_result >= 0)
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Profit</span>
                        <span class="info-box-number text-xl">{{ $cryptoOne_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-minus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Loss</span>
                        <span class="info-box-number text-xl">{{$cryptoOne_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <hr>

    <section id="crypto_two_d" class="px-3 mt-2">
        <h4 class="text-muted">Crypto Two D report</h4>
        <div class="row">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-muted text-lg">CryptoTwo bet total</span>
                        <span class="info-box-number text-xl">{{ $cryptoTwo->cryptoTwo_bet_amount ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">CryptoTwo Reward</span>
                        <span class="info-box-number text-xl">{{ $cryptoTwo->cryptoTwo_payout_amount ?? 0}}</span>
                    </div>
                </div>
            </div>

            @if($cryptoTwo_result >= 0)
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Profit</span>
                        <span class="info-box-number text-xl">{{ $cryptoTwo_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-minus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Loss</span>
                        <span class="info-box-number text-xl">{{$cryptoTwo_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <hr>

    <section id="three" class="px-3 mt-2">
        <h4 class="text-muted">Three D report</h4>
        <div class="row">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-muted text-lg">ThreeD bet total</span>
                        <span class="info-box-number text-xl">{{ $three->three_bet_amount ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">ThreeD Reward</span>
                        <span class="info-box-number text-xl">{{ $three->three_payout_amount ?? 0}}</span>
                    </div>
                </div>
            </div>

            @if($three_result >= 0)
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Profit</span>
                        <span class="info-box-number text-xl">{{ $three_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-minus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold text-lg">Loss</span>
                        <span class="info-box-number text-xl">{{$three_result ?? 0}}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>


</div>
@endsection
@section('scripts')
<script>
    function second(){
        let timerInterval;
Swal.fire({
  title: "Thanks for waiting",
  html: "Waiting Main Dashboard",
  timer: 90000,
  timerProgressBar: true,
  didOpen: () => {
    Swal.showLoading();
    const timer = Swal.getPopup().querySelector("b");
    timerInterval = setInterval(() => {
      timer.textContent = `${Swal.getTimerLeft()}`;
    }, 100);
  },
  willClose: () => {
    clearInterval(timerInterval);
  }
}).then((result) => {
  /* Read more about handling dismissals below */
  if (result.dismiss === Swal.DismissReason.timer) {
    console.log("I was closed by the timer");
  }
});
    }
</script>
@endsection