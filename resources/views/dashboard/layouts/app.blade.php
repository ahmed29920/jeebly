<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if (app()->getLocale() == 'ar') dir="rtl" @endif>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('app_icon')) }}">
    <title>{{ setting('app_name', config('app.name')) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS Files -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link id="pagestyle" href="{{ asset('dashboard') }}/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{ asset('dashboard/css/glass-theme.css') }}"> --}}

</head>

<body id="page-top" class="g-sidenav-show  @if (app()->getLocale() == 'ar') rtl @endif  bg-gray-100"
    @if (app()->getLocale() == 'ar') dir="rtl" @endif>


    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    @include('dashboard.layouts.menu')

    <main class="main-content position-relative border-radius-lg overflow-hidden">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm">
                            <a class="opacity-5 text-white" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text text-body">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Type here...">
                        </div>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item dropdown px-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton2"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user  text-white cursor-pointer"></i>
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                                aria-labelledby="dropdownMenuButton2">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="{{ route('admin.profile') }}">
                                        <i class="fa-solid fa-user"></i> {{ __('Profile') }}
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item border-radius-md">
                                            <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
                                        </button>
                                    </form>

                                </li>
                            </ul>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item dropdown mx-1 px-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span
                                    class="position-absolute top-25 start-75 translate-middle badge rounded-pill p-1 bg-danger"></span>
                                <i class="fa fa-bell cursor-pointer"></i>
                            </a>
                            <x-notifcation-list-view />
                        </li>
                        <li class="nav-item d-flex align-items-center mx-2 px-1">
                            <form method="POST" action="{{ route('toggle-language') }}">
                                @csrf
                                <button type="submit"
                                    class="btn btn-link nav-link text-white font-weight-bold p-0 m-0">
                                    <i class="fa-solid fa-earth-americas"></i>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->


        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
            @if (session('success'))
                <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="toast align-items-center text-bg-danger border-0 show" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>


        @yield('content')

    </main>





    <!--   Core JS Files   -->
    <script src="{{ asset('dashboard') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('dashboard') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('dashboard') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('dashboard') }}/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="{{ asset('dashboard') }}/js/plugins/chartjs.min.js"></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#5e72e4",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js"
        integrity="sha512-6BTOlkauINO65nLhXhthZMtepgJSghyimIalb+crKRPhvhmsCdnIuGcVbR5/aQY2A+260iC1OPy1oCdB6pSSwQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('dashboard') }}/js/argon-dashboard.min.js?v=2.1.0"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea[name^="description"]',
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toastElList = [].slice.call(document.querySelectorAll('.toast'))
            toastElList.map(function(toastEl) {
                let toast = new bootstrap.Toast(toastEl, {
                    delay: 4000
                });
                toast.show();
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('dashboard/js/custom.js') }}"></script>
    @stack('scripts')
    <script>
        document.querySelectorAll('.ps').forEach(el => {
            el.classList.remove('ps--active-x');
        });
    </script>
    {{-- <script>
        let ws = new WebSocket(
            "ws://geeble.test:8080/app/prht10exgewvwqclwqet?protocol=7&client=js&version=8.4.0"
        );

        ws.onopen = () => {
            console.log("✅ Connected to Reverb");

            ws.send(JSON.stringify({
                event: "pusher:subscribe",
                data: {
                    channel: "orders"
                }
            }));
        };

        ws.onerror = (err) => console.error("❌ Connection error:", err);

        ws.onclose = (e) => console.warn("⚠️ Connection closed:", e);

        ws.onmessage = (msg) => {
            console.log("📩 Message from server:", msg.data);

            let parsed;
            try {
                parsed = JSON.parse(msg.data);
            } catch (e) {
                console.error("❌ Failed to parse message:", e);
                return;
            }

            if (parsed.event === "pusher:ping") {
                ws.send(JSON.stringify({
                    event: "pusher:pong",
                    data: {}
                }));
            }

            // if (parsed.event === "NewOrderEvent") {
            //     const order = parsed.data;
            //     alert(`📦 New order received!
        //     Order ID: ${order.id}
        //     Customer: ${order.customer_name}
        //     Total: $${order.total}
        //     Status: ${order.status}`);
            // }
            ws.onmessage = (msg) => {
                let parsed;
                try {
                    parsed = JSON.parse(msg.data);
                } catch (e) {
                    return;
                }

                if (parsed.event === "pusher:ping") {
                    ws.send(JSON.stringify({
                        event: "pusher:pong",
                        data: {}
                    }));
                }

                if (parsed.event === "NewOrderEvent") {
                    const order = JSON.parse(parsed.data);
                    appendNotification(order);
                }
            };

            function appendNotification(order) {
                console.log(order);
                console.log(typeof order);
                const list = document.getElementById("notifications-list");

                const li = document.createElement("li");
                li.classList.add("mb-2");

                li.innerHTML = `
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                        <div class="d-flex py-1">
                            <div class="my-auto">
                                <img src="{{ asset('dashboard/img/user-bg.jpg-4.jpg') }}" class="avatar avatar-sm me-3">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                    <span class="font-weight-bold">New order</span> from ${order.customer_name}
                                </h6>
                                <p class="text-xs text-secondary mb-0">
                                    <i class="fa fa-clock me-1"></i>
                                    Order #${order.id} • ${order.total} {{ currency_symbol() }} • ${order.status}
                                </p>
                            </div>
                        </div>
                    </a>
                    `;

                list.prepend(li);
            }
        };
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".notification-link").forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();

                    const li = this.closest(".notification-item");
                    const id = li.dataset.id;
                    const url = this.getAttribute("href");

                    fetch(`/admin/notifications/${id}/mark-as-read`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({})
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log("Notification marked as read:", id);
                            li.classList.add("bg-light");
                            window.location.href = url;
                        })
                        .catch(err => console.error(err));
                });
            });
        });
    </script>



</body>

</html>
