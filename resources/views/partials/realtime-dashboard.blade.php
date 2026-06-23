@auth
    @if (config('services.socket.url') && config('services.socket.secret'))
        <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const socketUrl = @json(config('services.socket.url'));
                const socketSecret = @json(config('services.socket.secret'));
                const userRole = @json(auth()->user()->role);
                const branchId = @json(auth()->user()->branch_id);
                const orderShowBase = @json(
                    auth()->user()->branch_id
                        ? url('/branch/orders')
                        : url('/admin/orders')
                );
                const currencySymbol = @json(currency_symbol());

                if (!socketUrl || !socketSecret) {
                    return;
                }

                const socket = io(socketUrl, {
                    auth: { secret: socketSecret },
                    transports: ['websocket'],
                });

                socket.on('connect', function () {
                    if (userRole === 'admin' || (userRole === 'employee' && !branchId)) {
                        socket.emit('join', 'admin-orders');
                    }

                    if (branchId) {
                        socket.emit('join', 'branch-' + branchId + '-orders');
                    }
                });

                socket.on('order.created', function (data) {
                    if (branchId && data.branch_id && Number(data.branch_id) !== Number(branchId)) {
                        return;
                    }

                    appendOrderNotification(data);
                    showRealtimeToast(
                        'New order #' + data.id + ' from ' + (data.customer_name || 'Customer')
                    );

                    document.dispatchEvent(new CustomEvent('order:created', { detail: data }));
                });

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
