<?php

namespace App\Services;

use App\Events\NewOrderEvent;
use App\Events\ProductCreated;
use App\Models\Order;
use App\Models\Product;

class RealtimeService
{
    public static function productCreated(Product $product): void
    {
        if (! $product->is_active) {
            return;
        }

        $product->loadMissing(['images', 'unit', 'categories']);

        $payload = [
            'product' => self::productPayload($product),
        ];

        event(new ProductCreated($product, $payload));
        SocketService::productCreated($payload);
    }

    public static function orderCreated(Order $order): void
    {
        $order->loadMissing(['user', 'branch', 'items']);

        $payload = self::orderPayload($order);

        event(new NewOrderEvent($order, $payload));
        SocketService::orderCreated($payload, $order->branch_id);
    }

    public static function productPayload(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->getTranslations('name'),
            'slug' => $product->slug,
            'sku' => $product->sku,
            'price' => (float) $product->price,
            'type' => $product->type ?? 'simple',
            'is_active' => (bool) $product->is_active,
            'is_new' => (bool) $product->is_new,
            'is_featured' => (bool) $product->is_featured,
            'thumb_image' => $product->images->first()?->image_path,
        ];
    }

    public static function orderPayload(Order $order): array
    {
        return [
            'id' => $order->id,
            'uuid' => $order->uuid,
            'customer_name' => $order->user?->name,
            'branch_id' => $order->branch_id,
            'branch_name' => $order->branch?->getTranslation('name', 'en'),
            'total' => (float) $order->final_total,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'items_count' => $order->items->count(),
            'created_at' => $order->created_at?->toIso8601String(),
        ];
    }
}
