@component('mail::message')

{{-- Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('storage/' . setting('app_logo')) }}" alt="{{ setting('app_name') }}" style="max-height: 80px;">
</div>

# Order Confirmation - #{{ $order->id }}

Hello **{{ $order->user->name ?? 'Customer' }}**,

Thank you for shopping with **{{ setting('app_name') }}** 🎉
Your order has been placed successfully and is now being processed.

---

### 🧾 Order Details:
@if($order->items && $order->items->count() > 0)
@component('mail::table')
| Product       | Quantity | Price   |
| ------------- |:--------:| -------:|
@foreach ($order->items as $item)
| {{ $item->product->name ?? 'Product Deleted' }} | {{ $item->quantity ?? 0 }} | {{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2) }} EGP |
@endforeach
@endcomponent
@else
<p>No items found in this order.</p>
@endif

**Total:** <span style="font-size:16px; font-weight:bold;">{{ number_format($order->final_total ?? 0, 2) }} EGP</span>

---

We will notify you once your order status changes.
You can track your order anytime from your account dashboard.

Thanks for choosing **{{ setting('app_name') }}** 💙
**{{ setting('app_name') }} Team**

@endcomponent
