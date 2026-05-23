@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Header Section with Actions --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-white">{{ $product->getTranslation('name', 'en') }}</h4>
                        <p class="text-white opacity-8 mb-0">{{ $product->sku }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.products.edit', $product->slug) }}" class="btn btn-sm btn-white">
                            <i class="fa fa-edit me-1"></i> Edit Product
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-white">
                            <i class="fa fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats Cards --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Price</p>
                                    <h5 class="font-weight-bolder mb-0 text-primary">
                                        {{ format_currency($product->manager()->price()) }}
                                    </h5>
                                    @if ($product->discount > 0)
                                        <small class="text-muted">
                                            <span
                                                class="text-success ms-1">-{{ $product->discount }}{{ $product->discount_type == 'percentage' ? '%' : '' }}</span>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 text-end align-content-center">
                                <div
                                    class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle align-content-center">
                                    <i class="fa fa-dollar-sign text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-100 col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Stock</p>
                                    <h5
                                        class="font-weight-bolder mb-0 {{ $product->manager()->stock() > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $product->manager()->stock() }}
                                    </h5>
                                    @if ($product->type == 'variable')
                                        <small class="text-muted">(across all variants)</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 text-end align-content-center">
                                <div
                                    class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle align-content-center">
                                    <i class="fa fa-boxes text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-100 col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Product Type</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <span
                                            class="badge {{ $product->type == 'variable' ? 'bg-gradient-primary' : 'bg-gradient-info' }} text-white">
                                            {{ ucfirst($product->type ?? 'simple') }}
                                        </span>
                                    </h5>
                                    @if ($product->type == 'variable')
                                        <small class="text-muted">{{ $product->variants->count() }} variants</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 text-end align-content-center">
                                <div
                                    class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle align-content-center">
                                    <i class="fa fa-tags text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-100 col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Status</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        @if ($product->is_active)
                                            <span class="badge bg-gradient-success">Active</span>
                                        @else
                                            <span class="badge bg-gradient-secondary">Inactive</span>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end align-content-center">
                                <div
                                    class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle align-content-center">
                                    <i class="fa fa-toggle-on text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Left Column --}}
            <div class="col-lg-8">
                {{-- Product Images Gallery --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fa fa-images me-2"></i>Product Images</h6>
                            <span class="badge bg-gradient-primary">{{ $product->images->count() }} images</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($product->images->count() > 0)
                            <div class="row g-3">
                                @foreach ($product->images as $img)
                                    <div class="col-auto">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $img->path) }}" alt="Product Image"
                                                class="rounded shadow-lg product-image-thumb"
                                                style="height: 180px;width: 180px; object-fit: cover; cursor: pointer;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#productImageModal"
                                                data-image-src="{{ asset('storage/' . $img->path) }}"
                                                data-image-title="Product Image {{ $loop->iteration }}">
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-dark opacity-75">{{ $loop->iteration }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fa fa-image fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No images uploaded for this product.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Product Details --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-0"><i class="fa fa-info-circle me-2"></i>Product Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fa fa-barcode text-primary me-2"></i>
                                        <strong>SKU:</strong>
                                    </div>
                                    <p class="ms-4 mb-0">{{ $product->sku }}</p>
                                </div>
                            </div>
                            @if ($product->unit)
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fa fa-ruler text-info me-2"></i>
                                            <strong>Unit:</strong>
                                        </div>
                                        <p class="ms-4 mb-0">
                                            {{ $product->unit->getTranslation('name', 'en') }}
                                            <small class="text-muted">
                                                ({{ is_array($product->unit->code) ? $product->unit->code['en'] ?? '' : $product->unit->code }})
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fa fa-percent text-warning me-2"></i>
                                        <strong>Discount:</strong>
                                    </div>
                                    <p class="ms-4 mb-0">
                                        @if ($product->discount > 0)
                                            <span class="badge bg-gradient-warning text-dark">
                                                {{ $product->discount }}{{ $product->discount_type == 'percentage' ? '%' : ' (Fixed)' }}
                                            </span>
                                        @else
                                            <span class="text-muted">No discount</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fa fa-shopping-cart text-success me-2"></i>
                                        <strong>Max Order Quantity:</strong>
                                    </div>
                                    <p class="ms-4 mb-0">
                                        {{ $product->max_order_quantity ?? 'No Limit' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa fa-tags text-primary me-2"></i>
                                <strong>Categories:</strong>
                            </div>
                            <div class="ms-4">
                                @if ($product->categories->count() > 0)
                                    @foreach ($product->categories as $category)
                                        <span class="badge bg-gradient-info me-1 mb-1">
                                            {{ $category->getTranslation('name', 'en') }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No categories assigned</span>
                                @endif
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa fa-align-left text-info me-2"></i>
                                <strong>Short Description:</strong>
                            </div>
                            <div class="ms-4">
                                {!! $product->short_description
                                    ? $product->short_description
                                    : '<span class="text-muted">No short description</span>' !!}
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa fa-file-text text-primary me-2"></i>
                                <strong>Description:</strong>
                            </div>
                            <div class="ms-4">
                                {!! $product->description ? $product->description : '<span class="text-muted">No description</span>' !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Variants --}}
                @if ($product->type == 'variable')
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fa fa-layer-group me-2"></i>Product Variants</h6>
                                <span class="badge bg-gradient-primary">{{ $product->variants->count() }} variants</span>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($product->variants && $product->variants->count() > 0)
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Variant</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    SKU</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Price</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Stock</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Status</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Options</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Images</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->variants as $index => $variant)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div>
                                                                <h6 class="mb-0 text-sm">
                                                                    {{ $variant->getTranslation('name', 'en') }}</h6>
                                                                @if ($variant->getTranslation('name', 'ar'))
                                                                    <p class="text-xs text-secondary mb-0">
                                                                        {{ $variant->getTranslation('name', 'ar') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ $variant->sku }}</p>
                                                    </td>
                                                    <td class="align-middle text-center text-sm">
                                                        <span
                                                            class="text-secondary text-xs font-weight-bold">{{ format_currency($variant->price) }}</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        @php
                                                            $variantBranchStockTotal = $variant->branchVariantStocks->sum(
                                                                'quantity',
                                                            );
                                                            $variantStock =
                                                                $variantBranchStockTotal > 0
                                                                    ? $variantBranchStockTotal
                                                                    : $variant->stock ?? 0;
                                                        @endphp
                                                        <span
                                                            class="badge badge-sm {{ $variantStock > 0 ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                                            {{ $variantStock }}
                                                        </span>
                                                        @if ($variantBranchStockTotal > 0)
                                                            <br><small class="text-xs text-muted">(branches)</small>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-center text-sm">
                                                        @if ($variant->is_active)
                                                            <span class="badge badge-sm bg-gradient-success">Active</span>
                                                        @else
                                                            <span
                                                                class="badge badge-sm bg-gradient-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        @if ($variant->values->count() > 0)
                                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                                @foreach ($variant->values as $value)
                                                                    @if ($value->variantOption)
                                                                        <span class="badge badge-sm bg-gradient-info">
                                                                            {{ $value->variantOption->getTranslation('name', 'en') }}
                                                                            @if ($value->variantOption->code)
                                                                                ({{ $value->variantOption->code }})
                                                                            @endif
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="badge badge-sm bg-gradient-secondary">{{ $value->value }}</span>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-xs text-muted">No options</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        @if ($variant->images->count() > 0)
                                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                                @foreach ($variant->images->take(3) as $img)
                                                                    <img src="{{ asset('storage/' . $img->path) }}"
                                                                        alt="Variant Image" class="rounded border"
                                                                        style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#variantImageModal{{ $img->id }}">
                                                                    <!-- Image Modal -->
                                                                    <div class="modal fade"
                                                                        id="variantImageModal{{ $img->id }}"
                                                                        tabindex="-1">
                                                                        <div
                                                                            class="modal-dialog modal-lg modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Variant Image
                                                                                    </h5>
                                                                                    <button type="button"
                                                                                        class="btn-close"
                                                                                        data-bs-dismiss="modal"></button>
                                                                                </div>
                                                                                <div class="modal-body text-center p-0">
                                                                                    <img src="{{ asset('storage/' . $img->path) }}"
                                                                                        alt="Variant Image"
                                                                                        class="img-fluid">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                @if ($variant->images->count() > 3)
                                                                    <span
                                                                        class="badge badge-sm bg-gradient-primary">+{{ $variant->images->count() - 3 }}</span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-xs text-muted">No images</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fa fa-box-open fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No variants available for this product.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Attributes --}}
                @if ($product->attributes->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6 class="mb-0"><i class="fa fa-list-ul me-2"></i>Product Attributes</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Attribute</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->attributes as $attribute)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <h6 class="mb-0 text-sm">
                                                                {{ $attribute->getTranslation('name', 'en') }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        @if ($attribute->pivot->attribute_option_id)
                                                            <span class="badge bg-gradient-info">
                                                                {{ $attribute->options->where('id', $attribute->pivot->attribute_option_id)->first()?->value }}
                                                            </span>
                                                        @else
                                                            {{ $attribute->pivot->value ?? '—' }}
                                                        @endif
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column --}}
            <div class="col-lg-4">
                {{-- Product Settings --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-0"><i class="fa fa-cog me-2"></i>Product Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-box text-primary me-2"></i>
                                <span class="text-sm">Stock Management</span>
                            </div>
                            {!! $product->manage_stock
                                ? '<span class="badge bg-gradient-success">Enabled</span>'
                                : '<span class="badge bg-gradient-secondary">Disabled</span>' !!}
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-toggle-on text-success me-2"></i>
                                <span class="text-sm">Active Status</span>
                            </div>
                            {!! $product->is_active
                                ? '<span class="badge bg-gradient-success">Active</span>'
                                : '<span class="badge bg-gradient-danger">Inactive</span>' !!}
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-star text-warning me-2"></i>
                                <span class="text-sm">Featured</span>
                            </div>
                            {!! $product->is_featured
                                ? '<span class="badge bg-gradient-warning text-dark">Yes</span>'
                                : '<span class="badge bg-gradient-secondary">No</span>' !!}
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-sparkles text-info me-2"></i>
                                <span class="text-sm">New Product</span>
                            </div>
                            {!! $product->is_new
                                ? '<span class="badge bg-gradient-info">Yes</span>'
                                : '<span class="badge bg-gradient-secondary">No</span>' !!}
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-calendar-check text-primary me-2"></i>
                                <span class="text-sm">Bookable</span>
                            </div>
                            {!! $product->is_bookable
                                ? '<span class="badge bg-gradient-primary">Yes</span>'
                                : '<span class="badge bg-gradient-secondary">No</span>' !!}
                        </div>
                    </div>
                </div>

                {{-- Branch Stock Management --}}
                @if ($product->type == 'simple')
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fa fa-warehouse me-2"></i>Branch Stocks</h6>
                            <button type="button" class="btn btn-sm btn-primary mb-0" id="edit-branch-stocks-btn">
                                <i class="fa fa-edit me-1"></i> Edit
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="branch-stocks-display">
                                @foreach ($branches as $branch)
                                    @php
                                        $branchStock = $product->branchProductStocks
                                            ->where('branch_id', $branch->id)
                                            ->first();
                                        $stockValue = $branchStock ? $branchStock->quantity : 0;
                                    @endphp
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div>
                                            <h6 class="text-sm mb-0">{{ $branch->getTranslation('name', 'en') }}</h6>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $branch->getTranslation('name', 'ar') }}</p>
                                        </div>
                                        <div class="text-end">
                                            <span
                                                class="badge badge-lg {{ $stockValue > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                {{ $stockValue }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div id="branch-stocks-edit" style="display: none;">
                                <form id="update-branch-stocks-form">
                                    @csrf
                                    @foreach ($branches as $branch)
                                        @php
                                            $branchStock = $product->branchProductStocks
                                                ->where('branch_id', $branch->id)
                                                ->first();
                                            $stockValue = $branchStock ? $branchStock->quantity : 0;
                                        @endphp
                                        <div class="mb-3">
                                            <label
                                                class="text-sm font-weight-bold">{{ $branch->getTranslation('name', 'en') }}</label>
                                            <input type="number" name="branch_stocks[{{ $branch->id }}]"
                                                class="form-control form-control-sm" min="0" placeholder="0"
                                                value="{{ $stockValue }}">
                                        </div>
                                    @endforeach
                                    <div class="d-flex gap-2 mt-3">
                                        <button type="submit" class="btn btn-sm btn-success flex-fill">
                                            <i class="fa fa-save me-1"></i> Save
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary flex-fill"
                                            id="cancel-edit-branch-stocks">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Variable Product Branch Stock Management --}}
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fa fa-warehouse me-2"></i>Branch Stocks</h6>
                            <button type="button" class="btn btn-sm btn-primary mb-0"
                                id="edit-variant-branch-stocks-btn">
                                <i class="fa fa-edit me-1"></i> Edit
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="variant-branch-stocks-display">
                                @if ($product->variants && $product->variants->count() > 0)
                                    @foreach ($product->variants as $variant)
                                        <div class="mb-4 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                            <h6 class="text-sm font-weight-bold mb-2">
                                                {{ $variant->getTranslation('name', 'en') }}</h6>
                                            <small class="text-xs text-secondary d-block mb-2">{{ $variant->sku }}</small>
                                            @foreach ($branches as $branch)
                                                @php
                                                    $variantBranchStock = $variant->branchVariantStocks
                                                        ->where('branch_id', $branch->id)
                                                        ->first();
                                                    $stockValue = $variantBranchStock
                                                        ? $variantBranchStock->quantity
                                                        : 0;
                                                @endphp
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span
                                                        class="text-xs">{{ $branch->getTranslation('name', 'en') }}</span>
                                                    <span
                                                        class="badge badge-sm {{ $stockValue > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                        {{ $stockValue }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-xs text-muted text-center">No variants available.</p>
                                @endif
                            </div>

                            <div id="variant-branch-stocks-edit" style="display: none;">
                                <form id="update-variant-branch-stocks-form">
                                    @csrf
                                    @if ($product->variants && $product->variants->count() > 0)
                                        @foreach ($product->variants as $variantIndex => $variant)
                                            <div class="mb-4 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                                <h6 class="text-sm font-weight-bold mb-2">
                                                    {{ $variant->getTranslation('name', 'en') }}</h6>
                                                <input type="hidden" name="product_variants[{{ $variantIndex }}][id]"
                                                    value="{{ $variant->id }}">
                                                {{-- Branch stocks inputs --}}
                                                @foreach ($branches as $branch)
                                                    @php
                                                        $variantBranchStock = $variant->branchVariantStocks
                                                            ->where('branch_id', $branch->id)
                                                            ->first();
                                                        $stockValue = $variantBranchStock
                                                            ? $variantBranchStock->quantity
                                                            : 0;
                                                    @endphp
                                                    <div class="mb-2">
                                                        <label
                                                            class="text-xs font-weight-bold">{{ $branch->getTranslation('name', 'en') }}</label>
                                                        <input type="number"
                                                            name="product_variants[{{ $variantIndex }}][branch_stocks][{{ $branch->id }}]"
                                                            class="form-control form-control-sm" min="0"
                                                            placeholder="0" value="{{ $stockValue }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="d-flex gap-2 mt-3">
                                        <button type="submit" class="btn btn-sm btn-success flex-fill">
                                            <i class="fa fa-save me-1"></i> Save
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary flex-fill"
                                            id="cancel-edit-variant-branch-stocks">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Related Products --}}
                @if ($product->relatedProducts->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6 class="mb-0"><i class="fa fa-link me-2"></i>Related Products</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($product->relatedProducts as $related)
                                @php
                                    $relatedProduct = $related->relatedProduct;
                                @endphp
                                @if ($relatedProduct)
                                <div
                                    class="d-flex align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                    @if ($relatedProduct->images()->first())
                                        <img src="{{ asset('storage/' . $relatedProduct->images()->first()->path) }}"
                                            class="rounded me-3" style="width:50px; height:50px; object-fit:cover;">
                                    @else
                                        <div class="bg-gradient-secondary rounded me-3 d-flex align-items-center justify-content-center"
                                            style="width:50px; height:50px;">
                                            <i class="fa fa-image text-white"></i>
                                        </div>
                                    @endif
                                    <div class="flex-fill">
                                        <h6 class="text-sm mb-0">{{ $relatedProduct->getTranslation('name', 'en') }}</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $relatedProduct->sku }}</p>
                                        <p class="text-xs font-weight-bold text-primary mb-0 mt-1">
                                            @if ($relatedProduct->type == 'variable')
                                                @php
                                                    $minPrice = $relatedProduct->variants()->min('price');
                                                    $maxPrice = $relatedProduct->variants()->max('price');
                                                @endphp
                                                @if ($minPrice == $maxPrice)
                                                    {{ format_currency($minPrice) }}
                                                @else
                                                    {{ format_currency($minPrice) }} - {{ format_currency($maxPrice) }}
                                                @endif
                                            @else
                                                {{ format_currency($relatedProduct->price) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Cross Sell Products --}}
                @if ($product->crossSellProducts->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6 class="mb-0"><i class="fa fa-shopping-bag me-2"></i>Cross Sell Products</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($product->crossSellProducts as $cross)
                                @php
                                    $crossProduct = $cross->relatedProduct;
                                @endphp
                                @if ($crossProduct)
                                <div
                                    class="d-flex align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                    @if ($crossProduct->images()->first())
                                        <img src="{{ asset('storage/' . $crossProduct->images()->first()->path) }}"
                                            class="rounded me-3" style="width:50px; height:50px; object-fit:cover;">
                                    @else
                                        <div class="bg-gradient-secondary rounded me-3 d-flex align-items-center justify-content-center"
                                            style="width:50px; height:50px;">
                                            <i class="fa fa-image text-white"></i>
                                        </div>
                                    @endif
                                    <div class="flex-fill">
                                        <h6 class="text-sm mb-0">{{ $crossProduct->getTranslation('name', 'en') }}</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $crossProduct->sku }}</p>
                                        <p class="text-xs font-weight-bold text-primary mb-0 mt-1">
                                            @if ($crossProduct->type == 'variable')
                                                @php
                                                    $minPrice = $crossProduct->variants()->min('price');
                                                    $maxPrice = $crossProduct->variants()->max('price');
                                                @endphp
                                                @if ($minPrice == $maxPrice)
                                                    {{ format_currency($minPrice) }}
                                                @else
                                                    {{ format_currency($minPrice) }} - {{ format_currency($maxPrice) }}
                                                @endif
                                            @else
                                                {{ format_currency($crossProduct->price) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- SEO Information --}}
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-0"><i class="fa fa-search me-2"></i>SEO Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-secondary">Meta Title</label>
                            <p class="text-sm mb-0">{{ $product->getTranslation('meta_title', 'en') ?: '—' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-secondary">Meta Description</label>
                            <p class="text-sm mb-0">{{ $product->getTranslation('meta_description', 'en') ?: '—' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-weight-bold text-uppercase text-secondary">Meta Keywords</label>
                            <p class="text-sm mb-0">{{ $product->getTranslation('meta_keywords', 'en') ?: '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shared Product Image Modal -->
    <div class="modal fade product-image-modal" id="productImageModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title mb-0">Product Image</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img src="" alt="Product Image" class="img-fluid rounded"
                        style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Simple Product Branch Stock Management
            $('#edit-branch-stocks-btn').on('click', function() {
                $('#branch-stocks-display').hide();
                $('#branch-stocks-edit').show();
                $(this).hide();
            });

            $('#cancel-edit-branch-stocks').on('click', function() {
                $('#branch-stocks-edit').hide();
                $('#branch-stocks-display').show();
                $('#edit-branch-stocks-btn').show();
            });

            $('#update-branch-stocks-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('admin.products.updateBranchStocks', $product->id) }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error updating branch stocks. Please try again.',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error updating branch stocks. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Handle validation errors
                            let errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join('\n');
                        }
                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            });

            // Variable Product Branch Stock Management
            $('#edit-variant-branch-stocks-btn').on('click', function() {
                $('#variant-branch-stocks-display').hide();
                $('#variant-branch-stocks-edit').show();
                $(this).hide();
            });

            $('#cancel-edit-variant-branch-stocks').on('click', function() {
                $('#variant-branch-stocks-edit').hide();
                $('#variant-branch-stocks-display').show();
                $('#edit-variant-branch-stocks-btn').show();
            });

            $('#update-variant-branch-stocks-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('admin.products.updateBranchStocks', $product->id) }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error updating variant branch stocks. Please try again.',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage =
                            'Error updating variant branch stocks. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Handle validation errors
                            let errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join('\n');
                        }
                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            });

            // Product image modal (single instance)
            $(document).on('click', '.product-image-thumb', function() {
                const src = $(this).data('image-src');
                const title = $(this).data('image-title') || 'Product Image';
                $('#productImageModal .modal-title').text(title);
                $('#productImageModal img').attr('src', src);
            });
        });
    </script>
@endpush
