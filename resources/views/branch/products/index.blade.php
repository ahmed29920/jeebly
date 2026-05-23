@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Branch Products') }}</h6>
            </div>
            <div class="card-body">
                <table id="branch-products-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('ID') }}</th>
                            <th class="text-center">{{ __('Name') }}</th>
                            <th class="text-center">{{ __('SKU') }}</th>
                            <th class="text-center">{{ __('Image') }}</th>
                            <th class="text-center">{{ __('Type') }}</th>
                            <th class="text-center">{{ __('Stock') }}</th>
                            <th class="text-center">{{ __('Unit') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $branchId = auth()->user()->branch_id; @endphp
                        @foreach ($products as $product)
                            @php
                                $typeBadge = $product->type === 'variable' ? 'bg-primary' : 'bg-info';
                                $stockValue = 0;
                                if ($product->type === 'simple') {
                                    $branchStock = $product->branchProductStocks->where('branch_id', $branchId)->first();
                                    $stockValue = $branchStock ? $branchStock->quantity : 0;
                                } else {
                                    $stockValue = $product->variants->sum(function ($variant) use ($branchId) {
                                        $variantStock = $variant->branchVariantStocks->where('branch_id', $branchId)->first();
                                        return $variantStock ? $variantStock->quantity : 0;
                                    });
                                }
                            @endphp
                            <tr>
                                <td class="text-center align-middle">{{ $product->id }}</td>
                                <td class="text-center align-middle">{{ $product->getTranslation('name','en') }}</td>
                                <td class="text-center align-middle">{{ $product->sku }}</td>
                                <td class="text-center align-middle">
                                    <img src="{{ $product->images()->first()?->image_path }}"
                                        alt="{{ $product->getTranslation('name', 'en') }}" class="avatar avatar-xl">
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge {{ $typeBadge }}">
                                        {{ ucfirst($product->type ?? 'simple') }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge {{ $stockValue > 0 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $stockValue }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    @if($product->unit)
                                        {{ is_array($product->unit->code) ? ($product->unit->code['en'] ?? '') : $product->unit->code }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td  class="text-center align-middle">
                                    <a href="{{ route('branch.products.show', $product->slug) }}"
                                        class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a>
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
            $('#branch-products-table').DataTable();
        });
    </script>
@endpush

