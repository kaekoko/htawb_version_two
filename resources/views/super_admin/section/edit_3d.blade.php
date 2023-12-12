@extends('super_admin.backend_layout.app')

@section('title', 'Edit 3D Section')

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
                        <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/section_3d/update/'.$section_3d->id) }}">
                            @csrf
                            <div class="form-group">
                                <label>Time</label>
                                <div class="input-group date" id="timepicker1" data-target-input="nearest">
                                    <input type="text" name="time" class="form-control form-control-sm datetimepicker-input" data-target="#timepicker1" value="{{ \Carbon\Carbon::createFromFormat('H:i:s',$section_3d->time)->format('h:i A') }}">
                                    <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Close Time</label>
                                <div class="input-group date" id="timepicker2" data-target-input="nearest">
                                    <input type="text" name="close_time" class="form-control form-control-sm datetimepicker-input" data-target="#timepicker2" value="{{ \Carbon\Carbon::createFromFormat('H:i:s',$section_3d->close_time)->format('h:i A') }}">
                                    <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <i class="far fa-calendar-alt"></i>
                                    </span>
                                  </div>
                                    <select name="date" class="form-control select2">
                                        @if($section_3d->id == 1)
                                            @foreach ($option_1 as $item)
                                                <option value="{{ $item }}"
                                                    @if ($item == $section_3d->date)
                                                        selected
                                                    @endif >
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        @endif
                                        @if($section_3d->id == 2)
                                            @foreach ($option_2 as $item)
                                                <option value="{{ $item }}"
                                                    @if ($item == $section_3d->date)
                                                        selected
                                                    @endif >
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
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
