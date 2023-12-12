@extends('super_admin.backend_layout.app')

@section('title', '1D Hot & Block Numbers')

@section('hotblock-1d-active', 'active')

@section('content')

<div class="card card-dark card-outline">
    <div class="card-body">
         <div class="d-flex justify-content-center">
            <div id="time" class="mt-2 pl-3"></div>
        </div>
        <ul class="nav nav-tabs" id="account" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Hot Numbers</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Block Numbers</a>
            </li>
        </ul>
        <div class="tab-content" id="accountContent">
            <div class="tab-pane active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="row">
                            @foreach($hots as $hot)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-dark hotsection_{{ $hot->id }}" disabled>{{ $hot->section }}</button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <input type="text" id="twod_{{ $hot->id }}" class="form-control" value={{ $hot->hot_number }}>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" id="hotamt_{{ $hot->id }}" class="form-control" placeholder="Enter Hot Amount" value={{ $hot->hot_amount }}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="{{ $hot->id }}" data-type="hot" class="btn btn-dark add-modal btn-block">Create</button>
                            </div>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="row">
                            @foreach($blocks as $block)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-dark blocksection_{{ $block->id }}" disabled>{{ $block->section }}</button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" id="block_{{ $block->id }}" class="form-control" value={{ $block->block_number }}>
                                </div>
                            </div>

                            <!-- <div class="col-1">
                                <button type="button" id="{{ $block->id }}" class="btn btn-primary"
                                    data-toggle="modal" data-target="#id_{{ $block->id }}">Quick</button>
                            </div> -->

                            <div class="modal fade" id='id_{{ $block->id }}' tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Quick Selection</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button0"
                                                    onclick="updateInput(0,'{{ $block->id }}')">0</div>
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button1"
                                                    onclick="updateInput(1,'{{ $block->id }}')">1</div>
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button2"
                                                    onclick="updateInput(2,'{{ $block->id }}')">2</div>
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button3"
                                                    onclick="updateInput(3,'{{ $block->id }}')">3</div>
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button4"
                                                    onclick="updateInput(4,'{{ $block->id }}')">4</div>
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button5"
                                                    onclick="updateInput(5,'{{ $block->id }}')">5</div>
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button6"
                                                    onclick="updateInput(6,'{{ $block->id }}')">6</div>
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button7"
                                                    onclick="updateInput(7,'{{ $block->id }}')">7</div>
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button8"
                                                    onclick="updateInput(8,'{{ $block->id }}')">8</div>
                                                <div class="col-1 btn btn-dark mr-2 rounded-2" id="button9"
                                                    onclick="updateInput(9,'{{ $block->id }}')">9</div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <button type="button" id="{{ $block->id }}" data-type="block" class="btn btn-dark add-modal btn-block">Create</button>
                            </div>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5" id="warningclose">
            <h1 class="text mt-2">Close Panel In Weekend. Number can't be add manually.</h1>
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('../js/hotblock_1d.js') }}"></script>
<script>
    function updateInput(number,id) {


        if (number === 0) {
            if (document.getElementById(`block_${id}`).value.includes(
                    "00,01,02,03,04,05,06,07,08,09,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "00,01,02,03,04,05,06,07,08,09,";

            }

        }
        if (number === 1) {
            if (document.getElementById(`block_${id}`).value.includes("10,11,12,13,14,15,16,17,18,19,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "10,11,12,13,14,15,16,17,18,19,";
            }

        }
        if (number === 2) {
            if (document.getElementById(`block_${id}`).value.includes("20,21,22,23,24,25,26,27,28,29,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "20,21,22,23,24,25,26,27,28,29,";
            }
        }
        if (number === 3) {
            if (document.getElementById(`block_${id}`).value.includes("30,31,32,33,34,35,36,37,38,39,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "30,31,32,33,34,35,36,37,38,39,";
            }
        }
        if (number === 4) {
            if (document.getElementById(`block_${id}`).value.includes("40,41,42,43,44,45,46,47,48,49,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "40,41,42,43,44,45,46,47,48,49,";
            }
        }
        if (number === 5) {
            if (document.getElementById(`block_${id}`).value.includes("50,51,52,53,54,55,56,57,58,59,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "50,51,52,53,54,55,56,57,58,59,";
            }
        }
        if (number === 6) {
            if (document.getElementById(`block_${id}`).value.includes("60,61,62,63,64,65,66,67,68,69,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "60,61,62,63,64,65,66,67,68,69,";
            }
        }
        if (number === 7) {
            if (document.getElementById(`block_${id}`).value.includes("70,71,72,73,74,75,76,77,78,79,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "70,71,72,73,74,75,76,77,78,79,";
            }
        }
        if (number === 8) {
            if (document.getElementById(`block_${id}`).value.includes("80,81,82,83,84,85,86,87,88,89,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "80,81,82,83,84,85,86,87,88,89,";
            }
        }
        if (number === 9) {
            if (document.getElementById(`block_${id}`).value.includes("90,91,92,93,94,95,96,97,98,99,")) {
                document.getElementById(`block_${id}`).value += "";
            } else {
                document.getElementById(`block_${id}`).value += "90,91,92,93,94,95,96,97,98,99,";
            }
        }
    }
</script>
@endsection
@endsection
