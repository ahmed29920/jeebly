@auth
    @php
        $reverbKey = config('broadcasting.connections.reverb.key');
        $reverbHost = config('broadcasting.connections.reverb.options.host');
        $reverbPort = (int) config('broadcasting.connections.reverb.options.port', 443);
        $reverbScheme = config('broadcasting.connections.reverb.options.scheme', 'https');
    @endphp

    @if ($reverbKey && $reverbHost)
        <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const REVERB_KEY = @json($reverbKey);
                const REVERB_HOST = @json($reverbHost);
                const REVERB_PORT = @json($reverbPort);
                const forceTLS = @json($reverbScheme === 'https');
                const userRole = @json(auth()->user()->role);
                const branchId = @json(auth()->user()->branch_id);
                const orderShowBase = @json(
                    auth()->user()->branch_id
                        ? url('/branch/orders')
                        : url('/admin/orders')
                );
                const currencySymbol = @json(currency_symbol());
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const pusher = new Pusher(REVERB_KEY, {
                    wsHost: REVERB_HOST,
                    wsPort: REVERB_PORT,
                    wssPort: REVERB_PORT,
                    forceTLS: forceTLS,
                    cluster: 'mt1',
                    disableStats: true,
                    enabledTransports: ['ws', 'wss'],
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    },
                });

                const channels = [];

                if (userRole === 'admin' || (userRole === 'employee' && !branchId)) {
                    channels.push(pusher.subscribe('private-admin.orders'));
                }

                if (branchId) {
                    channels.push(pusher.subscribe('private-branch.' + branchId + '.orders'));
                }

                channels.forEach(function (channel) {
                    channel.bind('order.created', function (data) {
                        handleOrderCreated(data);
                    });

                    channel.bind('order.updated', function (data) {
                        handleOrderUpdated(data);
                    });
                });

                function handleOrderCreated(data) {
                    if (branchId && data.branch_id && Number(data.branch_id) !== Number(branchId)) {
                        return;
                    }

                    appendOrderNotification(data);
                    showRealtimeToast(
                        'New order #' + data.id + ' from ' + (data.customer_name || 'Customer')
                    );

                    document.dispatchEvent(new CustomEvent('order:created', { detail: data }));
                }

                function handleOrderUpdated(data) {
                    if (branchId) {
                        const belongsHere = data.branch_id && Number(data.branch_id) === Number(branchId);
                        document.dispatchEvent(new CustomEvent('order:updated', {
                            detail: Object.assign({}, data, { _remove: !belongsHere }),
                        }));
                        return;
                    }

                    document.dispatchEvent(new CustomEvent('order:updated', { detail: data }));
                }

                function appendOrderNotification(order) {
                    const list = document.getElementById('notifications-list');
                    if (!list) {
                        return;
                    }

                    const li = document.createElement('li');
                    li.className = 'mb-2 notification-item';
                    li.innerHTML = `
                        <a class="dropdown-item border-radius-md" href="${orderShowBase}/${order.uuid}">
                            <div class="d-flex py-1">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/user-bg.jpg-4.jpg') }}" class="avatar avatar-sm me-3">
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="text-sm font-weight-normal mb-1">
                                        <span class="font-weight-bold">New order</span> from ${order.customer_name || 'Customer'}
                                    </h6>
                                    <p class="text-xs text-secondary mb-0">
                                        <i class="fa fa-clock me-1"></i>
                                        Order #${order.id} • ${order.total} ${currencySymbol} • ${order.status}
                                    </p>
                                </div>
                            </div>
                        </a>
                    `;

                    list.prepend(li);

                    const badge = document.querySelector('#dropdownMenuButton .badge');
                    if (badge) {
                        badge.classList.remove('d-none');
                    }
                }

                function showRealtimeToast(message) {
                    const container = document.querySelector('.toast-container');
                    if (!container) {
                        return;
                    }

                    const toast = document.createElement('div');
                    toast.className = 'toast align-items-center text-bg-primary border-0 show';
                    toast.setAttribute('role', 'alert');
                    toast.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">${message}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    `;

                    container.appendChild(toast);

                    setTimeout(function () {
                        toast.remove();
                    }, 5000);
                }
            });
        </script>
    @endif
@endauth
