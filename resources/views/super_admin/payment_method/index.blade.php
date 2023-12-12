@extends('super_admin.backend_layout.app')

@section('title', 'Payment Method')

@section('payment-method-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/payment_method/create') }}" class="btn btn-dark btn-sm" title="Add Payment Method">
        <i class="fa fa-plus" aria-hidden="true"></i> Add Payment Method
    </a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table" class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Information</th>
                                    <th>Logo</th>
                                    <th>Type</th>
                                    <th>Amount %</th>
                                    <th>Exchange Rate</th>
                                    <th>Show Cashin</th>
                                    <th>Show Cashout</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payment_methods as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->name ?? '-' }}</td>
                                        <td class="p-3">
                                            @if ($item->type == 'mobile-topup')
                                                {{ '-' }}
                                            @else
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Account Number</th>
                                                            <th>Account Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($item->payment_account_numbers as $account_number)
                                                            <tr>
                                                                <td>{{ $account_number->account_number ?? '-' }}</td>
                                                                <td>{{ $account_number->account_name ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->logo)
                                                <img src="{{ asset($item->logo) }}" width="150">
                                            @endif
                                        </td>
                                        <td>
                                            <p class="text-capitalize">
                                                @if ($item->type == 'foreign-banking')
                                                   {{"Thai Banking"}}
                                                @else
                                                    {{ $item->type }}
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-capitalize">
                                                @if ($item->type == 'mobile-topup')
                                                    {{ $item->percentage }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-capitalize">
                                                @if ($item->type == 'foreign-banking')
                                                    {{ $item->exchange_rate }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <input data-id="{{ $item->id }}" class="toggle-class-cashin"
                                                type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                data-toggle="toggle" data-on="On" data-off="Off" data-size="small"
                                                {{ $item->cash_in_status === 0 ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input data-id="{{ $item->id }}" class="toggle-class-cashout"
                                                type="checkbox" data-onstyle="success" data-offstyle="danger"
                                                data-toggle="toggle" data-on="On" data-off="Off" data-size="small"
                                                {{ $item->cash_out_status === 0 ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input data-id="{{ $item->id }}" class="toggle-class" type="checkbox"
                                                data-onstyle="success" data-offstyle="danger" data-toggle="toggle"
                                                data-on="On" data-off="Off" data-size="small"
                                                {{ $item->status === 0 ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <a href="{{ url('super_admin/payment_method/' . $item->id . '/edit') }}"
                                                title="Edit"><button class="btn btn-primary btn-sm"><i
                                                        class="fa fa-solid fa-pen"></i></button></a>
                                            <form id="postBtn{{ $item->id }}" method="post"
                                                action="{{ route('payment_method.destroy', $item->id) }}"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 0 : 1;
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: '{{ url('super_admin/payment_method/update_status') }}',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.status == 0) {
                        toastr.success(data.name + ' Status Open!')
                    } else {
                        toastr.error(data.name + ' Status Close!')
                    }
                }
            });
        });

        $('.toggle-class-cashin').change(function() {
            var status = $(this).prop('checked') == true ? 0 : 1;
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: 'payment_method/update_cashin_status',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.status == 0) {
                        toastr.success(data.name + ' Cashin Status Open!')
                    } else {
                        toastr.error(data.name + ' Cashin Status Close!')
                    }
                }
            });
        });

        $('.toggle-class-cashout').change(function() {
            var status = $(this).prop('checked') == true ? 0 : 1;
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "payment_method/update_cashout_status",
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.status == 0) {
                        toastr.success(data.name + ' Cashout Status Open!')
                    } else {
                        toastr.error(data.name + ' Cashout Status Close!')
                    }
                }
            });
        });
    </script>
@endsection
