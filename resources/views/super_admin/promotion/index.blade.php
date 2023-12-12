@extends('super_admin.backend_layout.app')

@section('title', 'Promotion List')

@section('dashboard-promotion-list-active')

@section('content')
    <div class="row">
       
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
                            <th>UserName</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Bet</th>
                            <th>precent</th>
                            <th>Promotion Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                            <tbody>
                        @foreach($promo as $p)
                            <tr  data-entry-id="{{ $p->id }}">
                                <td>{{$p->user->name}}</td>
                                <td>{{$p->start_date}}</td>
                                <td>{{$p->end_date}}</td>
                                <td class='text-danger'>{{$p->total_bet}}</td>
                                <td>{{$p->precent}} %</td>
                                <td>{{$p->promotion_amount}}  <span class="badge {{ $p->status == '0' ? 'bg-danger' : 'bg-success' }}">
                                {{ $p->status == '0' ? 'no paid' : 'paid' }}
                                </span></td>
                                <td>{{$p->created_at}}</td>


                                <td><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#pop{{$p->id}}">
                                                <i class="fa fa-eye"></i>
                                            </button></td>

                                <div class="modal fade" id="pop{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered " role="document">
                                            <div class="modal-content bg-dark text-white">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Calculation Explain</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body bg-dark text-white text-center">
                                                    <p>User Old Balance   =  <span class='ml-4'> {{$p->user->balance ?? 0}}</span>
                                                    <p>
                                                    <p>Cash Out Amount    =  <span class='ml-4'> {{$p->cashout_amount ?? 0}}</span>
                                                    <p>
                                                    <p>Two Lottery Bet    =   <span class='ml-4'>{{$p->two_amount ?? 0}}</span>
                                                    <p>
                                                    <p>Three Lottery Bet  =   <span class='ml-4'>{{$p->three_amount ?? 0}}</span>
                                                    <p>
                                                    <p>One Lottery Bet   =   <span class='ml-4'>{{$p->one_amount ?? 0}}</span>
                                                    <p>
                                                    <p>Crypto One Lottery Bet   =   <span class='ml-4'>{{$p->crypto_one_amount ?? 0}}</span>
                                                    <p>
                                                    <p>Crypto Two Lottery Bet   =   <span class='ml-4'>{{$p->crypto_two_amount ?? 0}}</span>
                                                    <p>
                                                    <p>Cash In Amount  =   <span class='ml-4'>{{$p->cashin_amount ?? 0}}</span>
                                                    <p>
                                        
                                                    <p class='border border-3 rounded-3 p-2 border-warning'>Total Bet          =   {{$p->total_bet}}  <span class='ml-4'>{{$p->precent}} %   <span class='mx-2'>=</span>  </span>  <span class='text-success'>{{$p->promotion_amount}} </span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
            window.location = '/super_admin/bet_slip_c1d';
        });
    </script>
@endsection
