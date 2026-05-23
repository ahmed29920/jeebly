<?php

namespace App\Services;

use App\Repositories\OfferRepository;
use App\Models\Offer;
use Illuminate\Support\Collection;

class OfferService
{
    protected OfferRepository $offerRepo;

    public function __construct(OfferRepository $offerRepo)
    {
        $this->offerRepo = $offerRepo;
    }

    /**
     * Get all active offers.
     */
    public function getActiveOffers(): Collection
    {
        return $this->offerRepo->getActive();
    }

    /**
     * Apply the best offer (highest discount) from all active offers.
     *
     * @param  Collection  $cartItems
     * @param  float  $subtotal
     * @return array{offer: ?Offer, discount: float}
     */
    public function applyBestOffer(Collection $cartItems, float $subtotal)
    {
        $offers = $this->getActiveOffers();
        $maxDiscount = 0;
        $bestOffer = null;
        $freeQuantity = 0;
        $freeQuantityProducts = [];
        foreach ($offers as $offer) {
            $discountData = $this->calculateOfferDiscount($offer, $cartItems, $subtotal);
            $discountValue = $discountData['discount'];
            $freeQuantity = $discountData['free_quantity'];
            $freeQuantityProducts = array_merge($freeQuantityProducts, $discountData['free_quantity_products']);
            if ($discountValue > $maxDiscount) {
                $maxDiscount = $discountValue;
                $bestOffer = $offer;
            }
        }

        return [
            'offer' => $bestOffer,
            'discount' =>  $freeQuantity > 0 ? 0 : $maxDiscount,
            'free_quantity' => $freeQuantity,
            'free_quantity_products' => $freeQuantityProducts,
        ];
    }


    /**
     * Calculate discount amount for a given offer.
     */
    protected function calculateOfferDiscount(Offer $offer, Collection $cartItems, float $subtotal)
    {
        $x = match ($offer->type) {
            'product'  => $this->applyProductOffer($offer, $cartItems),
            'category' => $this->applyCategoryOffer($offer, $cartItems),
            'cart'     => $this->applyCartOffer($offer, $subtotal),
            'shipping' => $this->applyShippingOffer($offer),
            default    => 0,
        };
        return $x;
    }
    /**
     * Apply a product-based offer
     */
    protected function applyProductOffer(Offer $offer, Collection $cartItems)
    {
        $discount = 0;
        $freeQuantity = 0;
        $productId = $offer->getConditionValue('product_id');
        $allowMultipleFree = setting('allow_more_than_one_free_item') ?? false;
        $hasGivenFree = false;

        if (!$productId) {
            return ['discount' => 0, 'free_quantity' => 0];
        }

        $totalFreeQty = 0;
        $freeQuantityProducts = [];

        foreach ($cartItems as $item) {
            if ($item->product_id == $productId) {
                if (!$allowMultipleFree && $hasGivenFree) {
                    continue;
                }
                $discountData = $this->calculateDiscount(
                    $item->price * $item->quantity,
                    $offer->discount_type,
                    $offer->discount_value,
                    $item->quantity
                );
                $discount += $discountData['discount'];
                $freeQuantity += $discountData['free_quantity'];
                $freeQuantityProducts = array_merge($freeQuantityProducts, $discountData['free_quantity_products']);
                if ($discountData['free_quantity'] > 0) {
                    $hasGivenFree = true;
                }
            }
        }
        return [
            'discount' => $discount,
            'free_quantity' => $freeQuantity,
            'free_quantity_products' => $freeQuantityProducts,
        ];
    }

