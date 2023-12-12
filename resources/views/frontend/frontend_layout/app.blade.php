<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        {{-- css --}}
        @include('frontend_style.css')
    </head>
    <body>
        {{-- main content --}}
        @yield('content')

        {{-- js --}}
        @include('frontend_style.js')
    </body>
</html>
