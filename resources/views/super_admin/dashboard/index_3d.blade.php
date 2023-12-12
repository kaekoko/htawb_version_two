@extends('super_admin.backend_layout.app')

@section('title', 'Super Admin 3D Dashboard')

@section('dashboard-3d-active', 'active')

@section('content')
<div class="d-flex">
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
        </div>
        <div class="ml-auto p-2 update_time">
            <div class="custom-control custom-radio">
                <input class="custom-control-input custom-control-input-info" type="radio" checked>
           <span>time Section::</span> 
           <span class="fw-bolder" style="font-size: 20px;">{{ \Carbon\Carbon::createFromFormat('H:i:s', $section->time)->format('h:i A') }}</span>
          <div>
          <span>This month Open Day::</span> 
           <span class="fw-bolder" style="font-size: 20px;">{{$section->date}} day</span>
          </div>
                <input type="hidden" name="time_section" class="time_section" value="{{$section->time_section}}">
            </div>
        </div>
    </div>
    <div class="tab-content" id="accountContent">
        <div class="tab-pane fade div_section active show" id="id-section" role="tabpanel" aria-labelledby="id-section-tab">
            <div class="d-flex justify-content-center">
                @php
                    $role_id = Auth::guard('super_admin')->user()->role_id;
                @endphp

                    <div class="p-2">
                    <button type="button" data-toggle="modal" data-target="#clearance" {{$approve == 0? 'disabled' : ''}} {{$read == 1 ? 'disabled' : ''}} class="mr-1 btn btn-danger clearance ">
                    Clearance | {{$section->date}}
                    </button>
                        
                    </div>
                    <div class="p-2">
                        <button type="button" class="btn-sm btn btn-outline-success refund">Refund</button>
                    </div>
            </div>
            <div
                class="row border border-5 border-dark callout justify-content-center mt-3 all_bet scrollable_3d scrollbarbackgroundblue_3d dashboard_3d_scroll">

               @foreach($two_ds as $t)
               <div class="p-2">

                                        @php
                                            $isBlocked = in_array($t->bet_number, $block_number);
                                        @endphp

                                        @php
                                        $betAmount = 0;
                                        foreach ($betting_total as $b) {
                                            if ($t->bet_number == $b->bet_number) {
                                                $betAmount = $b->amount;
                                                break;
                                            }
                                        }
                                        
                                        @endphp
                                        
                            <button type="button" class="btn btn-block btn-lg custom_dashboard{{ $isBlocked ? ' bg-danger' : '' }} {{ $betAmount == 0 ? 'bg-secondary' : '' }}"
                            data-toggle="modal" data-target="{{ $isBlocked ? '' : '#example' . $t->bet_number }}">
                                <p class="open_number">{{$t->bet_number}}</p>
                                <span>{{ number_format($betAmount, 0, '.', ',')}}</span>
                            </button>

                            {{-- Bet detail model --}}
                                        <div class="modal fade modal-number" id="example{{$t->bet_number}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="modal-head">{{$t->bet_number}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row justify-content-center">
                                                            <div class="col-auto">
                                                                <table
                                                                    class="table table-responsive table-striped table-borderless scrollable scrollbarbackgroundblue dashboard_scroll">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>User</th>
                                                                            <th>Amount</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="betDetailData">
                                                                        @foreach ($betting as $b)
                                                                        @forelse ($b->bettings_3d as $bet)
                                                                            @if ($t->bet_number == $bet->bet_number)
                                                                                <tr>
                                                                                    <td>{{ $b->user->name }}</td>
                                                                                    <td>{{ $bet->amount }}</td>
                                                                                </tr>
                                                                            @endif
                                                                        @empty
                                                                        <tr>
                                                                            No bet for users
                                                                        </tr>
                                                                        @endforelse
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        </div>
               @endforeach
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
                            <h3>{{$total_amount->total_amount ?? '0'}}</h3>
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
                            <h3>{{$total_amount->reward_amount ??  '0'}}</h3>
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
                            <h3>{{$total_amount->total_amount - $total_amount->reward_amount ?? '0' }}</h3>
                            <p>Profit</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>


                <div class="over_all_amount">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{$numberLucky}}</h3>
                            <p>Lucky Number</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
    <div class="row mb-5 mt-5">
        <div class="col-12">
            <h3 class="m-0">Bet Slips</h3>
        </div>
    </div>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="bet_slip_dash_datatable" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>3D Date</th>
                                    <th>Total Amount</th>
                                    <th>Total Bet</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                                <tbody>
                                @foreach ($betting as $item)
                                    <tr>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->date_3d }}</td>
                                        <td>{{ $item->total_amount }}</td>
                                        <td>{{ $item->total_bet }}</td>
                                        <td>{{ $item->date }}</td>
                                        <td>
                                            <a href="{{ url('/super_admin/bet_slip_3d/' . $item->id) }}"
                                                title="View"><button class="btn btn-info btn-sm"><i
                                                        class="fa fa-eye"></i></button></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bet detail model --}}
    <div class="modal fade modal-number">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-head"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <table
                                class="table table-responsive table-striped table-borderless scrollable scrollbarbackgroundblue dashboard_scroll">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="betDetailData">
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                    <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/clearance_3d') }}">
                        @csrf
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" required>
                            <input type="hidden" name="month" class="pass_month" value="{{$t_month}}">
                                <input type="hidden" name="date3d" class="pass_date_3d" value="{{$section->date}}">
                            <input type="hidden" name="year" class="pass_year" value="{{$cur_year}}">
                            <input type="hidden" name="lucky_number" class="lucky_number_cl" value="{{$numberLucky}}">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- refund password model --}}
    <div class="modal fade modal-refund">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Refund Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/refund_3d') }}">
                        @csrf
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" required>
                            <input type="hidden" name="month" class="pass_month">
                            <input type="hidden" name="date3d" class="pass_date_3d">
                            <input type="hidden" name="year" class="pass_year">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- bet date history --}}
    

@endsection
@section('scripts')
    <script>
        //refresh
        $('.reload').click(function() {
            let timerInterval;
                Swal.fire({
                title: "Thanks for waiting",
                html: "Waiting to the 2D Dashboard",
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
                var section = $('.time_section').val()
                window.location = `${section}`;
        });

       


        $('.year').change(function() {
            let timerInterval;
                Swal.fire({
                title: "Thanks for waiting",
                html: "Waiting to the 2D Dashboard",
                timer: 30000,
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
            var year = $('.year').val();
            var month = $('.month').val();
            history.pushState(null, '', '?year=' + year + '&month=' + month)
            window.location.reload()
        });

        $('.month').change(function() {
            let timerInterval;
                Swal.fire({
                title: "Thanks for waiting",
                html: "Waiting to the 2D Dashboard",
                timer: 30000,
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
            var year = $('.year').val();
            var month = $('.month').val();
            history.pushState(null, '', '?year=' + year + '&month=' + month)
            window.location.reload()
        });
    </script>
@endsection