@extends('super_admin.backend_layout.app')

@section('title', 'Over All Setting')

@section('over-all-setting-active', 'active')

@section('content')

    <form method="POST" enctype="multipart/form-data" action="{{ route('over_all_setting.update', $over_all_setting->id) }}">
        @csrf
        @method('PUT')

        <div class="card card-default card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">1D Over All Amount</label>
                            <input type="text" class="form-control form-control-sm" name="over_all_amount_1d"
                                value="{{ $over_all_setting->over_all_amount_1d }}" placeholder="Enter 2D Over All Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">1D Odd</label>
                            <input type="text" class="form-control form-control-sm" name="over_all_odd_1d"
                                value="{{ $over_all_setting->over_all_odd_1d }}" placeholder="Enter Over All Odd">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-default card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">2D Over All Amount</label>
                            <input type="text" class="form-control form-control-sm" name="over_all_amount"
                                value="{{ $over_all_setting->over_all_amount }}" placeholder="Enter 2D Over All Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">2D Odd</label>
                            <input type="text" class="form-control form-control-sm" name="over_all_odd"
                                value="{{ $over_all_setting->over_all_odd }}" placeholder="Enter Over All Odd">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-default card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Crypto 1D Over All Amount</label>
                            <input type="text" class="form-control form-control-sm" name="over_all_amount_crypto_1d"
                                value="{{ $over_all_setting->over_all_amount_crypto_1d }}"
                                placeholder="Enter Crypto 1D Over All Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Crypto 1D Odd</label>
                            <input type="text" class="form-control form-control-sm" name="crypto_1d_odd"
                                value="{{ $over_all_setting->crypto_1d_odd }}" placeholder="Enter Over All Odd">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-default card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Crypto 2D Over All Amount</label>
                            <input type="text" class="form-control form-control-sm" name="over_all_amount_crypto_2d"
                                value="{{ $over_all_setting->over_all_amount_crypto_2d }}"
                                placeholder="Enter 2D Over All Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Crypto 2D Odd</label>
                            <input type="text" class="form-control form-control-sm" name="crypto_2d_odd"
                                value="{{ $over_all_setting->crypto_2d_odd }}" placeholder="Enter Over All Odd">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        



        <div class="card card-default card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">3D Over All Amount</label>
                            <input type="text" class="form-control form-control-sm" name="over_all_amount_3d"
                                value="{{ $over_all_setting->over_all_amount_3d }}" placeholder="Enter 3D Over All Amount">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">3D Odd</label>
                            <input type="text" class="form-control form-control-sm" name="odd_3d"
                                value="{{ $over_all_setting->odd_3d }}" placeholder="Enter Over 3D Odd">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">3D Tot</label>
                            <input type="text" class="form-control form-control-sm" name="tot_3d"
                                value="{{ $over_all_setting->tot_3d }}" placeholder="Enter Over 3D Tot">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-default card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Thai 2D Referral</label>
                            <input type="text" class="form-control form-control-sm" name="referral"
                                value="{{ $over_all_setting->referral }}" placeholder="Enter Referral Amount">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Crypto 2D Referral</label>
                            <input type="text" class="form-control form-control-sm" name="referral_c2d"
                                value="{{ $over_all_setting->referral_c2d }}" placeholder="Enter Referral Amount">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Thai 3D Referral</label>
                            <input type="text" class="form-control form-control-sm" name="referral_3d"
                                value="{{ $over_all_setting->referral_3d }}" placeholder="Enter Referral Amount">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-default card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Game Referral</label>
                            <input type="text" class="form-control form-control-sm" name="game_refer"
                                value="{{ $over_all_setting->game_refer }}" placeholder="Enter Game Referral Amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Over All Default Amount</label>
                            <input type="text" class="form-control form-control-sm" name="over_all_default_amount"
                                value="{{ $over_all_setting->over_all_default_amount }}"
                                placeholder="Enter Over Default Amount">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-dark">Update</button>

    </form>

@endsection
