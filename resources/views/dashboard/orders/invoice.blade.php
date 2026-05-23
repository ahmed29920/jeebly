<!DOCTYPE html>
<html>

<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Cairo, DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
        }

    /* Improve Arabic rendering in Dompdf */
    .rtl {
        direction: rtl;
        text-align: right;
        unicode-bidi: embed;
    }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 2px solid #333;
        }

        .header img {
            max-height: 60px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .customer-info,
        .order-info {
            margin: 20px;
        }

        .customer-info p,
        .order-info p {
            margin: 4px 0;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #fff;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        td {
            border: none
        }

        th {
            background-color: #e9effc;
            color: #4b33a3;
            border-color: #fff
        }

        .total {
            text-align: right;
            margin: 20px;
            font-size: 18px;
        }

        .page-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        hr {
            border: 1px solid #e9effc;
        }
    </style>
</head>

<body>
    <!-- Logo -->
    <div class="logo-container ">
        @if (setting('app_logo'))
            <img width="120"
                src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('storage/' . setting('app_logo')))) }}" />
        @endif
    </div>

    <!-- Invoice Header -->
    <div class="page-header">
        <b>Invoice</b>
    </div>

    <!-- Invoice Information -->
    <table style="border: none">
        <tbody style="border: none">
            <tr style="border: none">
                <td style="border: none">
                    <b>Invoice ID:</b> #{{ $order->id }}
                </td>
                <td style="text-align: right;border:none">
                    <b>Order Date:</b> {{ $order->created_at->format('d-m-Y') }}
                </td>
            </tr>
            <tr style="border: none">
                <td style="border: none">
                    <b>Customer Name:</b> {{ $order->user->name }}
                </td>
                <td style="text-align: right;border:none">
                    <b>Customer Phone:</b> {{ $order->user->phone }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Billing & Shipping -->
    <table>
        <thead>
            <tr>
                @if ($order->billingAddress)
                    <th>Bill To</th>
                @endif
                @if ($order->shippingAddress)
                    <th>Ship To</th>
                @endif
                @if ($order->branch)
                    <th>Branch</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <tr>
                @if ($order->billingAddress)
                    <td>
                        <div ><strong>{{ arabic_pdf($order->billingAddress->name) }}</strong></div>
                        <div >{{ arabic_pdf($order->billingAddress->address) }}</div>
                        <div >{{ arabic_pdf($order->billingAddress->city) }}, {{ arabic_pdf($order->billingAddress->state) }}</div>
                        <div>{{ $order->billingAddress->phone }}</div>
                    </td>
                @endif
                @if ($order->shippingAddress)
                    <td>
                        <div "><strong>{{ arabic_pdf($order->shippingAddress->name) }}</strong></div>
                        <div >{{ arabic_pdf($order->shippingAddress->address) }}</div>
                        <div >{{ arabic_pdf($order->shippingAddress->city) }}, {{ arabic_pdf($order->shippingAddress->state) }}</div>
                        <div>{{ $order->shippingAddress->phone }}</div>
                    </td>
                @endif
                @if ($order->branch)

                    <td>
                        <div ><strong>{{ arabic_pdf($order->branch->name) }}</strong></div>
                        @if ($order->branch->address ?? false)
                            <div >{{ arabic_pdf($order->branch->address) }}</div>
                        @endif
                    </td>

                @endif
            </tr>

        </tbody>
    </table>
    <hr>
    <!-- Items -->
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                @php
                    $sku = $item->variant?->sku ?? ($item->product?->sku ?? '-');
                    $productName =
                        $item->product?->getTranslation('name', 'en') ?? ($item->product?->name ?? __('Product'));
                    $variantName = $item->variant?->getTranslation('name', 'en') ?? $item->variant?->name;
                    $unitPrice =
                        $item->price ?? ($item->variant?->price ?? ($item->product?->manager()?->price() ?? 0));
                    $freeQty = $item->free_quantity ?? 0;
                    $subtotal = $unitPrice * ($item->quantity ?? 0);
                @endphp
                <tr>
                    <td>{{ $sku }}</td>
                    <td>
                        {{ $productName }}
                        @if ($variantName)
                            - {{ $variantName }}
                        @endif
                    </td>
                    <td>{{ format_currency($unitPrice) }}</td>
                    <td>
                        {{ $item->quantity ?? 0 }}
                        @if ($freeQty > 0)
                            & {{ $freeQty }} (free)
                        @endif
                    </td>
                    <td>{{ format_currency($subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <!-- Totals -->
    <div class="summary">
        <table style="background-color: #e9effc">
            <tbody>
                <tr>
                    <td>Subtotal</td>
                    <td>-</td>
                    <td>{{ format_currency($order->total) }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td>-</td>
                    <td>{{ format_currency($order->coupon_discount_value) }}</td>
                </tr>
                <tr>
                    <td>Shipping</td>
                    <td>-</td>
                    <td>{{ format_currency($order->shipping_cost) }}</td>
                </tr>
                <tr style="border-top: 1px solid #fff">
                    <td>Grand Total</td>
                    <td>-</td>
                    <td>{{ format_currency($order->final_total) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>
