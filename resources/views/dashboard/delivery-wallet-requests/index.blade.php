@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Delivery Wallet Requests') }}</h6>
            </div>

            <div class="card-body">
                <table id="wallet-requests-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">{{ __('Delivery') }}</th>
                            <th class="text-center">{{ __('Type') }}</th>
                            <th class="text-center">{{ __('Amount') }}</th>
                            <th class="text-center">{{ __('Order') }}</th>
                            <th class="text-center">{{ __('Notes') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th class="text-center">{{ __('Date') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $walletRequest)
                            <tr>
                                <td class="align-content-center text-center">{{ $walletRequest->id }}</td>
                                <td class="align-content-center text-center">
                                    @if ($walletRequest->delivery?->user)
                                        {{ $walletRequest->delivery->user->name }}<br>
                                        <small class="text-muted">{{ $walletRequest->delivery->user->phone }}</small><br>
                                        <small>{{ __('Wallet') }}: {{ format_currency($walletRequest->delivery->wallet) }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    @if ($walletRequest->type === 'withdrawal')
                                        <span class="badge bg-info">{{ __('Withdrawal') }}</span>
                                    @else
                                        <span class="badge bg-primary">{{ __('COD Settlement') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">{{ format_currency($walletRequest->amount) }}</td>
                                <td class="align-content-center text-center">
                                    @if ($walletRequest->order)
                                        #{{ $walletRequest->order->id }}<br>
                                        <small class="text-muted">{{ ucfirst($walletRequest->order->payment_method) }}</small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $walletRequest->notes ?: '—' }}
                                    @if ($walletRequest->admin_notes)
                                        <br><small class="text-muted">{{ __('Admin') }}: {{ $walletRequest->admin_notes }}</small>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    @php
                                        $badge = match ($walletRequest->status) {
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ ucfirst($walletRequest->status) }}</span>
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $walletRequest->created_at?->format('Y-m-d H:i') }}
                                </td>
                                <td class="align-content-center text-center">
                                    @if ($walletRequest->status === 'pending')
                                        <button type="button"
                                            class="btn btn-sm btn-success process-request-btn"
                                            data-id="{{ $walletRequest->id }}"
                                            data-action="approved">
                                            {{ __('Approve') }}
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-danger process-request-btn"
                                            data-id="{{ $walletRequest->id }}"
                                            data-action="rejected">
                                            {{ __('Reject') }}
                                        </button>
                                    @else
                                        <small class="text-muted">
                                            {{ $walletRequest->processedBy?->name ?? '—' }}<br>
                                            {{ $walletRequest->processed_at?->format('Y-m-d H:i') }}
                                        </small>
                                    @endif
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#wallet-requests-table').DataTable({
                order: [[0, 'desc']],
            });
        });

        $(document).on('click', '.process-request-btn', function() {
            const requestId = $(this).data('id');
            const action = $(this).data('action');
            const isApprove = action === 'approved';

            Swal.fire({
                title: isApprove ? '{{ __('Approve request?') }}' : '{{ __('Reject request?') }}',
                input: 'textarea',
                inputLabel: '{{ __('Admin notes (optional)') }}',
                inputPlaceholder: '{{ __('Add a note...') }}',
                showCancelButton: true,
                confirmButtonText: isApprove ? '{{ __('Approve') }}' : '{{ __('Reject') }}',
                cancelButtonText: '{{ __('Cancel') }}',
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                const form = $('<form>', {
                    action: `/admin/delivery-wallet-requests/${requestId}`,
                    method: 'POST',
                });

                form.append('@csrf');
                form.append('<input type="hidden" name="_method" value="PATCH">');
                form.append('<input type="hidden" name="status" value="' + action + '">');
                form.append('<input type="hidden" name="admin_notes" value="' + (result.value || '') + '">');

                $('body').append(form);
                form.submit();
            });
        });
    </script>
@endpush
