@component('mail::message')

{{-- Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('storage/' . setting('app_logo')) }}" alt="{{ setting('app_name') }}" style="max-height: 80px;">
</div>

# Product Available - {{ $bookingList->product->name ?? 'Product' }}

Hello **{{ $bookingList->user->name ?? 'Customer' }}**,

Great news! 🎉 The product you were waiting for is now available in stock.

@component('mail::panel')
**{{ $bookingList->product->name ?? 'Product' }}** is now available!

You can order it now from your account dashboard.
@endcomponent

---

### 📦 Booking Details:
- **Product:** {{ $bookingList->product->name ?? 'Product' }}
- **Quantity Requested:** {{ $bookingList->quantity ?? 0 }}
- **Expected Date:** {{ $bookingList->expected_at ? $bookingList->expected_at->format('Y-m-d H:i') : 'Not set' }}
- **Booking Date:** {{ $bookingList->created_at ? $bookingList->created_at->format('Y-m-d H:i') : 'N/A' }}

---

@if($bookingList->product && ($bookingList->product->slug ?? null))
@component('mail::button', ['url' => url('/api/products/' . $bookingList->product->id)])
🛒 View Product
@endcomponent
@endif

Thanks for choosing **{{ setting('app_name') }}** 💙
**{{ setting('app_name') }} Team**

@endcomponent
