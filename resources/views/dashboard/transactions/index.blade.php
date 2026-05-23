@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-6">
                <a class="btn btn-outline-secondary" href="{{ route('admin.transactions.export') }}">
                    <i class="fa-solid fa-file-export"></i> {{ __('Export') }}
                </a>
            </div>

        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>Orders</h6>
            </div>

            <div class="card-body">

                <table id="transactions-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Order</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Currency</th>
                            <th class="text-center">Method</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td class="align-content-center text-center">{{ $transaction->id }}</td>
                                <td class="align-content-center text-center">{{ $transaction->user->name ?? 'Guest' }}</td>
                                <td class="align-content-center text-center">
                                    @if ($transaction->order)
                                        <a href="{{ route('admin.orders.show', $transaction->order->uuid) }}"
                                            class="text-primary">
                                            {{ __('View Order') }}
                                        </a>
                                        @if ($transaction->order->trashed())
                                            <span class="badge bg-secondary">{{ __('Deleted') }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">{{ __('Unavailable') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">{{ format_currency($transaction->total) }}
                                </td>
                                <td class="align-content-center text-center">{{ currency_code() }}</td>
                                <td class="align-content-center text-center">{{ $transaction->payment_method ?? '--' }}
                                </td>
                                <td class="align-content-center text-center">
                                    @php
                                        switch ($transaction->status) {
                                            case 'paid':
                                                $badge = 'success';
                                                break;
                                            case 'pending':
                                                $badge = 'warning';
                                                break;
                                            case 'faild':
                                                $badge = 'danger';
                                                break;
                                            default:
                                                $badge = 'secondary';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $transaction->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('admin.transactions.show', $transaction->uuid) }}"
                                        class="text-info mx-2 btn-sm">
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
    <script>
        $(document).ready(function() {
            $('#transactions-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-4"l><"col-md-4 text-center bulk-col"><"col-md-4"f>>rtip',
            });
        });

        handleDeleteAjax('.delete-btn', 'Transaction has been deleted successfully.');
    </script>
@endpush
