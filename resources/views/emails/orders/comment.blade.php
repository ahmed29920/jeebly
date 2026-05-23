@component('mail::message')

{{-- Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('storage/' . setting('app_logo')) }}" alt="{{ setting('app_name') }}" style="max-height: 80px;">
</div>

# Order Update - #{{ $order->id }}

Hello **{{ $order->user->name ?? 'Customer' }}**,

A new comment has been added to your order by our team:

@component('mail::panel')
{{ $comment->comment ?? 'No comment' }}
@endcomponent

---

### 🧾 Order Summary:
- **Status:** {{ ucfirst($order->status ?? 'pending') }}
- **Total:** {{ number_format($order->final_total ?? 0, 2) }} EGP
- **Payment Method:** {{ $order->payment_method ?? 'N/A' }}
- **Placed On:** {{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A' }}

---

@if($order->uuid ?? null)
@component('mail::button', ['url' => url('/admin/orders/' . $order->uuid)])
View Your Order
@endcomponent
@endif

Thanks for choosing **{{ setting('app_name') }}** 💙
**{{ setting('app_name') }} Team**

@endcomponent
