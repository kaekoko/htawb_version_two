@extends('super_admin.backend_layout.app')

@section('title', '3D Lucky Number')

@section('lucky-number-3d-active', 'active')

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
        <div class="p-2">
            <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <select class="form-control select2 month">
                    @foreach ($months as $key=>$item )
                        <option value="{{ $key }}"
                            @if(request()->month)
                                @if ( $key == request()->month)
                                    selected
                                @endif
                            @else
                                @if ( $key == $cur_month)
                                    selected
                                @endif
                            @endif>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
                </div>
            </div>
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
                                    <th scope="col">3D</th>
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
                                        <td>{{ $item->section }}</td>
                                        <td>{{ date('Y-m-d', strtotime($item->create_date)) }}</td>
                                        <td>
                                            @if( $item->approve == 0 )
                                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-default-{{ $item->id }}"><i class="fa fa-solid fa-pen"></i></button>
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
                                                    <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/lucky_number/approve_3d/'.$item->id) }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="name">Section</label>
                                                                    <input type="text" name="section" class="form-control" value="{{ $item->section }}" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="name">Date</label>
                                                                    <input type="text" name="date" class="form-control" value="{{ $item->create_date }}" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="name">Lucky Number</label>
                                                                    <input type="text" name="lucky_number" class="form-control" value="{{ $item->lucky_number }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" name="approve" type="checkbox" id="customCheckbox{{$item->id}}" value="1">
                                                                    <label for="customCheckbox{{$item->id}}" class="custom-control-label">Approve</label>
                                                                </div>
                                                            </div>
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

        //Filter month
        $('.year').change(function() {
            var year = $('.year').val();
            var month = $('.month').val();
            history.pushState(null, '', '?year='+year+'&month='+month)
            window.location.reload()
        });

        $('.month').change(function() {
            var year = $('.year').val();
            var month = $('.month').val();
            history.pushState(null, '', '?year='+year+'&month='+month)
            window.location.reload()
        });

        $('.reload').click(function(){
            window.location = '/super_admin/lucky_number_3d';
        });

    </script>
@endsection
