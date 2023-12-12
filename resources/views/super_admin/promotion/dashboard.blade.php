@extends('super_admin.backend_layout.app')

@section('title', 'Super Admin Promotion Dashboard')

@section('dashboard-promotion-active', 'active')

@section('content')
<div class="d-flex">
       
    </div>
    <div class="row">
       
    </div>
    <div id="spinner"  class="loading-spinner text-center fw-bold"></div>

    <div id="content" >
        <div class="row">
            <div class="col-12">
                <div class="tab-content" id="accountContent">
                   
                   
                        
                            <div class="row justify-content-center mt-3 clerance_div">
                                
                                    <button type="button" data-toggle="modal" data-target="#approve"  class="mr-1 btn btn-primary">
                                   Approve
                                    </button>
                              
                                    <button type="button" data-toggle="modal" data-target="#clearance"  class="mr-1 btn btn-success clearance ">
                                        Clearance promo
                                    </button>

                                    <button type="button" data-toggle="modal" data-target="#approve_refund"  class="mr-1 btn btn-danger">
                                   Approve Refund
                                    </button>
                            </div>                    
                </div>
            </div>
        </div>
        <div class="row mb-5 mt-5">
            <div class="col-12">
                <h3 class="m-0">Over All Statistics</h3>
            </div>
        </div>
        <div id="over_all_amount">
            <div class="row">
                <div class="over_all_amount">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{$promotion->total_bet ?? 0}}</h3>
                            <p>All Bet Amounts</p>
                        </div>
                        <div class="icon">
                            <i class="nav-icon fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
                <div class="over_all_amount">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{$promotion->promotion_amount ?? 0}}</h3>
                            <p>Total Reward</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>

                <div class="over_all_amount">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{$promotion->total_bet - $promotion->promotion_amount}}</h3>
                            <p>Different</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
                
            </div>
          
        </div>
        <div class="row mb-5 mt-5">
            <div class="col-12">
                <h3 class="m-0">promo history</h3>
            </div>
        </div>
        <div class="card card-dark card-outline">
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total Bet</th>
                                        <th>Reward</th>
                                        <th>Different</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($report as $r)
                                    <tr>
                                    <td>#</td>
                                    <td>{{ $r->start_date }} </td>
                                    <td>{{ $r->end_date }} </td>
                                    <td>{{ $r->all_bet_amount }}</td>
                                    <td>{{ $r->all_payout_amount }}</td>
                                    <td>{{ $r->all_bet_amount  - $r->all_payout_amount }} </td>
                                    <td>{{ $r->created_at }}</td>
                                    </tr>

                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Approve password model --}}
        <div class="modal fade modal-password" id="approve" tabindex="-1" role="dialog" aria-labelledby="approve" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/promotion/approve') }}">
                            @csrf
                           <div class="row">
                           <div class='form-group col-6'>
                                <label for="">Start Date</label>
                                <input  type="date" name="start_date" class='form-control' id="">
                           </div>
                           <div class='form-group col-6'>
                                <label for="">End Date</label>
                                <input  type="date" name="end_date" class='form-control' id="">
                           </div>
                           </div>

                           <div class="row">
                           <div class='form-group col-6'>
                                <label for="">Precent</label>
                                <input  type="number" name="precent" placeholder='1 to 100 ' class='form-control' id="">
                           </div>
                           <div class='form-group col-6'>
                                <label for="">Password</label>
                                <input  type="password" name="password" class='form-control' id="">
                           </div>
                           </div>
                            <button onclick="second()" type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        {{-- clearance password model --}}
        <div class="modal fade modal-password" id="clearance" tabindex="-1" role="dialog" aria-labelledby="clearance" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Clearance Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/promotion/complete') }}">
                            @csrf
                           
                            <div class='form- mb-3'>
                            <label for="">Password</label>
                            <input type="password" name="password" class='form-control' id="">
                            </div>
                
                            <button onclick="second()" type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        {{-- approve_refund password model --}}
        <div class="modal fade modal-password" id="approve_refund" tabindex="-1" role="dialog" aria-labelledby="approve_refund" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">approve_refund Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/promotion/approve/refund') }}">
                            @csrf
                           
                            <div class='form- mb-3'>
                            <label for="">Password</label>
                            <input type="password" name="password" class='form-control' id="">
                            </div>
                
                            <button onclick="second()" type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('scripts')
<script>
    function second(){
        let timerInterval;
Swal.fire({
  title: "Thanks for waiting",
  html: "Waiting Promotion process",
  timer: 20000,
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

