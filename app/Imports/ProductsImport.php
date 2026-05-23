<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsImport implements ToCollection, WithHeadingRow, ShouldQueue, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $skus = $rows->pluck('sku')->filter()->unique()->values();
        $existingSkus = Product::whereIn('sku', $skus)->pluck('sku')->toArray();

        $newRows = $rows->reject(fn($row) => in_array($row['sku'], $existingSkus));

        $alreadySeen = [];
        $uniqueRows = $newRows->reject(function ($row) use (&$alreadySeen) {
            if (in_array($row['sku'], $alreadySeen)) {
                return true;
            }
            $alreadySeen[] = $row['sku'];
            return false;
        });

        foreach ($uniqueRows as $row) {
            Log::info('Importing product: ' . $row);
            if (empty($row['sku'])) continue;

            /** 🧩 Create Product */
            $product = Product::create([
                'sku' => $row['sku'],
                'slug' => Str::slug($row['name_en'] ?? $row['sku']),

                'name' => [
                    'en' => $row['name_en'] ?? '',
                    'ar' => $row['name_ar'] ?? '',
                ],
                'description' => [
                    'en' => $row['description_en'] ?? '',
                    'ar' => $row['description_ar'] ?? '',
                ],
                'short_description' => [
                    'en' => $row['short_description_en'] ?? '',
                    'ar' => $row['short_description_ar'] ?? '',
                ],

                'discount' => $row['discount'] ?? 0,
                'discount_type' => $row['discount_type'] ?? 'percent',
                'max_order_quantity' => $row['max_order_quantity'] ?? null,
                'manage_stock' => filter_var($row['manage_stock'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'is_active' => filter_var($row['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'is_featured' => filter_var($row['is_featured'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'is_new' => filter_var($row['is_new'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ]);

            /** 🔹 Categories (IDs separated by |) */
            if (!empty($row['categories'])) {
                $categoryIds = array_filter(array_map('trim', explode('|', $row['categories'])));
                $validIds = Category::whereIn('id', $categoryIds)->pluck('id')->toArray();
                Log::info('Categories IDS: ' . json_encode($categoryIds));
                Log::info('Categories: ' . json_encode($validIds));
                $product->categories()->sync($validIds);
            }

            /** 🔹 Images (multiple URLs separated by |) */
            if (!empty($row['image_url'])) {
                $imageUrls = array_filter(array_map('trim', explode('|', $row['image_url'])));
                foreach ($imageUrls as $url) {
                    try {
                        $response = Http::get($url);
                        if ($response->successful()) {
                            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                            $filename = uniqid('product_') . '.' . ($extension ?: 'jpg');
                            $path = 'products/' . $filename;
                            Storage::disk('public')->put($path, $response->body());
                            $product->images()->create(['path' => $path]);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Image download failed for SKU {$row['sku']} URL {$url}: " . $e->getMessage());
                    }
                }
            }
        }

        Log::info('✅ Imported ' . $uniqueRows->count() . ' new products successfully.');
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
