<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (app()->getLocale() === 'ar') dir="rtl" @endif>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset('storage/' .  setting('app_icon')) }}">
    <title>{{ __('Admin Login') }} - {{ setting('app_name', config('app.name')) }}</title>

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="{{asset('dashboard/css/argon-dashboard.css')}}" rel="stylesheet" />

    @if (app()->getLocale() == 'ar')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:slnt,wght@-11..11,200..1000&display=swap" rel="stylesheet">
        <style>
            body * {
                font-family: 'Cairo', sans-serif !important;
            }
        </style>
    @endif
</head>

<body class="">
    <main class="main-content p-0 mt-0">
        <section class="p-0">
            <div class="page-header min-vh-100">
                <div class="container">
                   @yield('content')
                </div>
            </div>
        </section>
    </main>

    <!--   Core JS Files   -->
    <script src="{{asset('dashboard')}}/js/core/popper.min.js"></script>
    <script src="{{asset('dashboard')}}/js/core/bootstrap.min.js"></script>
    <script src="{{asset('dashboard')}}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{asset('dashboard')}}/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{asset('dashboard')}}/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>
