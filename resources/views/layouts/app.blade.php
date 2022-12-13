<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSRF-token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    </head>

    <body class="light">

        <div id="app">
            <!-- content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- footer -->
        <footer class="footer">

        </footer>

    <!-- scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('js')

    </body>
</html>