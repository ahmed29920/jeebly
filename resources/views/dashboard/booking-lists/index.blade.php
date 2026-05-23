@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Booking Lists') }}</h6>
            </div>

            <div class="card-body">
                <table id="booking-lists-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Expected At</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Notified</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookingLists as $bookingList)
                            <tr>
                                <td class="align-content-center text-center">{{ $bookingList->id }}</td>
                                <td class="align-content-center text-center">
                                    {{ $bookingList->user->name ?? 'N/A' }}
                                    <br>
                                    <small class="text-muted">{{ $bookingList->user->email ?? '' }}</small>
                                </td>
                                <td class="align-content-center text-center">
                                    @if($bookingList->product)
                                        <div class="d-flex align-items-center justify-content-center">
                                            @if($bookingList->product->images && $bookingList->product->images->first())
                                                <img src="{{ asset('storage/' . $bookingList->product->images->first()->path) }}"
                                                     class="rounded me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <div class="text-start">
                                                <strong>{{ $bookingList->product->name }}</strong>
                                                <br>
                                                <small class="text-muted">SKU: {{ $bookingList->product->sku }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Product Deleted</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    <span class="badge bg-info">{{ $bookingList->quantity }}</span>
                                </td>
                                <td class="align-content-center text-center">
                                    @if($bookingList->expected_at)
                                        {{ $bookingList->expected_at->format('Y-m-d H:i') }}
                                        <br>
                                        <small class="text-muted">{{ $bookingList->expected_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
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
                                </td>
                                <td class="align-content-center text-center">
                                    @if($bookingList->notified)
                                        <span class="badge bg-success">
                                            <i class="fa fa-check"></i> Yes
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fa fa-times"></i> No
                                        </span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $bookingList->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('admin.booking-lists.show', $bookingList->id) }}"
                                       class="text-info mx-2 btn-sm" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.booking-lists.edit', $bookingList->id) }}"
                                       class="text-primary mx-2 btn-sm" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#booking-lists-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-4"l><"col-md-4 text-center bulk-col"><"col-md-4"f>>rtip',
                order: [[0, 'desc']],
                pageLength: 25
            });
        });
    </script>
@endpush

