@extends('super_admin.backend_layout.app')

@section('title', 'Edit Lottery Off Day')

@section('lottery-off-day-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/lottery_off_day') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="row">
        <div class="col-6">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Create Lottery Off Day</h3>
                </div>
                <div class="card-body">
                    <!-- time Picker -->
                    <div class="bootstrap-timepicker">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('lottery_off_day.update', $lottery_off_day->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>Category<span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control select2">
                                    @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        @if ($c->id == $lottery_off_day->category_id)
                                            selected
                                        @endif >
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date:</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" name="off_day" class="form-control form-control-sm datetimepicker-input" data-target="#reservationdate" value="{!! date('m-d-Y', strtotime($lottery_off_day->off_day)) !!}"/>
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
    {!! JsValidator::formRequest('App\Http\Requests\LotteryOffDayRequest') !!}
@endsection
