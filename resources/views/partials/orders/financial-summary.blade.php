@php
    $couponDiscount = (float) ($order->coupon_discount_value ?? 0);
    $offerDiscount = (float) ($order->offer_discount_value ?? 0);
    $pointsDiscount = (float) ($order->points_discount_value ?? 0);
    $shippingCost = (float) ($order->shipping_cost ?? 0);
    $serviceFee = (float) ($order->service_fee ?? 0);
    $subtotal = (float) ($order->total ?? 0);
    $grandTotal = (float) ($order->final_total ?? 0);
    $totalDiscounts = $couponDiscount + $offerDiscount + $pointsDiscount;
    $subtotalAfterDiscounts = max(0, $subtotal - $totalDiscounts);
@endphp

<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fa-solid fa-calculator me-2"></i>{{ __('Order Summary') }}</span>
    </div>
    <div class="card-body p-0">
        <table class="table table-borderless mb-0">
            <tbody>
                <tr>
                    <td class="ps-4 text-muted">{{ __('Subtotal') }}</td>
                    <td class="pe-4 text-end fw-semibold">{{ format_currency($subtotal) }}</td>
                </tr>

                @if ($couponDiscount > 0 || $order->coupon_id)
                    <tr class="border-top">
                        <td class="ps-4">
                            <span class="text-success">{{ __('Coupon Discount') }}</span>
                            @if ($order->coupon)
                                <br>
                                <small class="text-muted">
                                    <span class="badge bg-light text-dark border">{{ $order->coupon->code }}</span>
                                    @if ($order->coupon->type === 'percentage')
                                        ({{ rtrim(rtrim(number_format((float) $order->coupon->coupon_discount_value, 2), '0'), '.') }}%)
                                    @else
                                        ({{ __('Fixed') }})
                                    @endif
                                </small>
                            @endif
                        </td>
                        <td class="pe-4 text-end text-success fw-semibold">
                            − {{ format_currency($couponDiscount) }}
                        </td>
                    </tr>
                @endif

                @if ($offerDiscount > 0 || $order->offer_id)
                    <tr class="{{ ! ($couponDiscount > 0 || $order->coupon_id) ? 'border-top' : '' }}">
                        <td class="ps-4">
                            <span class="text-success">{{ __('Offer Discount') }}</span>
                            @if ($order->offer)
                                <br>
                                <small class="text-muted">
                                    <span class="badge bg-light text-dark border">{{ $order->offer->title }}</span>
                                    @if ($order->offer->discount_type === 'percentage')
                                        ({{ rtrim(rtrim(number_format((float) $order->offer->discount_value, 2), '0'), '.') }}%)
                                    @elseif ($order->offer->discount_type === 'fixed')
                                        ({{ __('Fixed') }})
                                    @elseif ($order->offer->type)
                                        ({{ ucfirst(str_replace('_', ' ', $order->offer->type)) }})
                                    @endif
                                </small>
                            @endif
                        </td>
                        <td class="pe-4 text-end text-success fw-semibold">
                            − {{ format_currency($offerDiscount) }}
                        </td>
                    </tr>
                @endif

                @if ($pointsDiscount > 0)
                    <tr>
                        <td class="ps-4 text-success">{{ __('Points Discount') }}</td>
                        <td class="pe-4 text-end text-success fw-semibold">
                            − {{ format_currency($pointsDiscount) }}
                        </td>
                    </tr>
                @endif

                @if ($totalDiscounts > 0)
                    <tr class="border-top bg-light">
                        <td class="ps-4 fw-semibold">{{ __('Subtotal After Discounts') }}</td>
                        <td class="pe-4 text-end fw-semibold">{{ format_currency($subtotalAfterDiscounts) }}</td>
                    </tr>
                @endif

                <tr class="border-top">
                    <td class="ps-4 text-muted">{{ __('Shipping') }}</td>
                    <td class="pe-4 text-end">{{ format_currency($shippingCost) }}</td>
                </tr>

                <tr>
                    <td class="ps-4 text-muted">{{ __('Service Fee') }}</td>
                    <td class="pe-4 text-end">{{ format_currency($serviceFee) }}</td>
                </tr>

                <tr class="border-top bg-primary bg-opacity-10">
                    <td class="ps-4 py-3">
                        <h5 class="mb-0 fw-bold">{{ __('Grand Total') }}</h5>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <h5 class="mb-0 fw-bold text-primary">{{ format_currency($grandTotal) }}</h5>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
