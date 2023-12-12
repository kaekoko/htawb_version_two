@extends('super_admin.backend_layout.app')

@section('title', 'Create 1D Lucky Number')

@section('lucky-number-1d-active', 'active')

@section('content')
<a href="{{ url('/super_admin/lucky_number_1d') }}" title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="d-flex justify-content-center">
                <div id="time" class="mt-2 pl-3"></div>
            </div>
            <div class="row mt-3 d-flex justify-content-center align-items-center ">
                    <div class='bg-dark rounded-5 p-5  border border-danger rounded-lg border-3' >
            @foreach($customs as $custom)
                        <div >
                            
                        <form method="POST" action="{{ url('/super_admin/luckyOne',date("h:i A", strtotime($custom->time_section))) }}" class="row mt-3 d-flex justify-content-center align-items-center">
                            @csrf
                        <div class="col-4 text-center border border-warning rounded-3">
                            <div type="text" disabled name="" value= id="" class='py-3'>{{ date("h:i A", strtotime($custom->time_section)) }}</div>
                        </div>
                        <div class="col-3">
                            <input required type="text"  name="two_num" id="" class="form-control border border-warning border-3">
                        </div>
                        <div class="col-2">
                            <button class='btn btn-success'>create</button>
                        </div>
                        </form>
                        </div>
                        @endforeach
                        </div>
            </div>
            <div class="container mt-5" id="warningclose">
                <h1 class="text mt-2">Close Panel In Weekend. Number can't be add manually.</h1>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('../js/luckynumberOned.js') }}"></script>
{{-- {!! JsValidator::formRequest('App\Http\Requests\LuckyNumberRequest') !!} --}}
@endsection
