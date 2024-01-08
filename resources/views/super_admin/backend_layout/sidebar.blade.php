<aside class="main-sidebar elevation-4 mmk_bg_color">
    <!-- Brand Logo -->
    <a href="https://backend.myvipmm.com/super_admin/dashboard"
        class="brand-link border-bottom-0 d-flex justify-content-center align-items-center">



        @if (Request::is('game/*'))
            <img src="{{ asset('backend/icasino_ficon.png') }}" alt="Logo" width="50">
        @else
            <img src="{{ asset('backend/logo.png') }}" alt="Logo" width="60">
            <span class="brand-text font-weight-light">HTAWB</span>
        @endif
    </a>
    @php
        $role_id = Auth::guard('super_admin')->user()->role_id;
    @endphp
    <!-- Sidebar -->
    <div class="sidebar" style="height: calc(100% - 4.3rem) !important;">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                        <a href="{{ url('/super_admin/main/dashboard') }}" class="nav-link @yield('main-dashboard-active')">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Main Dashboard
                            </p>
                        </a>
                    </li>
                {{-- Super Admin Dashboard --}}
                @if (Request::is('super_admin/*'))
                    <li class="nav-item">
                        <a href="#"
                            class="nav-link @yield('dashboard-2d-active') @yield('dashboard-3d-active') @yield('dashboard-promotion-active') @yield('dashboard-crypto-2d-active') @yield('dashboard-1d-active') @yield('dashboard-promotion-active')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('super_admin/dashboard_1d') }}" class="nav-link @yield('dashboard-1d-active')">
                                    <i class="nav-icon fas fa-dice-two"></i>
                                    <p>
                                        1D
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('super_admin/dashboard') }}" class="nav-link @yield('dashboard-2d-active')">
                                    <i class="nav-icon fas fa-dice-two"></i>
                                    <p>
                                        2D
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('super_admin/dashboard_c1d') }}" class="nav-link @yield('dashboard-crypto-1d-active')">
                                    <i class="nav-icon fas fa-dice-two"></i>
                                    <p>
                                        Crypto 1D
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('super_admin/dashboard_c2d') }}" class="nav-link @yield('dashboard-crypto-2d-active')">
                                    <i class="nav-icon fas fa-dice-two"></i>
                                    <p>
                                        Crypto 2D
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('super_admin/dashboard_3d') }}" class="nav-link  @yield('dashboard-3d-active')">
                                    <i class="nav-icon fas fa-dice-three"></i>
                                    <p>
                                        3D
                                    </p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ url('super_admin/promotion/dashboard') }}" class="nav-link  @yield('dashboard-promotion-active')">
                                    <i class="nav-icon fas fa-dice-three"></i>
                                    <p>
                                       Promotion
                                    </p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ url('super_admin/promotion/index') }}" class="nav-link  @yield('dashboard-promotion-list-active')">
                                    <i class="nav-icon fas fa-dice-three"></i>
                                    <p>
                                       Promotion List
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- role_id : 1 ( super_admin ) --}}
                    @if ($role_id == 1)
                        <li class="nav-item">
                            <a href="{{ url('/super_admin/admin') }}" class="nav-link @yield('admin-active')">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>
                                    Admin
                                </p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ url('/super_admin/user') }}" class="nav-link @yield('user-active')">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                User
                            </p>
                            <!-- @if (isset($user_count))
<span class="badge badge-danger right" id="user_count">
                            {{ count($user_count) }}
                        </span>
@endif -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/super_admin/lottery_off_day') }}" class="nav-link @yield('lottery-off-day-active')">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Lottery Off Day
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/super_admin/bet_slip_1d') }}" class="nav-link @yield('bet-slip-1d-active')">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                1D Bet Slip
                            </p>
                            <!-- @if (isset($bet_slip_1d_count))
<span class="badge badge-danger right" id="bet_slip_1d_count">
                                    {{ count($bet_slip_1d_count) }}
                                </span>
