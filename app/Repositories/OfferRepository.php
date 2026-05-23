<?php

namespace App\Repositories;

use App\Models\Offer;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class OfferRepository
{
    public function all(): Collection
    {
        return Offer::orderBy('start_date', 'desc')->get();
    }

    /**
     * Get only active offers (date + status)
     */
    public function getActive(): Collection
    {
        $now = Carbon::now();

        return Offer::where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function find(int $id): ?Offer
    {
        return Offer::find($id);
    }


    public function create(array $data): Offer
    {
        return Offer::create($data);
    }


    public function update(Offer $offer, array $data): Offer
    {
        $offer->update($data);
        return $offer;
    }


    public function delete(Offer $offer): bool
    {
        return $offer->delete();
    }


    public function getByType(string $type): Collection
    {
        return Offer::where('type', $type)
            ->where('is_active', true)
            ->get();
    }


    public function getByProduct(int $productId): Collection
    {
        return Offer::where('type', 'product')
            ->whereJsonContains('condition->product_id', $productId)
            ->where('is_active', true)
            ->get();
    }


    public function getByCategory(int $categoryId): Collection
    {
        return Offer::where('type', 'category')
            ->whereJsonContains('condition->category_id', $categoryId)
            ->where('is_active', true)
            ->get();
    }
}
