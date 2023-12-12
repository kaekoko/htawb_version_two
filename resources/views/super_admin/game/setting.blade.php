@extends('super_admin.backend_layout.app')

@section('title', 'Game Setting')

@section('game-setting-active', 'active')

@section('content')

<div class="row">
    <div class="col-lg-6">
        <div class="card card-dark card-outline">
            <div class="card-header"><h4 class="card-title">App Force Update Setting</h4></div>
            <div class="card-body">
                <form action="{{ route('app.update.two', '1') }}" method="POST">
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

