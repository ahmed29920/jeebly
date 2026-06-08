<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin: 10px 0 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th,
        td {
            padding: 8px 10px;
            vertical-align: top;
        }

        .items-table th {
            background-color: #e9effc;
            color: #4b33a3;
            text-align: left;
        }

        .items-table td {
            border-bottom: 1px solid #e9effc;
        }

        .address-table th {
            background-color: #e9effc;
            color: #4b33a3;
            text-align: left;
        }

        .summary-table {
            width: 45%;
            margin-left: auto;
            background-color: #e9effc;
        }

        .summary-table td {
            padding: 8px 12px;
        }

        .summary-table tr:last-child td {
            font-weight: bold;
            border-top: 1px solid #fff;
        }

        hr {
            border: none;
            border-top: 1px solid #e9effc;
            margin: 16px 0;
        }

        .logo {
            height: 50px;
            width: auto;
        }
    </style>
</head>

<body>
    @php
        $logoSetting = setting('app_logo');
        $logoPath = $logoSetting ? storage_path('app/public/' . ltrim($logoSetting, '/')) : null;
    @endphp

    @if ($logoPath && file_exists($logoPath))
        <div class="text-center">
            <img class="logo" alt="Logo"
                src="data:{{ mime_content_type($logoPath) }};base64,{{ base64_encode(file_get_contents($logoPath)) }}">
        </div>
    @endif

    <div class="text-center title">Invoice</div>

    <table>
        <tr>
            <td><strong>Invoice ID:</strong> #{{ $order->id }}</td>
            <td class="text-right"><strong>Order Date:</strong> {{ $order->created_at->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td><strong>Customer Name:</strong> {{ $order->user->name }}</td>
            <td class="text-right"><strong>Customer Phone:</strong> {{ $order->user->phone }}</td>
        </tr>
    </table>

    @if ($order->billingAddress || $order->shippingAddress || $order->branch)
        <table class="address-table">
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
                            <strong>{{ arabic_pdf($order->billingAddress->name) }}</strong><br>
                            {{ arabic_pdf($order->billingAddress->address) }}<br>
                            {{ arabic_pdf($order->billingAddress->city) }}, {{ arabic_pdf($order->billingAddress->state) }}<br>
                            {{ $order->billingAddress->phone }}
                        </td>
                    @endif
                    @if ($order->shippingAddress)
                        <td>
                            <strong>{{ arabic_pdf($order->shippingAddress->name) }}</strong><br>
                            {{ arabic_pdf($order->shippingAddress->address) }}<br>
                            {{ arabic_pdf($order->shippingAddress->city) }}, {{ arabic_pdf($order->shippingAddress->state) }}<br>
                            {{ $order->shippingAddress->phone }}
                        </td>
                    @endif
                    @if ($order->branch)
                        <td>
                            <strong>{{ arabic_pdf($order->branch->name) }}</strong><br>
                            @if ($order->branch->address ?? false)
                                {{ arabic_pdf($order->branch->address) }}
                            @endif
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>
    @endif

    <hr>

    <table class="items-table">
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
                    $productName = $item->product?->getTranslation('name', app()->getLocale())
                        ?? ($item->product?->getTranslation('name', 'en') ?? ($item->product?->name ?? 'Product'));
                    $variantName = $item->variant?->getTranslation('name', app()->getLocale())
                        ?? $item->variant?->getTranslation('name', 'en')
                        ?? $item->variant?->name;
                    $unitPrice = $item->price ?? ($item->variant?->price ?? ($item->product?->manager()?->price() ?? 0));
                    $freeQty = $item->free_quantity ?? 0;
                    $subtotal = $unitPrice * ($item->quantity ?? 0);
                @endphp
                <tr>
                    <td>{{ $sku }}</td>
                    <td>
                        {{ arabic_pdf($productName) }}
                        @if ($variantName)
                            - {{ arabic_pdf($variantName) }}
                        @endif
                    </td>
                    <td>{{ format_currency($unitPrice) }}</td>
                    <td>
                        {{ $item->quantity ?? 0 }}
                        @if ($freeQty > 0)
                            + {{ $freeQty }} (free)
                        @endif
                    </td>
                    <td>{{ format_currency($subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table class="summary-table">
        <tbody>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ format_currency($order->total) }}</td>
            </tr>
            @if ($order->coupon_discount_value > 0)
                <tr>
                    <td>Discount</td>
                    <td class="text-right">{{ format_currency($order->coupon_discount_value) }}</td>
                </tr>
            @endif
            @if ($order->offer_discount_value > 0)
                <tr>
                    <td>Offer Discount</td>
                    <td class="text-right">{{ format_currency($order->offer_discount_value) }}</td>
                </tr>
            @endif
            @if ($order->points_discount_value > 0)
                <tr>
                    <td>Points Discount</td>
                    <td class="text-right">{{ format_currency($order->points_discount_value) }}</td>
                </tr>
            @endif
            <tr>
                <td>Shipping</td>
                <td class="text-right">{{ format_currency($order->shipping_cost) }}</td>
            </tr>
            @if (($order->service_fee ?? 0) > 0)
                <tr>
                    <td>Service Fee</td>
                    <td class="text-right">{{ format_currency($order->service_fee) }}</td>
                </tr>
            @endif
            <tr>
                <td>Grand Total</td>
                <td class="text-right">{{ format_currency($order->final_total) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