    /**
     * Apply a category-based offer
     */
    protected function applyCategoryOffer(Offer $offer, Collection $cartItems)
    {
        $discount = 0;
        $freeQuantity = 0;
        $categoryId = $offer->getConditionValue('category_id');
        $allowMultipleFree = setting('allow_more_than_one_free_item') ?? false;
        $hasGivenFree = false;
        $freeQuantityProducts = [];

        if (!$categoryId) {
            return ['discount' => 0, 'free_quantity' => 0, 'free_quantity_products' => []];
        }   

        foreach ($cartItems as $item) {
            if ($item->product->categories->pluck('id')->contains($categoryId)) {

                if (!$allowMultipleFree && $hasGivenFree) {
                    continue;
                }

                $price = $item->product->packSizes()
                    ->where('id', $item->pack_size_id)
                    ->first()
                    ->pivot
                    ->price;

                $discountData = $this->calculateDiscount(
                    $price * $item->quantity,
                    $offer->discount_type,
                    $offer->discount_value,
                    $item->quantity,
                    $item->product_id
                );
                $discount += $discountData['discount'];
                $freeQuantity = $discountData['free_quantity'];
                $freeQuantityProducts = array_merge($freeQuantityProducts, $discountData['free_quantity_products']);

                if ($discountData['free_quantity'] > 0) {
                    $hasGivenFree = true;
                }
            }
        }

        return [
            'discount' => $discount,
            'free_quantity' => $freeQuantity,
            'free_quantity_products' => $freeQuantityProducts,
        ];
    }

    /**
     * Apply a cart-based offer
     */
    protected function applyCartOffer(Offer $offer, float $subtotal)
    {
        $minCart = $offer->getConditionValue('min_cart_amount');
        $discount = 0;
        $freeQuantity = 0;
        $freeQuantityProducts = [];
        if ($minCart && $subtotal < $minCart) {
            return ['discount' => 0, 'free_quantity' => 0, 'free_quantity_products' => []];
        }

        $discountData = $this->calculateDiscount($subtotal, $offer->discount_type, $offer->discount_value);
        $discount += $discountData['discount'];
        $freeQuantity += $discountData['free_quantity'];
        $freeQuantityProducts = array_merge($freeQuantityProducts, $discountData['free_quantity_products']);
        return [
            'discount' => $discount,
            'free_quantity' => $freeQuantity,
            'free_quantity_products' => $freeQuantityProducts,
        ];
    }

    /**
     * Apply a shipping offer (e.g., free shipping or fixed discount)
     */
    protected function applyShippingOffer(Offer $offer)
    {
        if ($offer->discount_type === 'free_shipping') {
            return [
                'discount' => setting('shipping_cost') ?? 0,
                'free_quantity' => 0,
                'free_quantity_products' => [],
            ];
        }

        $discountData = $this->calculateDiscount(setting('shipping_cost') ?? 0, $offer->discount_type, $offer->discount_value);
        return [
            'discount' => $discountData['discount'],
            'free_quantity' => $discountData['free_quantity'],
            'free_quantity_products' => $discountData['free_quantity_products'],
        ];
    }

    /**
     * Helper function: Calculate discount based on type
     */
    protected function calculateDiscount(float $base, string $type, float $value, int $quantity = 1, int $productId = null): array
    {
        return match ($type) {
            'percent' => [
                'discount' => $base * ($value / 100),
                'free_quantity' => 0,
                'free_quantity_products' => [],
            ],
            'fixed' => [
                'discount' => min($base, $value),
                'free_quantity' => 0,
                'free_quantity_products' => [],
            ],
            'bogo' => [
                'discount' => $base / $quantity,
                'free_quantity' => min(1, floor($quantity / 1)),
                'free_quantity_products' => $productId ? [$productId] : [],
            ],
            default => [
                'discount' => 0,
                'free_quantity' => 0,
                'free_quantity_products' => [],
            ],
        };  
    }

    public function getAllOffers($request)
    {
        return $this->offerRepo->all();
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('offers', 'public');
        }
        return $this->offerRepo->create($data);
    }

    public function update(Offer $offer, array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('offers', 'public');
        }
        return $this->offerRepo->update($offer, $data);
    }

    public function delete(Offer $offer)
    {
        return $this->offerRepo->delete($offer);
    }

    public function toggleStatus(Offer $offer)
    {
        $offer->update(['is_active' => !$offer->is_active]);
        return $offer;
    }
}
