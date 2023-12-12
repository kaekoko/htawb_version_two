@extends('frontend.frontend_layout.app')

@section('title', 'Login')

@section('content')
    <div class="frontend_login">
        <div style="height: 100vh; display: flex; align-items: center;" class="container">
            <div class="login-box mx-auto card shadow p-3 bg-white rounded">
                <div class="login-logo">
                    <img style="object-position: center; object-fit:contain;" src="{{ asset('backend/logo.png') }}"
                        alt="Logo" width="190" class="img-fluid">
                </div>
                <div class="card-body login-card-body">

                    <form action="{{ url('check') }}" method="POST">
                        @csrf

                        <div class="input-group">
                            <input type="text" name="phone" class="form-control" placeholder="phone">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mt-3">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <textarea class="d-none" name="device_token" id="device_token"></textarea>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark btn-block">Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\LoginRequest') !!}
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    {{-- firebase initialize file --}}
    <script src="{{ asset('firebase.js') }}"></script>
    <script>
        messaging.requestPermission()
            .then(function() {
                return messaging.getToken()
            })
            .then(function(token) {
                var get_token = document.getElementById('device_token');
                get_token.innerHTML = token;
            }).catch(function(err) {
                console.log('User Chat Token Error' + err);
            });
    </script>
@endsection
