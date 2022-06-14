<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>@yield('title', 'Print')</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('css/print.css') }}" rel="stylesheet" />
    @stack('styles')
    @yield('styles')
</head>

<body class="app-print">
    <div class="print-header">
        @section('header')
            <div class="row align-items-center">
                <div class="col-auto">
                    <img src="{{ asset('images/app-logo.png') }}" class="print-logo" /> 
                </div>

                <div class="col">
                    @section('header.content')
                        <h3 class="mb-1">{{ config('app.name') }}</h3>

                        <p class="mb-0">Jl. Wijaya II No. 3, Jebres, Surakarta 57126</p>
                    @show
                </div>

                <div class="col-auto align-self-start">
                    @yield('header.right')
                </div>
            </div>
        @show
    </div>

    <div class="print-content my-3 py-3">
        @yield('content')
    </div>

    <div class="print-footer">
        @yield('footer')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

    @if (!request('preview'))
        <script>
        (function($) {
            $(window).on('load', function() {
                window.print();

                setTimeout(() => {
                    window.close();
                }, 1000);
            });
        })(jQuery);
        </script>
    @endif

    @stack('scripts')
    @yield('scripts')
</body>
</html>
