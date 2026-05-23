@extends('dashboard.layouts.app')

@section('content')
    @php
        $branchStockValue = 0;
        if ($product->type === 'simple') {
            $branchStock = $product->branchProductStocks->where('branch_id', $branchId)->first();
            $branchStockValue = $branchStock ? $branchStock->quantity : 0;
        } else {
            $branchStockValue = $product->variants->sum(function ($variant) use ($branchId) {
                $variantStock = $variant->branchVariantStocks->where('branch_id', $branchId)->first();
                return $variantStock ? $variantStock->quantity : 0;
            });
        }
        $allowBranchAdminToEditStock = setting('allow_branch_admin_to_edit_stock');
        $allowBranchAdminToEditStock = $allowBranchAdminToEditStock == 1 ? true : false;
    @endphp
    <div class="container-fluid py-4">
        {{-- Quick Stats --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Price') }}</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ format_currency($product->manager()->price()) }}
                                </h5>
                            </div>
                            <div class="col-4 text-end align-content-center">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle align-content-center">
                                    <i class="fa fa-dollar-sign text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Branch Stock') }}</p>
                                <h5 class="font-weight-bolder mb-0 {{ $branchStockValue > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $branchStockValue }}
                                </h5>
                            </div>
                            <div class="col-4 text-end align-content-center">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle align-content-center">
                                    <i class="fa fa-boxes text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Product Type') }}</p>
                                <h5 class="font-weight-bolder mb-0">
                                    <span class="badge {{ $product->type === 'variable' ? 'bg-gradient-primary' : 'bg-gradient-info' }}">
                                        {{ ucfirst($product->type ?? 'simple') }}
                                    </span>
                                </h5>
                            </div>
                            <div class="col-4 text-end align-content-center">
                                <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle align-content-center">
                                    <i class="fa fa-tags text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Status') }}</p>
                                <h5 class="font-weight-bolder mb-0">
                                    @if($product->is_active)
                                        <span class="badge bg-gradient-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-gradient-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </h5>
                            </div>
                            <div class="col-4 text-end align-content-center">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle align-content-center">
                                    <i class="fa fa-toggle-on text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Product Details & Images --}}
        <div class="row">
            <div class="col-lg-8">

                @if($allowBranchAdminToEditStock)
                {{-- Branch Stock Editor --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fa fa-warehouse me-2"></i>{{ __('Branch Stock') }}</h6>
                        <span class="badge bg-gradient-primary">{{ __('Branch') }} #{{ $branchId }}</span>
                    </div>
                    <div class="card-body">
                        @if($product->type === 'simple')
                            @php
                                $currentBranchStock = $product->branchProductStocks->where('branch_id', $branchId)->first();
                                $currentBranchStockValue = $currentBranchStock ? $currentBranchStock->quantity : 0;
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-sm text-muted">{{ __('Current Stock') }}</span>
                                    <span class="badge {{ $currentBranchStockValue > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                        {{ $currentBranchStockValue }}
                                    </span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="toggle-simple-stock-form">
                                    <i class="fa fa-edit me-1"></i>{{ __('Edit Stock') }}
                                </button>
                            </div>
                            <form method="POST"
                                  action="{{ route('branch.products.updateBranchStocks', $product->id) }}"
                                  class="d-none"
                                  id="simple-stock-form">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-sm">{{ __('Update Stock') }}</label>
                                    <input type="number"
                                           name="branch_stocks[{{ $branchId }}]"
                                           class="form-control"
                                           min="0"
                                           value="{{ $currentBranchStockValue }}"
                                           placeholder="0">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-success flex-fill">
                                        <i class="fa fa-save me-1"></i>{{ __('Save') }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary flex-fill" id="cancel-simple-stock-form">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-sm text-muted">{{ __('Branch Variants') }}</span>
                                    <span class="badge bg-gradient-info">{{ $product->variants->count() }}</span>
                                </div>
                                <small class="text-muted text-xs">{{ __('Edit stocks inside variants table below') }}</small>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-info-circle me-2"></i>{{ __('Product Details') }}</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered mb-0">
                            <tr><th width="30%">{{ __('ID') }}</th><td>{{ $product->id }}</td></tr>
                            <tr><th>{{ __('Name (EN)') }}</th><td>{{ $product->getTranslation('name', 'en') }}</td></tr>
                            <tr><th>{{ __('Name (AR)') }}</th><td>{{ $product->getTranslation('name', 'ar') }}</td></tr>
                            <tr><th>{{ __('SKU') }}</th><td>{{ $product->sku }}</td></tr>
                            <tr>
                                <th>{{ __('Unit') }}</th>
                                <td>
                                    @if($product->unit)
                                        {{ is_array($product->unit->code) ? ($product->unit->code['en'] ?? '') : $product->unit->code }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr><th>{{ __('Created At') }}</th><td>{{ $product->created_at?->format('Y-m-d H:i:s') }}</td></tr>
                            <tr><th>{{ __('Updated At') }}</th><td>{{ $product->updated_at?->format('Y-m-d H:i:s') }}</td></tr>
                        </table>
                    </div>
                </div>

                {{-- Variants --}}
                @if($product->type === 'variable' && $product->variants->count())
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fa fa-layer-group me-2"></i>{{ __('Variants') }}</h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-gradient-primary">{{ $product->variants->count() }} {{ __('variants') }}</span>
                            @if($allowBranchAdminToEditStock)
                            <button type="button" class="btn btn-sm btn-outline-primary" id="toggle-variant-stock-edit">
                                <i class="fa fa-edit me-1"></i>{{ __('Edit Stock') }}
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($allowBranchAdminToEditStock)
                        <form method="POST" action="{{ route('branch.products.updateBranchStocks', $product->id) }}" id="variant-stock-form-merged">
                            @csrf
                        @endif
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Variant') }}</th>
                                        <th>{{ __('SKU') }}</th>
                                        <th class="text-center">{{ __('Price') }}</th>
                                        <th class="text-center">{{ __('Branch Stock') }}</th>
                                        @if($allowBranchAdminToEditStock)
                                            <th class="text-center variant-edit-col d-none">{{ __('Update Stock') }}</th>
                                        @endif
                                        <th class="text-center">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants as $variantIndex => $variant)
                                        @php
                                            $variantStock = $variant->branchVariantStocks->where('branch_id', $branchId)->first();
                                            $variantStockValue = $variantStock ? $variantStock->quantity : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-sm fw-bold">{{ $variant->getTranslation('name', 'en') }}</span>
                                                    @if($variant->getTranslation('name', 'ar'))
                                                        <small class="text-muted">{{ $variant->getTranslation('name', 'ar') }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-sm">{{ $variant->sku }}</td>
                                            <td class="text-center text-sm">{{ format_currency($variant->price) }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $variantStockValue > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                    {{ $variantStockValue }}
                                                </span>
                                            </td>
                                            @if($allowBranchAdminToEditStock)
                                                <td class="text-center variant-edit-col d-none">
                                                    <input type="hidden" name="product_variants[{{ $variantIndex }}][id]" value="{{ $variant->id }}">
                                                    <input type="number"
                                                           name="product_variants[{{ $variantIndex }}][branch_stocks][{{ $branchId }}]"
                                                           class="form-control form-control-sm"
                                                           min="0"
                                                           value="{{ $variantStockValue }}"
                                                           placeholder="0">
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                @if($variant->is_active)
                                                    <span class="badge bg-gradient-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-gradient-secondary">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($allowBranchAdminToEditStock)
                        <div class="d-flex gap-2 justify-content-end mt-3 variant-edit-actions d-none">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fa fa-save me-1"></i>{{ __('Save Changes') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" id="cancel-variant-stock-edit">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                        </form>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                {{-- Images --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-images me-2"></i>{{ __('Images') }}</h6>
                    </div>
                    <div class="card-body">
                        @if($product->images->count())
                            <div class="row g-3">
                                @foreach($product->images as $img)
                                    <div class="col-auto">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $img->path) }}"
                                                 class="rounded shadow"
                                                 style="height: 120px; width: 120px; object-fit: cover; cursor: pointer;"
                                                 alt="{{ $product->getTranslation('name','en') }}"
                                                 data-bs-toggle="modal"
                                                 data-bs-target="#productImageModal"
                                                 data-image-src="{{ asset('storage/' . $img->path) }}"
                                                 data-image-title="{{ $product->getTranslation('name','en') }} - {{ __('Image') }} {{ $loop->iteration }}">
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-dark opacity-75">{{ $loop->iteration }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">{{ __('No images') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Categories --}}
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-tags me-2"></i>{{ __('Categories') }}</h6>
                    </div>
                    <div class="card-body">
                        @if($product->categories->count())
                            @foreach($product->categories as $category)
                                <span class="badge bg-gradient-info me-1 mb-1">
                                    {{ $category->getTranslation('name','en') }}
                                </span>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">{{ __('No categories assigned') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Image Modal -->
    <div class="modal fade product-image-modal" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title mb-0" id="productImageModalLabel">{{ $product->getTranslation('name','en') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img id="productImageModalImg" src="" alt="{{ $product->getTranslation('name','en') }}" class="img-fluid rounded" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Product Image Modal Handler
        const productImageModal = document.getElementById('productImageModal');
        const productImageModalImg = document.getElementById('productImageModalImg');
        const productImageModalLabel = document.getElementById('productImageModalLabel');

        if (productImageModal) {
            productImageModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const imageSrc = button.getAttribute('data-image-src');
                const imageTitle = button.getAttribute('data-image-title');

                if (productImageModalImg && imageSrc) {
                    productImageModalImg.src = imageSrc;
                }
                if (productImageModalLabel && imageTitle) {
                    productImageModalLabel.textContent = imageTitle;
                }
            });
        }

        // Simple Product Stock Form Toggle
        const simpleForm = document.getElementById('simple-stock-form');
        const toggleSimpleBtn = document.getElementById('toggle-simple-stock-form');
        const cancelSimpleBtn = document.getElementById('cancel-simple-stock-form');

        if (simpleForm && toggleSimpleBtn && cancelSimpleBtn) {
            toggleSimpleBtn.addEventListener('click', () => {
                simpleForm.classList.toggle('d-none');
            });
            cancelSimpleBtn.addEventListener('click', () => {
                simpleForm.classList.add('d-none');
            });
        }

        // Variant Stock Form Toggle
        const variantFormMerged = document.getElementById('variant-stock-form-merged');
        const toggleVariantEdit = document.getElementById('toggle-variant-stock-edit');
        const cancelVariantEdit = document.getElementById('cancel-variant-stock-edit');
        const variantEditCols = document.querySelectorAll('.variant-edit-col');
        const variantEditActions = document.querySelector('.variant-edit-actions');

        if (variantFormMerged && toggleVariantEdit && cancelVariantEdit && variantEditActions) {
            const showVariantInputs = (show) => {
                variantEditCols.forEach(col => col.classList.toggle('d-none', !show));
                variantEditActions.classList.toggle('d-none', !show);
            };

            toggleVariantEdit.addEventListener('click', () => {
                const willShow = variantEditActions.classList.contains('d-none');
                showVariantInputs(willShow);
            });

            cancelVariantEdit.addEventListener('click', () => {
                showVariantInputs(false);
            });
        }
    });
</script>
@endpush

