@extends('dashboard.layouts.app')


@section('content')
    <div class="container-fluid">
        {{-- Action Buttons --}}
        <div class="mb-3">
            @if ($order->status == 'pending')
                <span class="mx-2 text-success cursor-pointer status-action" data-action="invoice">
                    <i class="fa-solid fa-receipt"></i>
                    {{ __('Invoice') }}
                </span>
                <span class="mx-2 text-danger cursor-pointer status-action" data-status="cancelled">
                    <i class="fa-solid fa-ban"></i>
                    {{ __('Cancel') }}
                </span>
            @elseif ($order->status == 'processing')
                <span class="mx-2 text-info cursor-pointer status-action" data-status="shipped">
                    <i class="fa-solid fa-truck"></i>
                    {{ __('Ship') }}
                </span>
                <a class="mx-2 text-success" href="{{ route('admin.orders.download-invoice', $order->id) }}"
                    target="_blank">
                    <i class="fa-solid fa-receipt"></i>
                    {{ __('Download Invoice') }}
                </a>
            @elseif ($order->status == 'shipped')
                <a class="mx-2 text-success" href="{{ route('admin.orders.download-invoice', $order->id) }}"
                    target="_blank">
                    <i class="fa-solid fa-receipt"></i>
                    {{ __('Download Invoice') }}
                </a>
                <span class="mx-2 text-info cursor-pointer status-action" data-status="out_for_delivery">
                    <i class="fa-solid fa-truck-fast"></i>
                    {{ __('Out for Delivery') }}
                </span>
            @elseif ($order->status == 'out_for_delivery')
                <a class="mx-2 text-success" href="{{ route('admin.orders.download-invoice', $order->id) }}"
                    target="_blank">
                    <i class="fa-solid fa-receipt"></i>
                    {{ __('Download Invoice') }}
                </a>
                <span class="mx-2 text-info cursor-pointer status-action" data-status="completed">
                    <i class="fa-solid fa-check"></i>
                    {{ __('Complete') }}
                </span>
            @elseif ($order->status == 'completed')
                <a class="mx-2 text-success" href="{{ route('admin.orders.download-invoice', $order->id) }}"
                    target="_blank">
                    <i class="fa-solid fa-receipt"></i>
                    {{ __('Download Invoice') }}
                </a>
            @endif
        </div>

        {{-- Branch Assignment Alert (if order not assigned to branch) --}}
        @if ($order->branch_id === null && isset($branches))
            <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon-shape icon-lg bg-gradient-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="fas fa-exclamation-triangle fs-4"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <h5 class="mb-1 fw-bold">
                                        <span class="text-white">
                                            {{ __('Order Not Assigned to Branch') }}
                                        </span>
                                    </h5>
                                    <p class="text-white mb-0">
                                        {{ __('This order has been transferred to admin and needs to be assigned to a branch.') }}
                                    </p>
                                </div>
                                <div class="mt-2 mt-md-0">
                                    <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal"
                                        data-bs-target="#assignBranchModal">
                                        <i class="fas fa-building me-2"></i>{{ __('Assign to Branch') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            {{-- Left Side: Order Items --}}
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">Order Items ({{ count($order->items) }})</div>
                    <div class="card-body">
                        @foreach ($order->items as $item)
                            @include('dashboard.partials.order-item', ['item' => $item])
                        @endforeach
                    </div>
                </div>

                {{-- Order Summary --}}
                @include('partials.orders.financial-summary', ['order' => $order])
                {{-- Note --}}
                @if($order->note)
                <div class="card mb-3">
                    <div class="card-body">
                        <p>Notes:</p>
                        <br>
                        <p>
                            {{$order->note}}
                        </p>
                    </div>
                </div>
                @endif
                {{-- Comments --}}
                <div class="card">
                    <div class="card-header">Comments</div>
                    <div class="card-body">
                        @foreach ($order->comments as $comment)
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-auto">
                                        <strong>{{ $comment->user->name }}:</strong>
                                        <br>
                                        <small class="text-muted">{{ $comment->created_at->format('Y-m-d H:i') }} </small>
                                    </div>
                                    <div class="col border p-2 rounded">
                                        <p class="mb-0">{{ $comment->comment }}</p>
                                    </div>
                                </div>
                                <hr class="border border-secondary">
                            </div>
                        @endforeach
                        <!-- Add New Comment -->
                        <form action="{{ route('admin.orders.comments.store', $order) }}" method="POST">
                            @csrf
                            <textarea class="form-control" name="comment" rows="3" placeholder="Write your comment"></textarea>
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" value="1" name="notify"
                                    id="notify">
                                <label class="form-check-label" for="notify">Notify Customer</label>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                        </form>
                    </div>
                </div>

                {{-- Map --}}
                {{-- Live Delivery Tracking Map --}}
                @if ($order->delivery_id && $order->status == 'out_for_delivery')
                    <div class="card mt-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fa-solid fa-location-dot me-2"></i>{{ __('Live Delivery Location') }}
                            </span>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="followBtn">
                                    Follow: ON
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clearPathBtn">
                                    Clear Path
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="mb-2 small text-muted">
                                <span class="me-3">
                                    Channel: <code>private-order-delivery.{{ $order->id }}</code>
                                </span>
                                <span>
                                    Last update: <span id="lastUpdate">-</span>
                                </span>
                            </div>

                            <div id="deliveryMap" style="height: 380px; border-radius: 12px; overflow: hidden;"></div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right Side: Sidebar --}}
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header border border-bottom">
                        Order Info
                    </div>
                    <div class="card-body">
                        <p>Customer : <strong>{{ $order->user->name }}</strong></p>
                        <p>Email : <strong>{{ $order->user->email }}</strong></p>
                        <p>Phone : <strong>{{ $order->user->phone }}</strong></p>
                        <p>Zone :
                            @if(isset($orderZone) && $orderZone)
                                <span class="badge bg-success">{{ $orderZone->name }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Out of zones') }}</span>
                            @endif
                        </p>
                        <p>Branch :
                            @if ($order->branch)
                                <span class="badge bg-info">{{ $order->branch->getTranslation('name', 'en') }}</span>
                            @elseif($order->branch_id === null)
                                <span class="badge bg-warning">{{ __('Not Assigned') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header  border border-bottom">Order Information</div>
                    <div class="card-body">
                        <p>Order Date: <strong>{{ $order->created_at }}</strong></p>
                        @php
                            $statusClasses = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'out_for_delivery' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ];
                        @endphp

                        <p>Status:
                            <span class="badge bg-{{ $statusClasses[$order->status] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header  border border-bottom">Payment and Shipping</div>
                    <div class="card-body">
                        <p>Payment Method: <strong>{{ $order->payment_method }}</strong></p>
                        <p>Payment Status: <span
                                class="badge  @if ($order->payment_status == 'paid') bg-success @elseif($order->payment_status == 'pending') bg-warning @else bg-secondary @endif">{{ $order->payment_status }}</span>
                        </p>
                    </div>
                </div>

                @if ($order->delivery_id)
                    <div class="card mb-3">
                        <div class="card-header border border-bottom">
                            <i class="fa-solid fa-truck me-2"></i>{{ __('Delivery Man') }}
                        </div>
                        <div class="card-body">
                            <p>Name: <strong>{{ $order->delivery->user->name }}</strong></p>
                            <p>Email: <strong>{{ $order->delivery->user->email }}</strong></p>
                            <p>Phone: <strong>
                                    <a href="tel:{{ $order->delivery->user->phone }}" class="text-decoration-none">
                                        {{ $order->delivery->user->phone }}
                                    </a>
                                </strong></p>
                            <p>Plate Number: <strong>{{ $order->delivery->plate_number }}</strong></p>
                            <p>Status:
                                @if ($order->delivery->is_online)
                                    <span class="badge bg-success">{{ __('Online') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Offline') }}</span>
                                @endif
                            </p>
                            @if ($order->delivery_assigned_at)
                                <p>Assigned At: <strong>{{ $order->delivery_assigned_at->format('Y-m-d H:i') }}</strong>
                                </p>
                            @endif
                            <div class="mt-2">
                                <a href="{{ route('admin.deliveries.show', $order->delivery->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-eye me-1"></i>{{ __('View Details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card mb-3">
                        <div class="card-header border border-bottom">
                            <i class="fa-solid fa-truck me-2"></i>{{ __('Delivery Man') }}
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-0">
                                <i class="fa-solid fa-info-circle me-2"></i>{{ __('No delivery man assigned yet') }}
                            </p>
                        </div>
                    </div>
                @endif

                @if ($order->billingAddress)
                    <div class="card mb-3">
                        <div class="card-header border border-bottom">Billing Address</div>
                        <div class="card-body">
                            <p>Name : <strong>{{ $order->billingAddress->name }}</strong></p>
                            <p>Address : <strong>{{ $order->billingAddress->address }}</strong></p>
                            <p>City : <strong>{{ $order->billingAddress->city }}</strong></p>
                            <p>Country : <strong>{{ $order->billingAddress->country }}</strong></p>
                            <p>Contact : <strong>{{ $order->billingAddress->phone }}</strong></p>
                        </div>
                    </div>
                @endif

                @if ($order->shippingAddress)
                    <div class="card mb-3">
                        <div class="card-header border border-bottom">Shipping Address</div>
                        <div class="card-body">
                            <p>Name : <strong>{{ $order->shippingAddress->name }}</strong></p>
                            <p>Address : <strong>{{ $order->shippingAddress->address }}</strong></p>
                            <p>City : <strong>{{ $order->shippingAddress->city }}</strong></p>
                            <p>Country : <strong>{{ $order->shippingAddress->country }}</strong></p>
                            <p>Contact : <strong>{{ $order->shippingAddress->phone }}</strong></p>
                        </div>
                    </div>
                @endif

                @if ($order->branch)
                    <div class="card mb-3">
                        <div class="card-header border border-bottom">Branch</div>
                        <div class="card-body">
                            <p>Name : <strong>{{ $order->branch->name }}</strong></p>
                        </div>
                    </div>
                @elseif($order->branch_id === null)
                    <div class="card mb-3">
                        <div class="card-header border border-bottom">Branch</div>
                        <div class="card-body">
                            <p class="text-muted mb-0">
                                <span class="badge bg-warning">{{ __('Not Assigned') }}</span>
                            </p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <!-- Hidden form -->
    <form id="createInvoiceForm" action="{{ route('admin.orders.create-invoice', $order->id) }}" method="POST"
        style="display: none;">
        @csrf
    </form>

    <form id="StatusForm" action="{{ route('admin.orders.update', $order->id) }}" method="POST"
        style="display: none;">
        <input type="hidden" name="status" id="status_input">
        <input type="hidden" name="delivery_id" id="delivery_id_input">
        @method('PUT')
        @csrf
    </form>

    <!-- Assign Branch Modal -->
    @if ($order->branch_id === null && isset($branches))
        <div class="modal fade" id="assignBranchModal" tabindex="-1" aria-labelledby="assignBranchModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignBranchModalLabel">
                            <i class="fa-solid fa-building me-2"></i>{{ __('Assign Order to Branch') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.orders.assign-to-branch', $order->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="text-muted mb-3">{{ __('Please select a branch to assign this order to:') }}</p>
                            <div class="mb-3">
                                <label for="branch_select" class="form-label fw-semibold">{{ __('Branch') }} <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="branch_select" name="branch_id" required>
                                    <option value="">{{ __('Select Branch') }}</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->getTranslation('name', 'en') }}
                                            @if ($branch->address)
                                                - {{ $branch->getTranslation('address', 'en') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">
                                    {{ __('Select a suitable branch to handle this order') }}
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check me-2"></i>{{ __('Assign to Branch') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delivery Man Selection Modal -->
    <div class="modal fade" id="deliveryManModal" tabindex="-1" aria-labelledby="deliveryManModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deliveryManModalLabel">
                        <i class="fa-solid fa-truck me-2"></i>{{ __('Select Delivery Man') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">{{ __('Please select a delivery man to assign to this order:') }}</p>
                    <div class="mb-3">
                        <label for="delivery_man_select" class="form-label fw-semibold">{{ __('Delivery Man') }} <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="delivery_man_select" required>
                            <option value="">{{ __('Select Delivery Man') }}</option>
                            @foreach ($deliveryMen as $delivery)
                                <option value="{{ $delivery->id }}"
                                    data-branch="{{ $delivery->branch_id ? $delivery->branch->name : __('All Branches') }}">
                                    {{ $delivery->user->name }}
                                    @if ($delivery->branch_id)
                                        - {{ $delivery->branch->name }}
                                    @else
                                        - {{ __('All Branches') }}
                                    @endif
                                    @if ($delivery->is_online)
                                        <span class="badge bg-success ms-2">{{ __('Online') }}</span>
                                    @else
                                        <span class="badge bg-secondary ms-2">{{ __('Offline') }}</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">
                            {{ __('Available delivery men: those without branch or with the same branch as the order') }}
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="confirmShipBtn">
                        <i class="fa-solid fa-check me-2"></i>{{ __('Confirm & Ship') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const createInvoiceBtn = document.querySelector('#createInvoiceBtn');
        if (createInvoiceBtn) {
            createInvoiceBtn.addEventListener('click', function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create an invoice and change order status to Processing?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Create Invoice",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('createInvoiceForm').submit();
                    }
                });
            });
        }


        document.querySelectorAll('.status-action').forEach(btn => {
            btn.addEventListener('click', function() {
                let action = this.dataset.action;
                let status = this.dataset.status;

                if (action === 'invoice') {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Do you want to create an invoice and change order status to Processing?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Create Invoice",
                        cancelButtonText: "Cancel"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('createInvoiceForm').submit();
                        }
                    });
                } else if (status) {
                    if (status === 'shipped') {
                        // Show modal for delivery man selection
                        const modal = new bootstrap.Modal(document.getElementById('deliveryManModal'));
                        modal.show();
                    } else {
                        // For other statuses, show simple confirmation
                        Swal.fire({
                            title: "Are you sure?",
                            text: `Do you want to change order status to ${status}?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: `Yes, ${status.charAt(0).toUpperCase() + status.slice(1)} Order`,
                            cancelButtonText: "Cancel"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('status_input').value = status;
                                document.getElementById('StatusForm').submit();
                            }
                        });
                    }
                }
            });
        });

        // Handle delivery man selection and ship confirmation
        const confirmShipBtn = document.getElementById('confirmShipBtn');
        const deliveryManSelect = document.getElementById('delivery_man_select');

        if (confirmShipBtn && deliveryManSelect) {
            confirmShipBtn.addEventListener('click', function() {
                const deliveryId = deliveryManSelect.value;

                if (!deliveryId) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('Error') }}',
                        text: '{{ __('Please select a delivery man') }}'
                    });
                    return;
                }

                // Set the delivery_id and status, then submit
                document.getElementById('delivery_id_input').value = deliveryId;
                document.getElementById('status_input').value = 'shipped';

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('deliveryManModal'));
                modal.hide();

                // Submit form
                document.getElementById('StatusForm').submit();
            });
        }
    </script>
    @if ($order->delivery_id)
        <style>
            .bike-marker img {
                width: 42px;
                height: 42px;
                transform: translate(-21px, -21px);
            }
        </style>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>

        <script>
            (function() {
                const ORDER_ID = {{ $order->id }};
                const REVERB_KEY = "{{ config('broadcasting.connections.reverb.key') }}";
                const REVERB_HOST = "{{ config('broadcasting.connections.reverb.options.host') }}";
                const REVERB_PORT = {{ (int) config('broadcasting.connections.reverb.options.port') }};

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const lastUpdateEl = document.getElementById('lastUpdate');

                let follow = true;
                document.getElementById('followBtn')?.addEventListener('click', () => {
                    follow = !follow;
                    document.getElementById('followBtn').innerText = `Follow: ${follow ? 'ON' : 'OFF'}`;
                });

                // Map (will be initialized from Redis snapshot)
                let map = null;
                let marker = null;
                let path = [];
                let polyline = null;

                function initMap(lat, lng, zoom = 14) {
                    if (map) return; // already initialized

                    map = L.map('deliveryMap').setView([lat, lng], zoom);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19
                    }).addTo(map);

                    polyline = L.polyline(path, {
                        weight: 4
                    }).addTo(map);
                }

                document.getElementById('clearPathBtn')?.addEventListener('click', () => {
                    path = [];
                    if (polyline) polyline.setLatLngs(path);
                });

                function setMarker(lat, lng) {
                    if (!map) initMap(lat, lng, 14);

                    const bikeIcon = L.icon({
                        iconUrl: "{{ asset('dashboard/img/motorcycle.png') }}",
                        iconSize: [42, 42],
                        iconAnchor: [21, 21], // center
                    });

                    if (!marker) marker = L.marker([lat, lng], {
                        icon: bikeIcon
                    }).addTo(map);
                    else marker.setLatLng([lat, lng]);
                }

                function pushPath(lat, lng) {
                    path.push([lat, lng]);
                    if (path.length > 200) path.shift();
                    if (polyline) polyline.setLatLngs(path);
                }

                // Smooth animation
                let currentPos = null;
                let animId = null;
                const lerp = (a, b, t) => a + (b - a) * t;

                function animateTo(next, duration = 1200) {
                    if (!currentPos) {
                        currentPos = next;
                        setMarker(next.lat, next.lng);
                        pushPath(next.lat, next.lng);
                        if (map) map.setView([next.lat, next.lng], 16);
                        return;
                    }
                    const start = {
                        ...currentPos
                    };
                    const startTime = performance.now();
                    if (animId) cancelAnimationFrame(animId);

                    function frame(now) {
                        const t = Math.min((now - startTime) / duration, 1);
                        const lat = lerp(start.lat, next.lat, t);
                        const lng = lerp(start.lng, next.lng, t);
                        setMarker(lat, lng);

                        if (t < 1) animId = requestAnimationFrame(frame);
                        else {
                            currentPos = next;
                            pushPath(next.lat, next.lng);
                            if (follow && map) map.panTo([next.lat, next.lng], {
                                animate: true
                            });
                        }
                    }
                    animId = requestAnimationFrame(frame);
                }

                
                async function loadInitial() {
                    
                    let lat = 30.0444,
                        lng = 31.2357,
                        zoom = 14;

                    try {
                        const res = await fetch(`/admin/orders/${ORDER_ID}/delivery-location`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        if (res.ok) {
                            const json = await res.json();
                            console.log(json);
                            if (json.found) {
                                const rLat = Number(json.data.lat),
                                    rLng = Number(json.data.lng);
                                if (Number.isFinite(rLat) && Number.isFinite(rLng)) {
                                    lat = rLat;
                                    lng = rLng;
                                    zoom = 16;
                                    currentPos = {
                                        lat,
                                        lng
                                    };
                                    lastUpdateEl.textContent = json.data.updated_at || '-';
                                }
                            }
                        }
                    } catch (e) {
                        // ignore and use fallback
                    }

                    // init map on the chosen start point
                    console.log(lat, lng, zoom);
                    initMap(lat, lng, zoom);

                    // place marker + path if we have a valid position
                    if (currentPos) {
                        setMarker(lat, lng);
                        pushPath(lat, lng);
                        map.setView([lat, lng], zoom);
                    } else {
                        // just show map centered even if no marker data exists
                        map.setView([lat, lng], zoom);
                    }
                }

                // Socket subscribe
                function connect() {
                    const pusher = new Pusher(REVERB_KEY, {
                        wsHost: REVERB_HOST,
                        wsPort: REVERB_PORT,
			wssPort:REVERB_PORT,
                        forceTLS: true,
                        cluster: 'mt1',
			disableStats:true,
                        enabledTransports: ['ws'],
                        authEndpoint: '/broadcasting/auth',
                        auth: {
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        }
                    });

                    const channel = pusher.subscribe(`private-order-delivery.${ORDER_ID}`);

		    channel.bind('pusher:subscription_succeeded', () => {
		        channel.trigger('client-request-location', {
		            requested_by: 'admin'
		        });
		    });

                    channel.bind('client-location-updated', (data) => {
                        const lat = Number(data.lat),
                            lng = Number(data.lng);
                        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;
                        animateTo({
                            lat,
                            lng
                        }, 1200);
                        lastUpdateEl.textContent = data.updated_at || '-';
                    });
                }

                loadInitial().finally(connect);
            })();
        </script>
    @endif

@endpush
