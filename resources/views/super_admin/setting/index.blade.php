@extends('super_admin.backend_layout.app')

@section('title', 'Settings')

@section('setting-active', 'active')

@section('content')
<div class="d-flex">
<div class="row">
    <div class="col-lg-12">
        <div class="card card-dark card-outline">
            <div class="card-header">
                <h4 class="card-title">Game Control Setting</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input style="cursor: pointer;" onchange=""
                            {{ $over_all_setting->game_maintenance ? 'checked' : '' }} name="is_open_3d"
                            id="switchToggle" type="checkbox" class="custom-control-input">
                        <label style="cursor: pointer;" class="custom-control-label" for="switchToggle">Is Game
                            Maintenance Opened?</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card card-dark card-outline">
            <div class="card-header">
                <h4 class="card-title">Myvip wave Auto Approve</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input style="cursor: pointer;" onchange=""
                            {{ $wave_setting->myvip_wave ? 'checked' : '' }} name="myvip_wave"
                            id="switchTogglemyvip" type="checkbox" class="custom-control-input">
                        <label style="cursor: pointer;" class="custom-control-label" for="switchTogglemyvip">Htaw B Auto Approve Opened?</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card card-dark card-outline">
            <div class="card-header"><h4 class="card-title">CashIn/CashOut Setting</h4></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form action="{{ route('cashin.mini', $cash_in_mini->key) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label>CashIn Minimum Amount</label>
                                <input type="text" name="cash_in_mini_amount" class="form-control" placeholder="Ex.1000" value="{{ $cash_in_mini->value }}">
                            </div>
                            <button class="btn btn-success">Modify</button>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <form action="{{ route('cashout.mini', $cash_out_mini->key) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label>CashOut Minimum Amount</label>
                                <input type="text" name="cash_out_mini_amount" class="form-control" placeholder="Ex.1000" value="{{ $cash_out_mini->value }}">
                            </div>
                            <button class="btn btn-success">Modify</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form action="{{ route('welcome.bonus', $welcome_bonus->key) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>welcom Bonus</label>
                            <input type="text" name="welcome_bonus" class="form-control"
                                value="{{ $welcome_bonus->value }}" placeholder="Ex.3000">
                        </div>
                        <button class="btn btn-success">Modify</button>
                    </form>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <form action="{{ route('cashin.amount',$cash_in_amount->key) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>CashIn Amount</label>
                                <input type="text" name="cash_in_amount" class="form-control" value="{{ $cash_in_amount->value }}" placeholder="Ex.1000,2000,3000,5000">
                            </div>
                            <button class="btn btn-success">Modify</button>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <form action="{{ route('cashout.amount', $cash_out_amount->key) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>CashOut Amount</label>
                                <input type="text" name="cash_out_amount" class="form-control" value="{{ $cash_out_amount->value }}" placeholder="Ex.1000,2000,3000,5000">
                            </div>
                            <button class="btn btn-success">Modify</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-dark card-outline">
            <div class="card-header"><h4 class="card-title">App Force Update Setting</h4></div>
            <div class="card-body">
                <form action="{{ route('app.update', '1') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Version Code</label>
                        <input type="number" name="version_code" class="form-control" value="{{ $app_update->version_code ?? 0 }}">
                    </div>
                    <div class="form-group">
                        <label>Hide Wallet version number</label>
                        <input type="number" name="wallet_hide_version" class="form-control" value="{{ $app_update->wallet_hide_version ?? 0 }}">
                    </div>
                    <div class="form-group">
                        <label>Version Name</label>
                        <input type="text" name="version_name" class="form-control" value="{{ $app_update->version_name }}">
                    </div>
                    <div class="form-group">
                        <label>Playstore</label>
                        <input type="text" name="playstore" class="form-control" value="{{ $app_update->playstore }}">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" cols="30" rows="5">
                            {{$app_update->description}}
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label>Force Update</label>
                        <input type="checkbox" name="force_update" value="1"
                            @if ($app_update->force_update == 1)
                                checked
                            @endif
                        >
                    </div>
                    <div class="form-group">
                        <label>Show Wallet</label>
                        <input type="checkbox" name="show_wallet" value="1"
                            @if ($app_update->show_wallet == 1)
                                checked
                            @endif
                        >
                    </div>
                    <button type="submit" class="btn btn-success">Modify</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const handleGameMaintenance = (is_open) => {
            $.ajax({
                method: 'POST',
                url: location.protocol + "//" + location.host + '/super_admin/toggle_game_maintenance',
                data: {
                    is_open
                },
                success: function(data) {
                    toastr.success(data?.message)
                },
                error: function(err) {
                    toastr.error(err?.responseJSON?.message)
                }
            });
        }

        $(document).ready(function() {
            $("#switchToggle").on("change", () => {
                let isChecked = $("#switchToggle").is(":checked");
                if (isChecked)
                    handleGameMaintenance(1);
                else
                    handleGameMaintenance(0);
            })
        });



        // myvip wave

        const handleMyvipWave = (is_open) => {
            $.ajax({
                method: 'POST',
                url: location.protocol + "//" + location.host + '/super_admin/toggle_myvip_wave',
                data: {
                    is_open
                },
                success: function(data) {
                    toastr.success(data?.message)
                },
                error: function(err) {
                    toastr.error(err?.responseJSON?.message)
                }
            });
        }

        $(document).ready(function() {
            $("#switchTogglemyvip").on("change", () => {
                let isChecked = $("#switchTogglemyvip").is(":checked");
                if (isChecked)
                handleMyvipWave('1');
                else
                handleMyvipWave('0');
            })
        });


        // Icasino wave

        const handleIcasinoWave = (is_open) => {
            $.ajax({
                method: 'POST',
                url: location.protocol + "//" + location.host + '/super_admin/toggle_icasino_wave',
                data: {
                    is_open
                },
                success: function(data) {
                    toastr.success(data?.message)
                },
                error: function(err) {
                    toastr.error(err?.responseJSON?.message)
                }
            });
        }

        $(document).ready(function() {
            $("#switchToggleicasino").on("change", () => {
                let isChecked = $("#switchToggleicasino").is(":checked");
                if (isChecked)
                handleIcasinoWave('1');
                else
                handleIcasinoWave('0');
            })
        });
    </script>

@endsection
