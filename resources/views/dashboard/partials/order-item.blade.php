@php
    $product = $item->product;
    $variant = $item->variant;
    $productImage = $product?->images()?->first();
@endphp

<div class="d-flex align-items-center mb-3">
    @if ($productImage)
        <img src="{{ asset('storage/' . $productImage->path) }}" class="img-thumbnail me-3"
            style="width: 80px; height: 80px; object-fit: cover;">
    @else
        <div class="img-thumbnail me-3 d-flex align-items-center justify-content-center bg-light"
            style="width: 80px; height: 80px;">
            <i class="fa fa-image text-muted"></i>
        </div>
    @endif
    <div>
        @if ($product)
            <h6>
                {{ $product->getTranslation('name', 'en') }}
                @if ($product->trashed())
                    <span class="badge bg-secondary">{{ __('Deleted') }}</span>
                @endif
            </h6>
            <p>
                {{ format_currency($variant ? $variant->price : $product->price) }}
                x {{ $item->quantity }}
                @if ($item->free_quantity)
                    & ({{ $item->free_quantity }} free)
                @endif
            </p>
            <small>
                SKU: {{ $variant ? $variant->sku : $product->sku }}
                @if ($variant)
                    - {{ $variant->getTranslation('name', 'en') }}
                    @if ($variant->trashed())
                        <span class="badge bg-secondary">{{ __('Deleted') }}</span>
                    @endif
                @endif
            </small>
            <br>
            @if ($product->unit)
                <small class="text-muted">
                    Unit:
                    {{ is_array($product->unit->code) ? $product->unit->code['en'] ?? '' : $product->unit->code }}
                </small>
            @endif
        @else
            <h6>{{ __('Deleted product #:id', ['id' => $item->product_id]) }}</h6>
            <p>
                {{ format_currency($item->price) }} x {{ $item->quantity }}
                @if ($item->free_quantity)
                    & ({{ $item->free_quantity }} free)
                @endif
            </p>
        @endif
    </div>
</div>