@endif -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/super_admin/bet_slip') }}" class="nav-link @yield('bet-slip-active')">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                2D Bet Slip
                            </p>
                            <!-- @if (isset($bet_slip_count))
<span class="badge badge-danger right" id="bet_slip_count">
                            {{ count($bet_slip_count) }}
                        </span>
@endif -->
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/super_admin/bet_slip_c1d') }}" class="nav-link @yield('bet-slip-crypto-1d-active')">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                Crypto 1D Bet Slip
                            </p>
                            <!-- @if (isset($bet_slip_c1d_count))
<span class="badge badge-danger right" id="bet_slip_c2d_count">
                            {{ count($bet_slip_c1d_count) }}
                        </span>
@endif -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/super_admin/bet_slip_c2d') }}" class="nav-link @yield('bet-slip-crypto-2d-active')">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                Crypto 2D Bet Slip
                            </p>
                            <!-- @if (isset($bet_slip_c2d_count))
<span class="badge badge-danger right" id="bet_slip_c2d_count">
                            {{ count($bet_slip_c2d_count) }}
                        </span>
@endif -->
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/super_admin/bet_slip_3d') }}" class="nav-link @yield('bet-slip-3d-active')">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                3D Bet Slip
                            </p>
                            <!-- @if (isset($bet_slip_3d_count))
<span class="badge badge-danger right" id="bet_slip_3d_count">
                            {{ count($bet_slip_3d_count) }}
                        </span>
@endif -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/super_admin/cash-in') }}" class="nav-link @yield('cashin-active')">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>
                                CashIn
                            </p>
                            <!-- @if (isset($cash_in_count))
<span class="badge badge-danger right" id="cash_in_count">
                            {{ count($cash_in_count) }}
                        </span>
@endif -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/super_admin/cash-out') }}" class="nav-link @yield('cashout-active')">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>
                                CashOut
                            </p>
                            <!-- @if (isset($cash_out_count))
<span class="badge badge-danger right" id="cash_out_count">
                            {{ count($cash_out_count) }}
                        </span>
