@extends('user.backend_layout.app')

@section('title', 'User Dashboard')

@section('dashboard-active', 'active')

@section('content')
    @if (empty($token))
        <center>
            <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()"
                class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
        </center>
    @endif
@endsection
@section('scripts')
    <script>
        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                })
                .then(function(token) {
                    $.ajax({
                        url: '{{ url('user/save_token') }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            alert('Token saved successfully.');
                            window.location.reload();
                        },
                        error: function(err) {
                            console.log('User Chat Token Error' + err);
                        },
                    });
                }).catch(function(err) {
                    console.log('User Chat Token Error' + err);
                });
        }
    </script>
@endsection
