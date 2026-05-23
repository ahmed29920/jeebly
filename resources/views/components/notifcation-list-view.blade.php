<ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" id="notifications-list"
    aria-labelledby="dropdownMenuButton">
    @forelse($notifications as $notification)
        <li class="mb-2 notification-item" data-id="{{ $notification->id }}">
            <a class="dropdown-item border-radius-md notification-link" href="{{ route('admin.orders.show', $notification->data['order_uuid']) }}">
                <div class="d-flex py-1">
                    <div class="my-auto">
                        <img src="{{ asset('dashboard/img/user-bg.jpg-4.jpg') }}" class="avatar avatar-sm me-3">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                            <span class="font-weight-bold">New order</span> from
                            {{ $notification->data['customer_name'] }}
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                            <i class="fa fa-clock me-1"></i>
                            Order #{{ $notification->data['order_id'] }} • ${{ $notification->data['total'] }} •
                            {{ $notification->data['status'] }}
                        </p>
                    </div>
                </div>
            </a>
        </li>
    @endforeach
</ul>
