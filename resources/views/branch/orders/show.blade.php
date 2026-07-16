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
                <span class="mx-2 text-warning cursor-pointer transfer-to-admin-action" data-order-id="{{ $order->id }}">
                    <i class="fa-solid fa-arrow-up"></i>
                    {{ __('Transfer to Admin') }}
                </span>
            @elseif ($order->status == 'processing')
                <span class="mx-2 text-info cursor-pointer status-action" data-status="shipped">
                    <i class="fa-solid fa-truck"></i>
                    {{ __('Ship') }}
                </span>
                <a class="mx-2 text-success" href="{{ route('branch.orders.download-invoice', $order->id) }}"
                    target="_blank">
                    <i class="fa-solid fa-receipt"></i>
                    {{ __('Download Invoice') }}
                </a>
            @elseif ($order->status == 'shipped')
                <a class="mx-2 text-success" href="{{ route('branch.orders.download-invoice', $order->id) }}"
                    target="_blank">
                    <i class="fa-solid fa-receipt"></i>
                    {{ __('Download Invoice') }}
                </a>
                <span class="mx-2 text-info cursor-pointer status-action" data-status="completed">
                    <i class="fa-solid fa-check"></i>
                    {{ __('Complete') }}
                </span>
            @elseif ($order->status == 'completed')
                <a class="mx-2 text-success" href="{{ route('branch.orders.download-invoice', $order->id) }}"
                    target="_blank">
                    <i class="fa-solid fa-receipt"></i>
                    {{ __('Download Invoice') }}
                </a>
            @endif
        </div>

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
                        <form action="{{ route('branch.orders.comments.store', $order) }}" method="POST">
                            @csrf
                            <textarea class="form-control" name="comment" rows="3" placeholder="Write your comment"></textarea>
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" value="1" name="notify" id="notify">
                                <label class="form-check-label" for="notify">Notify Customer</label>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                        </form>
                    </div>
                </div>
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
                            @if($order->branch)
                                <span class="badge bg-info">{{ $order->branch->getTranslation('name','en') }}</span>
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
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ];
                        @endphp

                        <p>Status:
                            <span class="badge bg-{{ $statusClasses[$order->status] ?? 'secondary' }}">
                                {{ ucfirst($order->status) }}
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
                @endif

            </div>
        </div>
    </div>
    <!-- Hidden form -->
    <form id="createInvoiceForm" action="{{ route('branch.orders.create-invoice', $order->id) }}" method="POST"
        style="display: none;">
        @csrf
    </form>

    <form id="StatusForm" action="{{ route('branch.orders.update', $order->id) }}" method="POST" style="display: none;">
        <input type="hidden" name="status" id="status_input">
        <input type="hidden" name="delivery_id" id="delivery_id_input">
        @method('PUT')
        @csrf
    </form>

    <form id="TransferToAdminForm" action="{{ route('branch.orders.transfer-to-admin', $order->id) }}" method="POST" style="display: none;">
        @csrf
    </form>

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
                            @foreach (($deliveryMen ?? collect()) as $delivery)
                                <option value="{{ $delivery->id }}">
                                    {{ $delivery->user->name ?? '-' }}
                                    @if ($delivery->branch_id)
                                        - {{ $delivery->branch->name }}
                                    @else
                                        - {{ __('All Branches') }}
                                    @endif
                                    @if ($delivery->is_online)
                                        ({{ __('Online') }})
                                    @else
                                        ({{ __('Offline') }})
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
                        const modal = new bootstrap.Modal(document.getElementById('deliveryManModal'));
                        modal.show();
                    } else {
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

                document.getElementById('delivery_id_input').value = deliveryId;
                document.getElementById('status_input').value = 'shipped';

                const modal = bootstrap.Modal.getInstance(document.getElementById('deliveryManModal'));
                modal.hide();

                document.getElementById('StatusForm').submit();
            });
        }

        document.querySelectorAll('.transfer-to-admin-action').forEach(btn => {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: "{{ __('Transfer Order to Admin') }}",
                    text: "{{ __('Are you sure you want to transfer this order to admin? The admin will reassign it to a suitable branch.') }}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "{{ __('Yes, Transfer to Admin') }}",
                    cancelButtonText: "{{ __('Cancel') }}",
                    confirmButtonColor: "#ffc107",
                    cancelButtonColor: "#6c757d"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('TransferToAdminForm').submit();
                    }
                });
            });
        });
    </script>
@endpush
