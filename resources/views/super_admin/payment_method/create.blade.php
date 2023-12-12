@extends('super_admin.backend_layout.app')

@section('title', 'Create Payment Method')

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
                    <form method="POST" enctype="multipart/form-data" action="{{ route('payment_method.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Payment Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="name"
                                placeholder="Enter Payment Name">
                        </div>
                        <div id="pay-info" class="form-group">
                            <label for="">Information</label>
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
                                <option value="pay">Pay</option>
                                <option value="banking">Local Banking</option>
                                <option value="foreign-banking">Thai Banking</option>
                                <option value="mobile-topup">Mobile Top-up</option>
                            </select>
                        </div>
                        {{-- mobile-topup --}}
                        <div id="percentage" class="form-group" style="display: none;">
                            <label for="type">Percentage</label>
                            <input type="number" value="0" name="percentage" placeholder="Enter Percentage"
                                id="input-percentage" class="form-control">
                        </div>
                        {{-- foreign-banking --}}
                        <div id="foreign-banking" class="form-group" style="display: none;">
                            <label for="type">Exchange Rate ( 1unit )</label>
                            <div class="row d-flex align-items-center">
                                <div class="col-2">
                                    <input type="number" value="1" min="1" name="exchange_rate"
                                        placeholder="Enter Exchange Rate" id="input-exchange-rate" class="form-control">
                                </div>
                                <div class="col-10">
                                    <span>Ks</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Logo</label>
                            <input type="file" class="form-control" name="logo" onchange="loadFile(event)">
                            <img id="output" width="200" class="mt-3" />
                        </div>
                        <button type="submit" class="btn btn-dark">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\PaymentMethodRequest') !!}
    <script>
        function mobileTopupFun() {
            if ($("#pay-method").val() === "mobile-topup") {
                $("#percentage").show();
                $("#pay-info").hide();
            } else {
                $("#percentage").hide();
                $("#pay-info").show();
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
            mobileTopupFun();
            foreignBankingFun();

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
        })

        // on change payment
        $("#pay-method").on("change", function() {
            mobileTopupFun();
            foreignBankingFun();
        })
    </script>
@endsection
