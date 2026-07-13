@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-6">
                <a class="btn btn-outline-secondary" href="{{ route('admin.orders.export') }}">
                    <i class="fa-solid fa-file-export"></i> {{ __('Export') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>Orders</h6>
            </div>

            <div class="card-body">

                <table id="orders-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Payment</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr data-order-id="{{ $order->id }}">
                                <td class="align-content-center text-center">{{ $order->id }}</td>
                                <td class="align-content-center text-center">{{ $order->user->name ?? 'Guest' }}</td>
                                <td class="align-content-center text-center">{{ format_currency($order->total) }}</td>
                                <td class="align-content-center text-center">
                                    @php
                                        switch ($order->status) {
                                            case 'completed':
                                                $badge = 'success';
                                                break;
                                            case 'processing':
                                                $badge = 'info';
                                                break;
                                            case 'pending':
                                                $badge = 'warning';
                                                break;
                                            case 'shipped':
                                            case 'out_for_delivery':
                                                $badge = 'warning';
                                                break;
                                            case 'canceled':
                                            case 'cancelled':
                                                $badge = 'danger';
                                                break;
                                            default:
                                                $badge = 'secondary';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="align-content-center text-center">
                                    {{ ucfirst($order->payment_method) }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('branch.orders.show', $order->uuid) }}" class="text-info mx-2 btn-sm">
                                        <i class="fa fa-eye"></i>
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
    @include('partials.realtime-orders-table', ['showBranchColumn' => false])
@endpush
