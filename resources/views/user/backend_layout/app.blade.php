<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <!-- css -->
        @include('backend_style.css')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <!-- Navbar -->
            @include('user.backend_layout.nav')
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            @include('user.backend_layout.sidebar')
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h3 class="m-0">@yield('title')</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-body text-sm">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- script -->
        @include('backend_style.js')
    </body>
</html>
