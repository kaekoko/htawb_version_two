@extends('super_admin.backend_layout.app')

@section('title', '2D Lucky Number')

@section('lucky-number-2d-active', 'active')

@section('content')
    <div class="d-flex flex-wrap">
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
                <input type="text" name="bet_date" class="form-control form-control-sm date" value="{{ request()->date ?? date('Y-m-d') }}">
                </div>
            </div>
        </div>
        <div class="p-2">
            <a href="{{ url('/super_admin/lucky_number/create') }}" class="btn btn-dark btn-sm" title="Add Lucky Number">
                <i class="fa fa-plus" aria-hidden="true"></i> Add Lucky Number
            </a>
        </div>
    </div>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">2D</th>
                                    <th scope="col">Set</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lucky_numbers as $item)
                                <tr>
                                <td></td>
                                <td>{{ $item->lucky_number }}</td>
                                <td>{{ $item->set ?? '-' }}</td>
                                <td>{{ $item->value ?? '-' }}</td>
                                <td>{{ $item->section }}</td>
                                <td>{{ date('Y-m-d', strtotime($item->create_date)) }}</td>
                                <td>
                                    @if ($item->approve == 0)
                                    <form action="{{ url('super_admin/lucky_number/approve/'.$item->id) }}" method="POST">
                                     @csrf
                                     <input type="hidden" name="create_date" value="{{ date('Y-m-d', strtotime($item->create_date)) }}">
                                     <input type="hidden" name="lucky_number" value="{{ $item->lucky_number }}">
                                     <input type="hidden" name="section" value="{{$item->section}}">
                                     <input type="hidden" name="id" value="{{$item->id}}">
                                     <button class="btn btn-info btn-sm" >Approve</button>
                                    </form>
                                 @else
                                     <p class="badge badge-info">done</p>
                                 @endif
                                </td>
                                </tr>
                                {{-- lucky number edit model --}}
                                <div class="modal fade" id="modal-default-{{ $item->id }}">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Lucky Number</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/lucky_number/approve/'.$item->id) }}">
                                                    @csrf
                                                    <div class="row">
                                                        
                                                        <div class="col-md-6">
                                                            <button type="submit" class="btn btn-dark float-right">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            history.pushState(null, '', '?date='+date)
            window.location.reload()
        });

        $('.reload').click(function(){
            window.location = '/super_admin/lucky_number';
        });

    </script>
@endsection
