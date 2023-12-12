<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('backend/logo.png')}}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-lazyload@1.9.7/jquery.lazyload.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>@yield('title')</title>
    <!-- css -->
    @include('backend_style.css')
    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        @include('super_admin.backend_layout.nav')
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        @include('super_admin.backend_layout.sidebar')
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
                        <div class="card-body text-sm" style="">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- script -->
    @include('backend_style.js')
    <script>
        $(function() {
            $(document).on("submit", "form", function() {
                $("button").attr("disabled", "disabled");
            });
        })
    </script>
</body>

</html>
