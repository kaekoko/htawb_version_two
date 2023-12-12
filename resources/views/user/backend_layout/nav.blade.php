<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        {{-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <span class="badge badge-warning navbar-badge">@if(isset($senior_agent_notis)){{ count($senior_agent_notis) }}@endif</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">@if(isset($senior_agent_notis)){{ count($senior_agent_notis) }}@endif Notifications</span>
              <div class="dropdown-divider"></div>
              @if(isset($senior_agent_notis))
                @foreach ($senior_agent_notis as $noti)
                    <a href="{{ url('senior_agent/noti/'.$noti->notifications->id) }}" class="dropdown-item">
                        {{ Str::limit($noti->notifications->title, 17) }}
                        <span class="float-right text-muted text-sm">{{ $noti->notifications->created_at->diffForHumans(); }}</span>
                    </a>
                @endforeach
              @endif
            </div>
        </li> --}}
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                @if (Auth::guard('user')->user()->image)
                    <img src="{{ asset(Auth::guard('user')->user()->image) }}" class="user-image img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{ asset('backend/avatar.png') }}" class="user-image img-circle elevation-2" alt="User Image">
                @endif
                <span class="d-none d-md-inline">{{ Auth::guard('user')->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-dark">
                    @if (Auth::guard('user')->user()->image)
                        <img src="{{ asset(Auth::guard('user')->user()->image) }}" class="img-circle elevation-2" alt="User Image">
                    @else
                        <img src="{{ asset('backend/avatar.png') }}" class="img-circle elevation-2" alt="User Image">
                    @endif
                    <p>
                        {{ Auth::guard('user')->user()->name }} - User
                    </p>
                </li>
                <li class="user-footer d-flex flex-column">
                    <a href="{{ url('/user/profile/'.Auth::guard('user')->user()->id) }}" class="btn btn-default btn-flat">Profile</a>
                    <a href="{{ url('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
                    class="btn btn-default btn-flat float-right mt-1">
                            Log out
                        <form id="logout-form" action="{{ url('logout') }}" method="GET" class="d-none">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
