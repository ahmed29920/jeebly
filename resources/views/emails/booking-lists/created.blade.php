@component('mail::message')

{{-- Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('storage/' . setting('app_logo')) }}" alt="{{ setting('app_name') }}" style="max-height: 80px;">
</div>

# Booking Confirmation - #{{ $bookingList->id }}

Hello **{{ $bookingList->user->name ?? 'Customer' }}**,

Thank you for your booking request! 📋
We have successfully received your booking and will notify you once the product becomes available.

@component('mail::panel')
**{{ $bookingList->product->name ?? 'Product' }}** - Booking Confirmed

We'll notify you as soon as this product is back in stock.
@endcomponent

---

### 📦 Booking Details:
- **Booking ID:** #{{ $bookingList->id }}
- **Product:** {{ $bookingList->product->name ?? 'Product' }}
- **Quantity Requested:** {{ $bookingList->quantity ?? 0 }}
- **Expected Date:** {{ $bookingList->expected_at ? $bookingList->expected_at->format('Y-m-d H:i') : 'Not set' }}
- **Status:** <span style="font-weight: bold; color: {{ $bookingList->status == 'confirmed' ? '#28a745' : ($bookingList->status == 'pending' ? '#ffc107' : '#6c757d') }};">{{ ucfirst($bookingList->status ?? 'pending') }}</span>
- **Booking Date:** {{ $bookingList->created_at ? $bookingList->created_at->format('Y-m-d H:i') : 'N/A' }}

---

@if($bookingList->product && ($bookingList->product->id ?? null))
@component('mail::button', ['url' => url('/api/products/' . $bookingList->product->id)])
🛒 View Product
@endcomponent
@endif

---

**Note:** You will receive an email notification once the product is available in stock. You can also check your booking status anytime from your account dashboard.

Thanks for choosing **{{ setting('app_name') }}** 💙
**{{ setting('app_name') }} Team**

@endcomponent


