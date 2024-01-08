<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            {{-- Tooltip --}}
            
        </li>
        <!-- <li class="nav-item">
            {{-- Tooltip --}}
            <button type="button" class="btn btn-secondary bs-hidden" id="bs-tooltip-game">
                Game
            </button>
            <a data-bs-tooltip="bs-tooltip-game" class="nav-link bs-tooltip" href="{{ url('game/dashboard_game') }}"
                role="button"><i class="nav-icon fas fa-gamepad"></i></a>
        </li> -->

        <li class="nav-item">
            {{-- Tooltip --}}
            <button type="button" class="btn btn-secondary bs-hidden" id="bs-tooltip-game">
                i casino side
            </button>

        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                @if (Auth::guard('super_admin')->user()->image)
                    <img src="{{ asset(Auth::guard('super_admin')->user()->image) }}"
                        class="user-image img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{ asset('backend/avatar.png') }}" class="user-image img-circle elevation-2"
                        alt="User Image">
                @endif
                <span class="d-none d-md-inline">{{ Auth::guard('super_admin')->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-dark">
                    @if (Auth::guard('super_admin')->user()->image)
                        <img src="{{ asset(Auth::guard('super_admin')->user()->image) }}" class="img-circle elevation-2"
                            alt="User Image">
                    @else
                        <img src="{{ asset('backend/avatar.png') }}" class="img-circle elevation-2" alt="User Image">
                    @endif
                    <p>
                        {{ Auth::guard('super_admin')->user()->name }}
                    </p>
                </li>
                <li class="user-footer d-flex flex-column">
                    <a href="{{ url('/super_admin/profile/'.Auth::guard('super_admin')->user()->id) }}" class="btn btn-default btn-flat">Profile</a>
                    <a href="{{ url('/super_admin/change_pass') }}" class="btn btn-default btn-flat float-right my-2">Change Password</a>
                    <a href="{{ url('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
                    class="btn btn-default btn-flat float-right">
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
