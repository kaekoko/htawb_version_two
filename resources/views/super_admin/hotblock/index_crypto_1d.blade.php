@extends('super_admin.backend_layout.app')

@section('title', 'Crypto 1D Hot & Block Numbers')

@section('hotblock-c1d-active', 'active')

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
                            <div class="col-md-2">
                                <button type="button" id="{{ $block->id }}" data-type="block" class="btn btn-dark add-modal btn-block">Create</button>
                            </div>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('../js/hotblock_crypto_1d.js') }}"></script>
@endsection
@endsection
