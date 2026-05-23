@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6>{{ __('Branch Details') }}</h6>
                <div>
                    <a href="{{ route('admin.branches.edit', $branch->id) }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Name') }}</h6>
                            <p class="mb-1"><strong>{{ __('English') }}:</strong> {{ $branch->getTranslation('name', 'en') }}</p>
                            <p class="mb-0"><strong>{{ __('Arabic') }}:</strong> {{ $branch->getTranslation('name', 'ar') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Slug') }}</h6>
                            <p>{{ $branch->slug }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Address') }}</h6>
                            <p class="mb-1"><strong>{{ __('English') }}:</strong> {{ $branch->getTranslation('address', 'en') ?? '-' }}</p>
                            <p class="mb-0"><strong>{{ __('Arabic') }}:</strong> {{ $branch->getTranslation('address', 'ar') ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Phone') }}</h6>
                            <p>{{ $branch->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Location') }}</h6>
                            @if($branch->latitude && $branch->longitude)
                                <p class="mb-1"><strong>{{ __('Latitude') }}:</strong> {{ $branch->latitude }}</p>
                                <p class="mb-1"><strong>{{ __('Longitude') }}:</strong> {{ $branch->longitude }}</p>
                                <a href="https://www.google.com/maps?q={{ $branch->latitude }},{{ $branch->longitude }}"
                                   target="_blank" class="btn btn-sm btn-info mt-2">
                                    <i class="fa fa-map-marker-alt"></i> {{ __('View on Map') }}
                                </a>
                            @else
                                <p class="text-muted">{{ __('No location data available') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Status') }}</h6>
                            @if($branch->is_active)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Inactive') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Created At') }}</h6>
                            <p>{{ $branch->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Updated At') }}</h6>
                            <p>{{ $branch->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Branch Stock Section --}}
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fa fa-boxes me-2"></i>{{ __('Branch Stock') }}</h6>
            </div>
            <div class="card-body">
                @if($products && $products->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Product') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('SKU') }}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Type') }}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Stock') }}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Unit') }}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    @if($product->type === 'simple')
                                        @php
                                            $branchStock = $product->branchProductStocks->first();
                                            $stockValue = $branchStock ? $branchStock->quantity : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    @if($product->images->first())
                                                        <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                                             class="avatar avatar-sm me-3"
                                                             alt="{{ $product->getTranslation('name', 'en') }}">
                                                    @else
                                                        <div class="avatar avatar-sm me-3 bg-gradient-secondary d-flex align-items-center justify-content-center">
                                                            <i class="fa fa-image text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $product->getTranslation('name', 'en') }}</h6>
                                                        @if($product->getTranslation('name', 'ar'))
                                                            <p class="text-xs text-secondary mb-0">{{ $product->getTranslation('name', 'ar') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $product->sku }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-sm bg-gradient-info">Simple</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="stock-display-{{ $product->id }}" data-product-id="{{ $product->id }}" data-type="simple">
                                                    <span class="badge badge-sm {{ $stockValue > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }} stock-badge" style="cursor: pointer;">
                                                        {{ $stockValue }}
                                                    </span>
                                                </div>
                                                <div class="stock-edit-{{ $product->id }}" style="display: none;">
                                                    <input type="number"
                                                           class="form-control form-control-sm d-inline-block"
                                                           style="width: 80px; text-align: center;"
                                                           min="0"
                                                           value="{{ $stockValue }}"
                                                           data-product-id="{{ $product->id }}"
                                                           data-type="simple">
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($product->unit)
                                                    <span class="text-xs">
                                                        {{ is_array($product->unit->code) ? ($product->unit->code['en'] ?? '') : $product->unit->code }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <button type="button"
                                                        class="btn btn-sm btn-primary edit-stock-btn"
                                                        data-product-id="{{ $product->id }}"
                                                        data-type="simple"
                                                        data-stock="{{ $stockValue }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @else
                                        {{-- Variable Product - Show Variants --}}
                                        @foreach($product->variants as $variant)
                                            @php
                                                $variantBranchStock = $variant->branchVariantStocks->first();
                                                $variantStockValue = $variantBranchStock ? $variantBranchStock->quantity : 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        @if($variant->images->first())
                                                            <img src="{{ asset('storage/' . $variant->images->first()->path) }}"
                                                                 class="avatar avatar-sm me-3"
                                                                 alt="{{ $variant->getTranslation('name', 'en') }}">
                                                        @elseif($product->images->first())
                                                            <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                                                 class="avatar avatar-sm me-3"
                                                                 alt="{{ $product->getTranslation('name', 'en') }}">
                                                        @else
                                                            <div class="avatar avatar-sm me-3 bg-gradient-secondary d-flex align-items-center justify-content-center">
                                                                <i class="fa fa-image text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0 text-sm">{{ $product->getTranslation('name', 'en') }}</h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                <strong>Variant:</strong> {{ $variant->getTranslation('name', 'en') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $variant->sku }}</p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="badge badge-sm bg-gradient-primary">Variable</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="stock-display-variant-{{ $variant->id }}" data-variant-id="{{ $variant->id }}" data-product-id="{{ $product->id }}" data-type="variant">
                                                        <span class="badge badge-sm {{ $variantStockValue > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }} stock-badge" style="cursor: pointer;">
                                                            {{ $variantStockValue }}
                                                        </span>
                                                    </div>
                                                    <div class="stock-edit-variant-{{ $variant->id }}" style="display: none;">
                                                        <input type="number"
                                                               class="form-control form-control-sm d-inline-block"
                                                               style="width: 80px; text-align: center;"
                                                               min="0"
                                                               value="{{ $variantStockValue }}"
                                                               data-variant-id="{{ $variant->id }}"
                                                               data-product-id="{{ $product->id }}"
                                                               data-type="variant">
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($product->unit)
                                                        <span class="text-xs">
                                                            {{ is_array($product->unit->code) ? ($product->unit->code['en'] ?? '') : $product->unit->code }}
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary edit-stock-btn"
                                                            data-variant-id="{{ $variant->id }}"
                                                            data-product-id="{{ $product->id }}"
                                                            data-type="variant"
                                                            data-stock="{{ $variantStockValue }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-box-open fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">{{ __('No products found for this branch.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Edit stock button click
            $('.edit-stock-btn').on('click', function() {
                const type = $(this).data('type');
                const productId = $(this).data('product-id');
                const variantId = $(this).data('variant-id');
                const currentStock = $(this).data('stock');

                if (type === 'simple') {
                    $('.stock-display-' + productId).hide();
                    $('.stock-edit-' + productId).show();
                    $('.stock-edit-' + productId + ' input').focus().select();
                } else if (type === 'variant') {
                    $('.stock-display-variant-' + variantId).hide();
                    $('.stock-edit-variant-' + variantId).show();
                    $('.stock-edit-variant-' + variantId + ' input').focus().select();
                }
            });

            // Save stock on Enter key or blur
            $(document).on('keypress', 'input[data-type="simple"], input[data-type="variant"]', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    saveStock($(this));
                }
            });

            $(document).on('blur', 'input[data-type="simple"], input[data-type="variant"]', function() {
                saveStock($(this));
            });

            function saveStock($input) {
                const type = $input.data('type');
                const productId = $input.data('product-id');
                const variantId = $input.data('variant-id');
                const newStock = parseInt($input.val()) || 0;

                let data = {};
                let url = '';

                if (type === 'simple') {
                    data = {
                        branch_stocks: {}
                    };
                    data.branch_stocks[{{ $branch->id }}] = newStock;
                    url = '/admin/products/' + productId + '/update-branch-stocks';
                } else if (type === 'variant') {
                    data = {
                        product_variants: [{
                            id: variantId,
                            branch_stocks: {}
                        }]
                    };
                    data.product_variants[0].branch_stocks[{{ $branch->id }}] = newStock;
                    url = '/admin/products/' + productId + '/update-branch-stocks';
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the display
                            if (type === 'simple') {
                                const $badge = $('.stock-display-' + productId + ' .stock-badge');
                                $badge.text(newStock);
                                $badge.removeClass('bg-gradient-success bg-gradient-secondary')
                                      .addClass(newStock > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary');
                                $('.stock-edit-' + productId).hide();
                                $('.stock-display-' + productId).show();
                                $('.edit-stock-btn[data-product-id="' + productId + '"]').data('stock', newStock);
                            } else if (type === 'variant') {
                                const $badge = $('.stock-display-variant-' + variantId + ' .stock-badge');
                                $badge.text(newStock);
                                $badge.removeClass('bg-gradient-success bg-gradient-secondary')
                                      .addClass(newStock > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary');
                                $('.stock-edit-variant-' + variantId).hide();
                                $('.stock-display-variant-' + variantId).show();
                                $('.edit-stock-btn[data-variant-id="' + variantId + '"]').data('stock', newStock);
                            }

                            Swal.fire({
                                title: 'Success',
                                text: 'Stock updated successfully!',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error updating stock. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join('\n');
                        }
                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error'
                        });
                        // Restore original value
                        if (type === 'simple') {
                            $input.val($('.edit-stock-btn[data-product-id="' + productId + '"]').data('stock'));
                        } else if (type === 'variant') {
                            $input.val($('.edit-stock-btn[data-variant-id="' + variantId + '"]').data('stock'));
                        }
                    }
                });
            }
        });
    </script>
@endpush

