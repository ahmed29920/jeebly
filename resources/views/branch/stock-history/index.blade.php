@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Branch Stock History') }}</h6>
            <span class="badge bg-gradient-primary">{{ $history->total() }} {{ __('records') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Variant') }}</th>
                            <th class="text-center">{{ __('Type') }}</th>
                            <th class="text-center">{{ __('Change') }}</th>
                            <th class="text-center">{{ __('Before') }}</th>
                            <th class="text-center">{{ __('After') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $item)
                            <tr>
                                <td class="text-sm">{{ $item->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="text-sm">
                                    {{ $item->product?->getTranslation('name','en') ?? '-' }}
                                </td>
                                <td class="text-sm">
                                    {{ $item->productVariant?->getTranslation('name','en') ?? '-' }}
                                </td>
                                <td class="text-center">
                                    {{-- convert type to human readable --}}
                                    @php
                                        $type = $item->type;
                                        $type = str_replace('_', ' ', $type);
                                        $type = ucwords($type);
                                        $color =  $item->type == 'manual_add' ? 'success' : 'info';
                                        $color =  $item->type == 'manual_update' ? 'info' : $color;
                                        $color =  $item->type == 'order_decrease' ? 'danger' : $color;
                                        $color =  $item->type == 'order_cancel' ? 'warning' : $color;
                                        $color =  $item->type == 'adjustment' ? 'primary' : $color;
                                    @endphp
                                    <span class="badge bg-gradient-{{ $color }} text-uppercase">{{ $type }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ ($item->quantity_change ?? 0) >= 0 ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                        {{ $item->quantity_change ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-center text-sm">{{ $item->quantity_before ?? 0 }}</td>
                                <td class="text-center text-sm">{{ $item->quantity_after ?? 0 }}</td>
                                <td class="text-sm">{{ $item->user?->name ?? '-' }}</td>
                                <td class="text-sm">{{ $item->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">{{ __('No history found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $history->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

