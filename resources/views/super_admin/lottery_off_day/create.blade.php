@extends('super_admin.backend_layout.app')

@section('title', 'Create Lottery Off Day')

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
                        <form method="POST" enctype="multipart/form-data" action="{{ route('lottery_off_day.store') }}">
                            @csrf
                            <div class="form-group">
                                <label>Category<span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control select2">
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date:</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" name="off_day" class="form-control form-control-sm datetimepicker-input" data-target="#reservationdate"/>
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
{!! JsValidator::formRequest('App\Http\Requests\LotteryOffDayRequest') !!}
@endsection
