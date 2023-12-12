@extends('super_admin.backend_layout.app')

@section('title', 'Create 3D Section')

@section('section-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/section') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="row">
        <div class="col-6">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Create Section</h3>
                </div>
                <div class="card-body">
                    <!-- time Picker -->
                    <div class="bootstrap-timepicker">
                        <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/section_3d/store') }}">
                            @csrf
                            <div class="form-group">
                                <label>Time</label>
                                <div class="input-group date" id="timepicker1" data-target-input="nearest">
                                    <input type="text" name="time" class="form-control form-control-sm datetimepicker-input" data-target="#timepicker1">
                                    <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Close Time</label>
                                <div class="input-group date" id="timepicker2" data-target-input="nearest">
                                    <input type="text" name="close_time" class="form-control form-control-sm datetimepicker-input" data-target="#timepicker2">
                                    <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" name="date" class="form-control form-control-sm datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                  </div>
                            </div>
                            <button type="submit" class="btn btn-dark">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\Section3dRequest') !!}
    <script>
        //Timepicker
        $('#timepicker1').datetimepicker({
            format: 'LT'
        })
        $('#timepicker2').datetimepicker({
            format: 'LT'
        })
    </script>
@endsection
