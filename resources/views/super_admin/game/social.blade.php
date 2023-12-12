@extends('super_admin.backend_layout.app')

@section('game-social-active', 'active')

@section('create-game-active', 'active')

@section('content')
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('game/social_link_update_two') }}">
                        @csrf
                        <div class="form-group">
                            <label class="text-md">Facebook</label>
                            <input type="text" name="facebook" class="form-control form-control-sm" value="{{ $facebook->value }}">
                        </div>
                        <button type="submit" class="btn-sm btn btn-outline-info">Update</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('game/social_link_update_two') }}">
                        @csrf
                        <div class="form-group">
                            <label class="text-md">Viber</label>
                            <input type="text" name="viber" class="form-control form-control-sm" value="{{ $viber->value }}">
                        </div>
                        <button type="submit" class="btn-sm btn btn-outline-info">Update</button>
                    </form>
                </div>
                <div class="col-md-6 mt-3">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('game/social_link_update_two') }}">
                        @csrf
                        <div class="form-group">
                            <label class="text-md">Messenger</label>
                            <input type="messenger" name="messenger" class="form-control form-control-sm" value="{{ $messenger->value }}">
                        </div>
                        <button type="submit" class="btn-sm btn btn-outline-info">Update</button>
                    </form>
                </div>
                <div class="col-md-6 mt-3">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('game/social_link_update_two') }}">
                        @csrf
                        <div class="form-group">
                            <label class="text-md">Telegram</label>
                            <input type="text" name="instagram" class="form-control form-control-sm" value="{{ $instagram->value }}">
                        </div>
                        <button type="submit" class="btn-sm btn btn-outline-info">Update</button>
                    </form>
                </div>
                <div class="col-md-6 mt-3">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('game/social_link_update_two') }}">
                        @csrf
                        <div class="form-group">
                            <label class="text-md">Play Store</label>
                            <input type="text" name="play_store" class="form-control form-control-sm" value="{{ $play_store->value }}">
                        </div>
                        <button type="submit" class="btn-sm btn btn-outline-info">Update</button>
                    </form>
                </div>
                <div class="col-md-6 mt-3">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('game/social_link_update_two') }}">
                        @csrf
                        <div class="form-group">
                            <label class="text-md">MediaFire</label>
                            <input type="text" name="media_fire" class="form-control form-control-sm" value="{{ $media_fire->value }}">
                        </div>
                        <button type="submit" class="btn-sm btn btn-outline-info">Update</button>
                    </form>
                </div>
                <div class="col-md-6 mt-3">
                    <form method="POST" enctype="multipart/form-data" action="{{ url('game/social_link_update_two') }}">
                        @csrf
                        <div class="form-group">
                            <label class="text-md">Qr Image</label>
                            <input type="file" class="form-control form-control-sm" name="qr" onchange="loadFile(event)">
                            @if($qr->value)
                                <img src="{{ asset($qr->value) }}" width="100" class="mt-1">
                                <a href="{{ url('game/social_link/qr_image_delete_two') }}" onclick="return confirm('Confirm delete?')" style="color: red;"><i class="fas fa-trash"></i></a>
                            @else
                                <img id="output" width="100" class="mt-1"/>
                            @endif
                        </div>
                        <button type="submit" class="btn-sm btn btn-outline-info">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
