@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.booking-lists.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('admin.booking-lists.edit', $bookingList->id) }}" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Left Side: Booking Details --}}
            <div class="col-md-8">
                {{-- Booking Information --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Booking Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p><strong>Booking ID:</strong> {{ $bookingList->id }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p><strong>Status:</strong>
                                    @php
                                        switch ($bookingList->status) {
                                            case 'confirmed':
                                                $badge = 'success';
                                                break;
                                            case 'pending':
                                                $badge = 'warning';
                                                break;
                                            case 'cancelled':
                                                $badge = 'danger';
                                                break;
                                            case 'fulfilled':
                                                $badge = 'info';
                                                break;
                                            default:
                                                $badge = 'secondary';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($bookingList->status ?? 'pending') }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p><strong>Quantity:</strong>
                                    <span class="badge bg-info">{{ $bookingList->quantity }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p><strong>Expected At:</strong>
                                    @if($bookingList->expected_at)
                                        {{ $bookingList->expected_at->format('Y-m-d H:i') }}
                                        <br>
                                        <small class="text-muted">{{ $bookingList->expected_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p><strong>Notified:</strong>
                                    @if($bookingList->notified)
                                        <span class="badge bg-success">
                                            <i class="fa fa-check"></i> Yes
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fa fa-times"></i> No
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p><strong>Created At:</strong> {{ $bookingList->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p><strong>Updated At:</strong> {{ $bookingList->updated_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Information --}}
                @if($bookingList->product)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Product Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            @if($bookingList->product->images && $bookingList->product->images->first())
                                <img src="{{ asset('storage/' . $bookingList->product->images->first()->path) }}"
                                     class="rounded me-3"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            @endif
                            <div>
                                <h5>{{ $bookingList->product->name }}</h5>
                                <p><strong>SKU:</strong> {{ $bookingList->product->sku }}</p>
                                <p><strong>Type:</strong>
                                    <span class="badge {{ $bookingList->product->type == 'variable' ? 'bg-primary' : 'bg-info' }}">
                                        {{ ucfirst($bookingList->product->type ?? 'simple') }}
                                    </span>
                                </p>
                                <p><strong>Price:</strong> {{ format_currency($bookingList->product->manager()->price()) }}</p>
                                <p><strong>Stock:</strong>
                                    <span class="badge {{ $bookingList->product->manager()->stock() > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $bookingList->product->manager()->stock() }}
                                    </span>
                                </p>
                                @if($bookingList->product->slug)
                                <a href="{{ route('admin.products.show', $bookingList->product->slug) }}"
                                   class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fa fa-eye"></i> View Product
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="text-muted">Product has been deleted</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right Side: User Information --}}
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>User Information</h5>
                    </div>
                    <div class="card-body">
                        @if($bookingList->user)
                            <p><strong>Name:</strong> {{ $bookingList->user->name }}</p>
                            <p><strong>Email:</strong> {{ $bookingList->user->email }}</p>
                            <p><strong>Phone:</strong> {{ $bookingList->user->phone ?? 'N/A' }}</p>
                            @if($bookingList->user->role)
                                <p><strong>Role:</strong>
                                    <span class="badge bg-info">{{ ucfirst($bookingList->user->role) }}</span>
                                </p>
                            @endif
                        @else
                            <p class="text-muted">User not found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

