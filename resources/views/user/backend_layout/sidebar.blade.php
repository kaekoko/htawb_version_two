<aside class="main-sidebar elevation-4 mmk_bg_color">
    <!-- Brand Logo -->
    <a href="https://backend.myvipmm.com/super_admin/dashboard" class="brand-link border-bottom-0 d-flex justify-content-center align-items-center">
        <img src="{{ asset('backend/logo.png') }}" alt="Logo" width="120">
        <span class="brand-text font-weight-light">HTAWB</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/user/dashboard') }}" class="nav-link @yield('dashboard-active')">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link @yield('payment-active')">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>
                            Payment History
                        </p>
                        <i class="fas fa-angle-left right"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/user/cash_in_history/' . Auth::guard('user')->user()->id) }}"
                                class="nav-link">
                                <i class="nav-icon fas fa-receipt"></i>
                                <p>Cash In</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/user/cash_out_history/' . Auth::guard('user')->user()->id) }}"
                                class="nav-link">
                                <i class="nav-icon fas fa-receipt"></i>
                                <p>Cash Out</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
