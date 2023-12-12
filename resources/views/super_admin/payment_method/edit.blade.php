@extends('super_admin.backend_layout.app')

@section('title', 'Payment Method Edit')

@section('payment-method-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/payment_method') }}" title="Back"><button class="btn btn-default btn-sm"><i
                class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data"
                        action="{{ route('payment_method.update', $payment_method->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="name">Payment Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="name"
                                value="{{ $payment_method->name }}" placeholder="Enter Payment Name">
                        </div>
                        <div style="display: @if ($payment_method->type != 'mobile-topup') block; @else none; @endif" id="info-label"
                            class="form-group">
                            <label class="form-label">Information</label>
                            <table class="table table-bordered">
                                <tr>
                                    @foreach ($payment_method->payment_account_numbers as $value)
                                        <input type="hidden" name="edit_id[]" value="{{ $value->id }}">
                                <tr>
                                    <td>
                                        <label>Account Number</label>
                                        <input type="text" name="edit_account_number[]" class="form-control mb-3"
                                            value="{{ $value->account_number }}">
                                    </td>
                                    <td>
                                        <label>Account Name</label>
                                        <input type="text" name="edit_account_name[]" class="form-control mb-3"
                                            value="{{ $value->account_name }}">
                                    </td>
                                    <td width="200">
                                        <a href="{{ route('paymentaccount.delete', $value->id) }}"
                                            onclick="return confirm('Confirm delete?')"
                                            class="btn btn-danger mt-4">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                                </tr>
                            </table>
                        </div>
                        <div style="display: @if ($payment_method->type != 'mobile-topup') block; @else none; @endif" id="info-input"
                            class="form-group">
                            <table class="table table-bordered" id="account_number_field">
                                <tr>
                                    <td>
                                        <label>Account Number</label>
                                        <input type="text" name="account_number[]" class="form-control">
                                    </td>
                                    <td>
                                        <label>Account Name</label>
                                        <input type="text" name="account_name[]" class="form-control">
                                    </td>
                                    <td width="200"><button type="button" name="add_account_number"
                                            id="add_account_number" class="btn btn-success mt-4">Add More</button></td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="type">Type </label>
                            <select id="pay-method" name="type" class="form-control">
                                <option value="pay" @if ($payment_method->type == 'pay') selected @endif>Pay</option>
                                <option value="banking" @if ($payment_method->type == 'banking') selected @endif>Banking</option>
                                <option value="foreign-banking" @if ($payment_method->type == 'foreign-banking') selected @endif>Thai
                                    Banking</option>
                                <option value="mobile-topup" @if ($payment_method->type == 'mobile-topup') selected @endif>Mobile Top-up
                                </option>
                            </select>
                        </div>
                        {{-- mobile-topup --}}
                        <div id="percentage" style="display: @if ($payment_method->type == 'mobile-topup') block; @else none; @endif"
                            class="form-group">
                            <label for="type">Percentage</label>
                            <input type="number"
                                value="{{ empty($payment_method->percentage) ? 0 : $payment_method->percentage }}"
                                name="percentage" placeholder="Enter Percentage" id="input-percentage" class="form-control">
                        </div>
                        {{-- foreign-banking --}}
                        <div id="foreign-banking"
                            style="display: @if ($payment_method->type == 'foreign-banking') block; @else none; @endif" class="form-group">
                            <label for="type">Exchange Rate ( 1unit )</label>
                            <div class="row d-flex align-items-center">
                                <div class="col-2">
                                    <input type="number" value="{{ $payment_method->exchange_rate }}" name="exchange_rate"
                                        min="1" placeholder="Enter Exchange Rate" id="input-foreign-banking"
                                        class="form-control">
                                </div>
                                <div class="col-10">
                                    <span>Ks</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Logo</label>
                            <input type="file" class="form-control" name="logo" onchange="loadFile(event)">
                            @if ($payment_method->logo)
                                <img src="{{ asset($payment_method->logo) }}" width="150" class="mt-3"> <br>
                            @endif
                            <img id="output" width="200" class="mt-3" />
                        </div>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function mobileTopupFun() {
            if ($("#pay-method").val() === "mobile-topup") {
                $("#percentage").show();
                $("#info-label").hide();
                $("#info-input").hide();
            } else {
                $("#percentage").hide();
                $("#info-label").show();
                $("#info-input").show();
            }
        }

        function foreignBankingFun() {
            if ($("#pay-method").val() === "foreign-banking") {
                $("#foreign-banking").show();
            } else {
                $("#foreign-banking").hide();
            }
        }

        $(document).ready(function() {
            var c = 1;
            $('#add_account_number').click(function() {
                c++;
                $('#account_number_field').append('<tr id="row' + c +
                    '"><td><label>Account Number</label><input type="text" name="account_number[]" class="form-control mb-3"></td><td><label>Account Name</label><input type="text" name="account_name[]" class="form-control"></td><td><button type="button" name="remove" id="' +
                    c + '" class="btn btn-danger mt-4 value_btn_remove">X</button></td></tr>');
            });
            $(document).on('click', '.value_btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });

            // on change payment
            $("#pay-method").on("change", function() {
                mobileTopupFun();
                foreignBankingFun();
            })
        })
    </script>
@endsection
