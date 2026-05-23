<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::withTrashed()
            ->with([
                'user',
                'items.product' => fn ($query) => $query->withTrashed(),
                'billingAddress',
                'shippingAddress',
            ])
            ->get()
            ->map(function ($order) {
                return [
                    'id'                    => $order->id,
                    'uuid'                  => $order->uuid,
                    'user_name'             => $order->user->name ?? '',
                    'user_email'            => $order->user->email ?? '',
                    'user_phone'            => $order->user->phone ?? '',
                    'status'                => $order->status,
                    'payment_method'        => $order->payment_method,
                    'payment_status'        => $order->payment_status,
                    'total'                 => $order->total,
                    'discount'              => $order->discount,
                    'coupon_discount_value' => $order->coupon_discount_value,
                    'coupon'                => $order->coupon?->code,
                    'final_total'           => $order->final_total,
                    'shipping_cost'         => $order->shipping_cost,
                    'billing_address'       => $order->billingAddress
                        ? $order->billingAddress->address . ', ' . $order->billingAddress->city . ', ' . $order->billingAddress->country
                        : '',
                    'shipping_address'      => $order->shippingAddress
                        ? $order->shippingAddress->address . ', ' . $order->shippingAddress->city . ', ' . $order->shippingAddress->country
                        : '',
                    'items'                 => $order->items->map(function ($item) {
                        $name = $item->product?->name ?? __('Deleted product #:id', ['id' => $item->product_id]);

                        return $name . ' x ' . $item->quantity;
                    })->join(', '),
                    'created_at'            => $order->created_at->toDateTimeString(),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'UUID',
            'User Name',
            'User Email',
            'User Phone',
            'Status',
            'Payment Method',
            'Payment Status',
            'Total',
            'Discount',
            'Coupon Discount Value',
            'Coupon',
            'Final Total',
            'Shipping Cost',
            'Billing Address',
            'Shipping Address',
            'Items',
            'Created At',
        ];
    }
}
