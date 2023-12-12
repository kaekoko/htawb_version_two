@extends('super_admin.backend_layout.app')

@section('title', 'Edit 2D Lucky Number')

@section('lucky-number-2d-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/lucky_number') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('lucky_number.update', $lucky_number->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Lucky Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="lucky_number" value="{{ $lucky_number->lucky_number }}"  placeholder="Enter Lucky Number">
                        </div>
                        <div class="form-group">
                            <label>Section<span class="text-danger">*</span></label>
                            <select name="section" class="form-control select2">
                                @foreach ($sections as $s)
                                    <option value=" {{ \Carbon\Carbon::createFromFormat('H:i:s',$s->time_section)->format('g:i A') }}"
                                        @if (\Carbon\Carbon::createFromFormat('H:i:s',$s->time_section)->format('g:i A') == $lucky_number->section)
                                            selected
                                        @endif >
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s',$s->time_section)->format('g:i A') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Category<span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control select2">
                                @foreach ($categories as $c)
                                <option value="{{ $c->id }}"
                                    @if ($c->id == $lucky_number->category_id)
                                        selected
                                    @endif >
                                    {{ $c->name }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Create Date<span class="text-danger">*</span></label>
                              <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                  <input type="text" name="create_date" class="form-control datetimepicker-input" data-target="#reservationdate" value="{!! date('m-d-Y', strtotime($lucky_number->create_date)) !!}"/>
                                  <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                              </div>
                          </div>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\LuckyNumberRequest') !!}
@endsection
