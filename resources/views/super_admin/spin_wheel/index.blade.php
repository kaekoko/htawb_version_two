@extends('super_admin.backend_layout.app')

@section('title', 'Spin Wheel')

@section('spin-wheel-active', 'active')

@section('content')

    <ul class="nav nav-tabs" id="account" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="contact-1-tab" data-toggle="tab" href="#contact-1" role="tab" aria-controls="contact-1" aria-selected="false">Lucky History</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Wheel list</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">User Wheel Count</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="profile-1-tab" data-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="false">Setting</a>
        </li>
    </ul>
    <div class="tab-content" id="accountContent">
        <div class="tab-pane active" id="contact-1" role="tabpanel" aria-labelledby="contact-1-tab">
            <br>
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
            <div class="card card-dark card-outline">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_senior_agent" class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th><th>User</th><th>Amount</th><th>Time</th><th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lucky_history as $key=>$item)
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>{{ $item->amount }}</td>
                                            <td>{{ date('h:i A', strtotime($item->created_at)) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <br><br>
            <div class="card card-dark card-outline">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th><th>Name</th><th>Percent</th><th>Image</th><th>Degree</th><th>Amount</th><th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($spin_wheels as $n=>$item)
                                            <tr>
                                                <td>{{ $n+1 }}</td>
                                                <td>{{ $item->name ?? '-' }}</td>
                                                <td>{{ $item->percent ?? '-' }}</td>
                                                <td>
                                                    @if ($item->image)
                                                        <img src="{{ asset($item->image) }}" width="50" class="mt-1">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $item->degree ?? '-' }}</td>
                                                <td>{{ $item->amount ?? '-' }}</td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-default-{{ $item->id }}"><i class="fa fa-solid fa-pen"></i></button>
                                                </td>
                                            </tr>
                                            {{-- spin wheel edit model --}}
                                            <div class="modal fade" id="modal-default-{{ $item->id }}">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Spin Wheel {{ $item->id }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/spin_wheel/update/'.$item->id) }}">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="name">Name</label>
                                                                            <input type="text" name="name" class="form-control" value="{{ $item->name }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="name">Percent</label>
                                                                            <input type="text" name="percent" class="form-control" value="{{ $item->percent }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="name">Image</label>
                                                                            <input type="file" name="image" class="form-control" value="{{ $item->image }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="name">Degree</label>
                                                                            <input type="text" name="degree" class="form-control" value="{{ $item->degree }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="name">Amount</label>
                                                                            <input type="text" name="amount" class="form-control" value="{{ $item->amount }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button type="submit" class="btn btn-dark">Update</button>
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
        </div>
        <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="contact-tab">
            <br><br>
            <div class="card card-info card-outline">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_super_admin" class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th><th>User</th><th>Count</th><th>Update Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($spin_wheel_users as $key=>$item)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->count }}</td>
                                                <td>{{ $item->updated_at->format('h:i A , d.m.Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="profile-1" role="tabpanel" aria-labelledby="profile-1-tab">
            <br><br>
            <div class="card card-dark card-outline">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Message</th><th>Spin Wheel On/Off</th><th>Refresh</th><th title="Only Lvl 2 Users for free spin">Only Lvl 2</th><th>Spin Free</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/spin_wheel/update/'.'9') }}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <textarea class="form-control" name="message" rows="3" placeholder="Enter ..." style="height: 102px;">{{ $message->message }}</textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-dark">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>
                                                @if($wheel_on_off->type == 'wheel_on')
                                                    <button type="button" class="btn btn-success spin_on">On</button>
                                                @endif
                                                @if($wheel_on_off->type == 'wheel_off')
                                                    <button type="button" class="btn btn-danger spin_off">Off</button>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning refresh">Refresh</button>
                                            </td>
                                            <td>
                                                <input data-id="{{$lvl_2->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="On" data-off="Off" data-size="small" {{ $lvl_2->type == 'lvl_2_on' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input data-id="{{$sp_free->id}}" class="toggle-class-1" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="On" data-off="Off" data-size="small" {{ $sp_free->type == 'free_on' ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-dark card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-dark range">Add Rage Setting</button>
                            <br><br>
                            <div class="table-responsive">
                                <table id="data_table_senior_agent" class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th><th>Start Amount</th><th>End Amount</th><th>Count</th><th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ranges as $key=>$item)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $item->start_amount }}</td>
                                                <td>{{ $item->end_amount }}</td>
                                                <td>{{ $item->count }}</td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-default-range-{{ $item->id }}"><i class="fa fa-solid fa-pen"></i></button>
                                                    <form id="postBtn{{ $item->id }}" method="post" action="{{ url('super_admin/spin_wheel/range_amount/delete/'.$item->id) }}" style="display: inline;">
                                                        @csrf
                                                    </form>
                                                    <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            {{-- spin wheel range edit model --}}
                                            <div class="modal fade" id="modal-default-range-{{ $item->id }}">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Spin Wheel Range Amount</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/spin_wheel/range_amount/'.$item->id) }}">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <input type="text" name="start_amount" class="form-control" value="{{ $item->start_amount }}" placeholder="eg:10000" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <input type="text" name="end_amount" class="form-control" value="{{ $item->end_amount }}" placeholder="eg:20000" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <input type="text" name="count" class="form-control" value="{{ $item->count }}" placeholder="eg:5" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button type="submit" class="btn btn-dark">Update</button>
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
        </div>
    </div>


    {{-- spin on password model --}}
    <div class="modal fade modal-spin-on">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Spin Off</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/spin_wheel/update/'.'10') }}">
                    @csrf
                    <div class="form-group">
                      <input type="password" name="password" class="form-control" placeholder="Admin Password" required>
                      <input type="hidden" name="type" value="wheel_off">
                    </div>
                    <button type="submit" class="btn btn-dark">Update</button>
                </form>
            </div>
            </div>
        </div>
    </div>

    {{-- spin off password model --}}
    <div class="modal fade modal-spin-off">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Spin On</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/spin_wheel/update/'.'10') }}">
                    @csrf
                    <div class="form-group">
                      <input type="password" name="password" class="form-control" placeholder="Admin Password" required>
                      <input type="hidden" name="type" value="wheel_on">
                    </div>
                    <button type="submit" class="btn btn-dark">Update</button>
                </form>
            </div>
            </div>
        </div>
    </div>

    {{-- refresh password model --}}
    <div class="modal fade modal-refresh">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refresh</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/spin_wheel/refresh') }}">
                    @csrf
                    <div class="form-group">
                      <input type="password" name="password" class="form-control" placeholder="Admin Password" required>
                    </div>
                    <button type="submit" class="btn btn-dark">Update</button>
                </form>
            </div>
            </div>
        </div>
    </div>

    {{-- refresh password model --}}
    <div class="modal fade modal-range">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Spin Wheel Range Amount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/spin_wheel/range_amount') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="start_amount" class="form-control" placeholder="eg:10000" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="end_amount" class="form-control" placeholder="eg:20000" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="count" class="form-control" placeholder="eg:5" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-dark">Crate</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.spin_on').click(function(){
            $('.modal-spin-on').modal('show');
        });

        $('.spin_off').click(function(){
            $('.modal-spin-off').modal('show');
        });

        $('.refresh').click(function(){
            $('.modal-refresh').modal('show');
        });

        $('.range').click(function(){
            $('.modal-range').modal('show');
        });

        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 'lvl_2_on' : 'lvl_2_off';
            var id = $(this).data('id');
            $.ajax({
                type:'POST',
                url:'{{ url("super_admin/spin_wheel/lvl_2_on_off") }}',
                data:{ id:id, status:status },
                success:function(data){
                    if( data.status == 'lvl_2_on' ){
                        toastr.success('Lvl 2 users only for free spin')
                    }else{
                        toastr.success('Both users for free spin')
                    }
                }
            });
        });

        $('.toggle-class-1').change(function() {
            var status = $(this).prop('checked') == true ? 'free_on' : 'free_off';
            var id = $(this).data('id');
            $.ajax({
                type:'POST',
                url:'{{ url("super_admin/spin_wheel/spin_free_on_off") }}',
                data:{ id:id, status:status },
                success:function(data){
                    if( data.status == 'free_on' ){
                        toastr.success('Spin Free On')
                    }else{
                        toastr.error('Spin Free Off')
                    }
                }
            });
        });

        $(function() {

            var urlParams = new URLSearchParams(window.location.search);
            //calendar
            if(urlParams.has('start_date')){
                var start = moment(urlParams.get('start_date'));
                var end = moment(urlParams.get('end_date'));
            }else{
                var start = moment();
                var end = moment();
            }

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            //calendar

            //refresh
            $('.reload').click(function(){
                window.location = 'spin_wheel';
            });

            //by date daily filter
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                var startDate = $('#reportrange').data('daterangepicker').startDate;
                var endDate = $('#reportrange').data('daterangepicker').endDate;
                var start = startDate.format('YYYY-MM-DD');
                var end = endDate.format('YYYY-MM-DD');
                history.pushState(null, '', '?start_date='+start+'&end_date='+end)
                window.location.reload()
            });

        });
    </script>
@endsection
