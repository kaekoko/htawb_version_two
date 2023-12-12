@extends('super_admin.backend_layout.app')

@section('title', 'Super Admin 3D Dashboard')

@section('dashboard-3d-active', 'active')

@section('content')
    <div class="d-flex">
        <div class="p-2">
            <button class="btn btn-info btn-sm reload" title="refresh"><i class="nav-icon fas fa-retweet"></i></button>
        </div>
        <div class="p-2">
            <div class="date3d" value="{{ request()->date ? date("d", strtotime(request()->date)) : $get_section_3d }}">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" name="dashboard_3d_date" class="form-control form-control-sm date-3d"
                            value="{{ request()->date ?? date('Y-m-d', strtotime($hist_year . '-' . $hist_month . '-' . $get_section_3d)) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content" id="accountContent">
        <div class="tab-pane fade div_section active show" id="id-section" role="tabpanel" aria-labelledby="id-section-tab">
            <div class="d-flex flex-wrap clerance_div">
                @php
                    $role_id = Auth::guard('super_admin')->user()->role_id;
                @endphp
                @if ($role_id == 1 || $role_id == 2)
                    <div class="p-2">
                        <button type="button" class="mr-1 btn-sm btn btn-danger clearance">
                        </button>
                    </div>
                    <div class="p-2">
                        <button type="button" class="btn-sm btn btn-outline-success refund">Refund</button>
                    </div>
                @endif
                <div class="ml-auto p-2 update_time">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input custom-control-input-info" type="radio" checked>
                        <label for="customRadio4" class="custom-control-label">Updated <label id="update_time">0</label>s
                            ago</label>
                    </div>
                </div>
            </div>
            <div
                class="row callout justify-content-center mt-3 all_bet scrollable_3d scrollbarbackgroundblue_3d dashboard_3d_scroll">
            </div>
        </div>
    </div>
    <div class="row mb-5 mt-5">
        <div class="col-12">
            <h3 class="m-0">Over All Statistics</h3>
        </div>
    </div>
    <div class="" id="over_all_amount">
    </div>
    <div class="row mb-5 mt-5">
        <div class="col-12">
            <h3 class="m-0">Bet Slips</h3>
        </div>
    </div>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="bet_slip_dash_datatable" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>3D Date</th>
                                    <th>Total Amount</th>
                                    <th>Total Bet</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            <tbody>
                                @foreach ($bet_slips as $item)
                                    <tr>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->date_3d }}</td>
                                        <td>{{ $item->total_amount }}</td>
                                        <td>{{ $item->total_bet }}</td>
                                        <td>{{ $item->date }}</td>
                                        <td>
                                            <a href="{{ url('/super_admin/bet_slip_3d/' . $item->id) }}"
                                                title="View"><button class="btn btn-info btn-sm"><i
                                                        class="fa fa-eye"></i></button></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bet detail model --}}
    <div class="modal fade modal-number">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-head"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <table
                                class="table table-responsive table-striped table-borderless scrollable scrollbarbackgroundblue dashboard_scroll">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="betDetailData">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- clearance password model --}}
    <div class="modal fade modal-password">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Clearance Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/clearance_3d') }}">
                        @csrf
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" required>
                            <input type="hidden" name="month" class="pass_month">
                            <input type="hidden" name="date3d" class="pass_date_3d">
                            <input type="hidden" name="year" class="pass_year">
                            <input type="hidden" name="all_bet_amount" class="all_bet_amount_cl">
                            <input type="hidden" name="total_reward" class="total_reward_cl">
                            <input type="hidden" name="profit" class="profit_cl">
                            <input type="hidden" name="user_refer_total" class="user_refer_total_cl">
                            <input type="hidden" name="lucky_number" class="lucky_number_cl">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- refund password model --}}
    <div class="modal fade modal-refund">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Refund Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/refund_3d') }}">
                        @csrf
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" required>
                            <input type="hidden" name="month" class="pass_month">
                            <input type="hidden" name="date3d" class="pass_date_3d">
                            <input type="hidden" name="year" class="pass_year">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- bet date history --}}
    <input type="hidden" class="his_month" value="{{ request()->date ? date("m", strtotime(request()->date)) : $hist_month }}">
    <input type="hidden" class="his_year" value="{{ request()->date ? date("Y", strtotime(request()->date)) : $hist_year }}">

@endsection
@section('scripts')
    <script src="{{ asset('custom/dashboard_3d.js') }}"></script>
@endsection
