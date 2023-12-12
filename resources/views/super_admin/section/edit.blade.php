@extends('super_admin.backend_layout.app')

@section('title', 'Edit 2D Time Section')

@section('section-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/section') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="row">
        <div class="col-6">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Edit Section</h3>
                </div>
                <div class="card-body">
                    <!-- time Picker -->
                    <div class="bootstrap-timepicker">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('section.update', $time_section->id) }}">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label>Time picker:</label>
                                <div class="input-group date" id="timepicker" data-target-input="nearest">
                                    <input type="text" name="time_section" class="form-control datetimepicker-input" data-target="#timepicker" value="{{ \Carbon\Carbon::createFromFormat('H:i:s',$time_section->time_section)->format('h:i A') }}">
                                    <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Open Time</label>
                                <div class="input-group date" id="timepicker1" data-target-input="nearest">
                                    <input type="text" name="open_time" class="form-control datetimepicker-input" data-target="#timepicker1" value="{{ \Carbon\Carbon::createFromFormat('H:i:s',$time_section->open_time)->format('h:i A') }}">
                                    <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Close Time</label>
                                <div class="input-group date" id="timepicker2" data-target-input="nearest">
                                    <input type="text" name="close_time" class="form-control datetimepicker-input" data-target="#timepicker2" value="{{ \Carbon\Carbon::createFromFormat('H:i:s',$time_section->close_time)->format('h:i A') }}">
                                    <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>ODD</label>
                                <div class="input-group date" id="timepicker2" data-target-input="nearest">
                                    <input type="text" name="odd" class="form-control "  value="{{ $time_section->odd }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\SectionRequest') !!}
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
