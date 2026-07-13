<?php

namespace App\Services;

use App\Events\AssignDeliveryEvent;
use App\Events\NewOrderEvent;
use App\Events\OrderUpdated;
use App\Events\ProductCreated;
use App\Events\ProductDeleted;
use App\Events\ProductUpdated;
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

    public static function orderUpdated(
        Order $order,
        ?int $previousBranchId = null,
        ?int $previousDeliveryId = null,
    ): void {
        $order->loadMissing(['user', 'branch', 'items']);

        $payload = self::orderPayload($order);

        event(new OrderUpdated($order, $payload, $previousBranchId, $previousDeliveryId));
        SocketService::orderUpdated(
            $payload,
            $order->branch_id,
            $order->user_id,
            $order->delivery_id,
            $previousBranchId,
            $previousDeliveryId,
        );
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
            'user_id' => $order->user_id,
            'customer_name' => $order->user?->name,
            'branch_id' => $order->branch_id,
            'branch_name' => $order->branch?->getTranslation('name', 'en'),
            'delivery_id' => $order->delivery_id,
            'total' => (float) $order->final_total,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'items_count' => $order->relationLoaded('items') ? $order->items->count() : $order->items()->count(),
            'created_at' => $order->created_at?->toIso8601String(),
            'updated_at' => $order->updated_at?->toIso8601String(),
        ];
    }

    public static function productUpdated(Product $product): void
    {
        if (! $product->is_active) {
            self::productDeleted($product);

            return;
        }

        $product->loadMissing(['images', 'unit', 'categories']);

        $payload = [
            'product' => self::productPayload($product),
        ];

        event(new ProductUpdated($product, $payload));
        SocketService::productUpdated($payload);
    }

    public static function productDeleted(Product|int $product): void
    {
        $productId = $product instanceof Product ? $product->id : $product;

        $payload = [
            'product' => [
                'id' => $productId,
            ],
        ];

        event(new ProductDeleted($product instanceof Product ? $product : $productId, $payload));
        SocketService::productDeleted($payload);
    }

    public static function assignDelivery(Order $order): void
    {
        $order->loadMissing(['delivery', 'user', 'branch', 'items']);

        $payload = self::orderPayload($order);

        event(new AssignDeliveryEvent($order, $payload));
        SocketService::deliveryOrderAssigned($payload, $order->delivery_id);
    }
}