@endif -->
                        </a>
                    </li>
                    {{-- @if ($role_id == 1) --}}
                        <li class="nav-item">
                            <a href="{{ url('/super_admin/over_all_setting') }}" class="nav-link @yield('over-all-setting-active')">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Over All Setting
                                </p>
                            </a>
                        </li>
                    {{-- @endif --}}

                    {{-- @if ($role_id = 1 || ($role_id = 2)) --}}
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link @yield('games') @yield('game-providers') @yield('game-categories')">

                                <i class="nav-icon fas fa-gamepad"></i>
                                <p>
                                    Seamless Games
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/game_categories') }}"
                                        class="nav-link @yield('game-categories')">
                                        <i class="nav-icon fas fa-gamepad"></i>
                                        <p>
                                            Game Categories
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/game_providers') }}"
                                        class="nav-link @yield('game-providers')">
                                        <i class="nav-icon fas fa-gamepad"></i>
                                        <p>
                                            Game Providers
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/games') }}" class="nav-link @yield('games')">
                                        <i class="nav-icon fas fa-gamepad"></i>
                                        <p>
                                            Games
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/super_admin/betslip/transaction') }}"
                                class="nav-link @yield('transaction-active')">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Transactions
                                </p>
                            </a>
                        </li>
                    {{-- @endif --}}

                    {{-- @if ($role_id == 1) --}}
                        <li class="nav-item">
                            <a href="{{ url('/super_admin/winner_history') }}" class="nav-link @yield('winner-history-active')">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Winner History
                                </p>
                            </a>
                        </li>
                    {{-- @endif --}}
                    {{-- @if ($role_id == 1 || $role_id == 2) --}}
                        <li class="nav-item">
                            <a href="{{ url('/super_admin/activity_log') }}" class="nav-link @yield('activity-log-active')">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Activity Log
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link @yield('spin-wheel-active') @yield('promo-cash-in-active')">
                                <i class="nav-icon fas fa-bullhorn"></i>
                                <p>
                                    Promotion
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/spin_wheel') }}"
                                        class="nav-link @yield('spin-wheel-active')">
                                        <i class="nav-icon fas fa-dharmachakra"></i>
                                        <p>
                                            Spin Wheel
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/promo_cash_in') }}"
                                        class="nav-link @yield('promo-cash-in-active')">
                                        <i class="nav-icon fas fa-money-check"></i>
                                        <p>
                                            Cash In
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    {{-- @endif --}}
                    <li class="nav-item">
                        <a href="#"
                            class="nav-link @yield('report-2d-active') @yield('report-3d-active') @yield('report-user-active') @yield('report-crypto-1d-active') @yield('user-refer-history-active') @yield('report-1d-active')">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Reports
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/super_admin/report_1d') }}" class="nav-link @yield('report-1d-active')">
                                    <i class="nav-icon fas fa-dice-two"></i>
                                    <p>
                                        1D
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/super_admin/report_2d') }}" class="nav-link @yield('report-2d-active')">
                                    <i class="nav-icon fas fa-dice-two"></i>
                                    <p>
                                        2D
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/super_admin/report_c1d') }}" class="nav-link @yield('report-crypto-1d-active')">
                                    <i class="nav-icon fas fa-dice-two"></i>
                                    <p>
                                        Crypto 1D
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/super_admin/report_c2d') }}" class="nav-link @yield('report-crypto-2d-active')">
                                    <i class="nav-icon fas fa-dice-two"></i>
                                    <p>
                                        Crypto 2D
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('/super_admin/report_3d') }}" class="nav-link @yield('report-3d-active')">
                                    <i class="nav-icon fas fa-dice-three"></i>
                                    <p>
                                        3D
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/super_admin/report_user') }}" class="nav-link  @yield('report-user-active')">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>
                                        User
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/super_admin/user_refer_history') }}"
                                    class="nav-link  @yield('user-refer-history-active')">
                                    <i class="nav-icon fas fa fa-percent"></i>
                                    <p>
                                        Commission
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- @if ($role_id == 1 || $role_id == 2) --}}
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link @yield('hotblock-c2d-active') @yield('hotblock-active') @yield('hotblock-c1d-active') @yield('hotblock-3d-active') @yield('hotblock-1d-active')">
                                <i class="nav-icon fas fa-burn"></i>
                                <p>
                                    Hot & Block Numbers
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/hotblock1d') }}"
                                        class="nav-link @yield('hotblock-1d-active')">
                                        <i class="nav-icon fas fa-dice-two"></i>
                                        <p>
                                            1D
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/hotblock') }}" class="nav-link @yield('hotblock-active')">
                                        <i class="nav-icon fas fa-dice-two"></i>
                                        <p>
                                            2D
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/hotblock_c1d') }}"
                                        class="nav-link @yield('hotblock-c1d-active')">
                                        <i class="nav-icon fas fa-dice-two"></i>
                                        <p>
                                            Crypto 1D
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/hotblock_c2d') }}"
                                        class="nav-link @yield('hotblock-c2d-active')">
                                        <i class="nav-icon fas fa-dice-two"></i>
                                        <p>
                                            Crypto 2D
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/hotblock_3d') }}"
                                        class="nav-link  @yield('hotblock-3d-active')">
                                        <i class="nav-icon fas fa-dice-three"></i>
                                        <p>
                                            3D
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link @yield('lucky-number-c2d-active') @yield('lucky-number-3d-active') @yield('lucky-number-c1d-active') @yield('lucky-number-2d-active') @yield('lucky-number-1d-active')">
                                <i class="nav-icon fas fa-star"></i>
                                <p>
                                    Lucky Number
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/lucky_number_1d') }}"
                                        class="nav-link @yield('lucky-number-1d-active')">
                                        <i class="nav-icon fas fa-dice-two"></i>
                                        <p>
                                            1D
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/lucky_number') }}"
                                        class="nav-link @yield('lucky-number-2d-active')">
                                        <i class="nav-icon fas fa-dice-two"></i>
                                        <p>
                                            2D
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/lucky_number_c1d') }}"
                                        class="nav-link @yield('lucky-number-c1d-active')">
                                        <i class="nav-icon fas fa-dice-two"></i>
                                        <p>
                                            Crypto 1D
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/lucky_number_c2d') }}"
                                        class="nav-link @yield('lucky-number-c2d-active')">
                                        <i class="nav-icon fas fa-dice-two"></i>
                                        <p>
                                            Crypto 2D
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/lucky_number_3d') }}"
                                        class="nav-link @yield('lucky-number-3d-active')">
                                        <i class="nav-icon fas fa-dice-three"></i>
                                        <p>
                                            3D
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link @yield('section-active') @yield('category-active') @yield('social-link-active') @yield('service-active') @yield('blog-active') @yield('notification-active') @yield('payment-method-active') @yield('city-active') @yield('header-play-text-active') @yield('banner-active') @yield('noti-active')">
                                {{-- <i class="nav-icon fas fa-cog"></i> --}}
                                <i class="nav-icon fas fa-bars"></i>
                                <p>
                                    Extra Menu
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/settings') }}" class="nav-link @yield('setting-active')">
                                        <i class="nav-icon fas fa-cog"></i>
                                        <p>
                                            Settings
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/section') }}" class="nav-link @yield('section-active')">
                                        <i class="nav-icon fas fa-clock"></i>
                                        <p>
                                            Section
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/category') }}" class="nav-link @yield('category-active')">
                                        <i class="nav-icon fas fa-list-ul"></i>
                                        <p>
                                            Category
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/social_link') }}"
                                        class="nav-link @yield('social-link-active')">
                                        <i class="nav-icon fas fa-share-alt-square"></i>
                                        <p>
                                            Social Link
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/service') }}" class="nav-link @yield('service-active')">
                                        <i class="nav-icon fas fa-phone"></i>
                                        <p>
                                            Service
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/blog') }}" class="nav-link @yield('blog-active')">
                                        <i class="nav-icon fas fa-blog"></i>
                                        <p>
                                            Blog
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/payment_method') }}"
                                        class="nav-link @yield('payment-method-active')">
                                        <i class="nav-icon fas fa-credit-card"></i>
                                        <p>
                                            Payment Method
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/header_play_text') }}"
                                        class="nav-link @yield('header-play-text-active')">
                                        <i class="nav-icon fas fa-forward"></i>
                                        <p>
                                            Header Play Text
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/banner') }}" class="nav-link @yield('banner-active')">
                                        <i class="nav-icon fas fa-image"></i>
                                        <p>
                                            Banner
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/super_admin/noti') }}" class="nav-link @yield('noti-active')">
                                        <i class="nav-icon fas fa-bell"></i>
                                        <p>
                                            Notification
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    {{-- @endif --}}
                    <br>
                @endif
                {{-- Game Dashboard --}}
                @if (Request::is('game/*'))
                    <li class="nav-item">
                        <a href="{{ url('/game/user') }}" class="nav-link @yield('game-user-active')">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users List
                            </p>
                            @if (isset($user_count_i))
                                <span class="badge badge-danger right" id="user_count">
                                    {{ count($user_count_i) }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/game/bannertwo') }}" class="nav-link @yield('game-dashboard-active')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Banner
                            </p>
                        </a>
                    </li>
                    {{-- @if ($role_id == 1 || $role_id == 2) --}}
                    <li class="nav-item">
                        <a href="{{ url('/game/social_link_two') }}" class="nav-link @yield('game-social-active')">
                            <i class="nav-icon fas fa-gamepad"></i>
                            <p>
                                Social Link
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/game/setting_two') }}" class="nav-link @yield('game-setting-active')">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                App Setting
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/game/payment_method_new') }}" class="nav-link @yield('payment-method-two-active')">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>
                                Paymethod
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/game/header_play_text_two') }}" class="nav-link @yield('header-play-text-two-active')">
                            <i class="nav-icon fas fa-forward"></i>
                            <p>
                                Header Play Text
                            </p>
                        </a>
                    </li>

                    {{-- @endif --}}
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
