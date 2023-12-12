@extends('super_admin.backend_layout.app')

@section('title', 'Game Statics')

@section('game-active', 'active')

@section('content')

<input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
<input type="text" id="checking-tab"  hidden>
<ul class="nav nav-tabs" id="cat-tab" role="tablist">

</ul>
<div class="tab-content mt-3 text-center" id="cat-body" style="height: 700px; overflow-y: scroll;">

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="{{ asset('js/game.js') }}"></script>
@endsection

