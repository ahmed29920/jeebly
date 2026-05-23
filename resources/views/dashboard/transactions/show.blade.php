@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">{{ __('Transaction Details') }}</div>
                    <div class="card-body">
                        <p><strong>{{ __('ID') }}:</strong> {{ $transaction->id }}</p>
                        <p><strong>{{ __('Order ID') }}:</strong>
                            @if ($transaction->order)
                                #{{ $transaction->order->id }}
                                @if ($transaction->order->trashed())
                                    <span class="badge bg-secondary">{{ __('Deleted') }}</span>
                                @endif
                            @else
                                <span class="text-muted">{{ __('Unavailable') }}</span>
                            @endif
                        </p>
                        <p><strong>{{ __('Amount') }}:</strong> {{ format_currency($transaction->amount) }}</p>
                        <p><strong>{{ __('Payment Method') }}:</strong> {{ ucfirst($transaction->payment_method ?? '-') }}</p>
                        <p>
                            <strong>{{ __('Status') }}:</strong>
                            @if($transaction->status === 'paid')
                                <span class="badge bg-success">{{ __('Paid') }}</span>
                            @elseif($transaction->status === 'pending')
                                <span class="badge bg-warning">{{ __('Pending') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Failed') }}</span>
                            @endif
                        </p>
                        <p><strong>{{ __('Transaction ID') }}:</strong> {{ $transaction->transaction_id ?? '-' }}</p>
                        <p><strong>{{ __('Reference Number') }}:</strong> {{ $transaction->reference_number ?? '-' }}</p>
                        <p><strong>{{ __('Date') }}:</strong> {{ $transaction->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                @if ($transaction->order)
                    <div class="card mb-3">
                        <div class="card-header">{{ __('Order Items') }} ({{ count($transaction->order->items) }})</div>
                        <div class="card-body">
                            @foreach ($transaction->order->items as $item)
                                @include('dashboard.partials.order-item', ['item' => $item])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                @if ($transaction->order)
                    <div class="card mb-3">
                        <div class="card-header border border-bottom">{{ __('Customer') }}</div>
                        <div class="card-body">
                            <p>{{ __('Name') }}: <strong>{{ $transaction->order->user->name ?? '-' }}</strong></p>
                            <p>{{ __('Email') }}: <strong>{{ $transaction->order->user->email ?? '-' }}</strong></p>
                            <p>{{ __('Phone') }}: <strong>{{ $transaction->order->user->phone ?? '-' }}</strong></p>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header border border-bottom">{{ __('Order Information') }}</div>
                        <div class="card-body">
                            <p>{{ __('Order Date') }}: <strong>{{ $transaction->order->created_at->format('Y-m-d H:i') }}</strong></p>
                            <p>{{ __('Order Status') }}:
                                <span class="badge bg-info">{{ ucfirst($transaction->order->status) }}</span>
                            </p>
                            <p>{{ __('Payment Status') }}:
                                <span class="badge
                                    @if ($transaction->order->payment_status == 'paid') bg-success
                                    @elseif($transaction->order->payment_status == 'pending') bg-warning
                                    @else bg-danger @endif">
                                    {{ $transaction->order->payment_status }}
                                </span>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
