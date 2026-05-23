@component('mail::message')

{{-- Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('storage/' . setting('app_logo')) }}" alt="{{ setting('app_name') }}" style="max-height: 80px;">
</div>

# Order Status Update - #{{ $order->id }}

Hello **{{ $order->user->name ?? 'Customer' }}**,

We wanted to let you know that your order status has been updated:

@component('mail::panel')
**{{ ucfirst($order->status) }}**
@endcomponent

---

### 🧾 Order Summary:
- **Total:** {{ number_format($order->final_total ?? 0, 2) }} EGP
- **Payment Method:** {{ $order->payment_method ?? 'N/A' }}
- **Placed On:** {{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A' }}

---

@if($order->uuid ?? null)
@component('mail::button', ['url' => url('/admin/orders/' . $order->uuid)])
🔍 View Order
@endcomponent
@endif

Thanks for choosing **{{ setting('app_name') }}** 💙
**{{ setting('app_name') }} Team**

@endcomponent
