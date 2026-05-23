<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'sku'       => $this->sku,
            'discount'     => $this->discount,
            'discount_type' => $this->discount_type,
            'max_order_quantity' => $this->max_order_quantity,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'is_featured' => $this->is_featured,
            'is_new' => $this->is_new,
            'is_active' => $this->is_active,
            'is_bookable' => $this->is_bookable,
            'manage_stock' => $this->manage_stock,
            // 'thumb_image'     => $this->images()->first() ? asset('storage/' . $this->images()->first()->path) : null,
            'thumb_image' => $this->images->first()?->image_path,

            'images' => ImageResource::collection($this->whenLoaded('images')),
            'unit' =>$this->whenLoaded('unit'),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'related_products' => $this->whenLoaded('relatedProducts', function () {
                return ProductResource::collection(
                    $this->relatedProducts->map->relatedProduct->filter()
                );
            }),

            'cross_sell_products' => $this->whenLoaded('crossSellProducts', function () {
                return ProductResource::collection(
                    $this->crossSellProducts->map->relatedProduct->filter()
                );
            }),
            'reviews' => ReviewResource::collection($this->whenLoaded('approvedReviews')),
            'average_rating' => $this->when(true, $this->averageRating()),

            'is_favorite' => $this->is_favorite ? $this->is_favorite : false,

            'price' => $this->manager()->price(),
            'stock' => $this->manager()->stock(),
            'type' => $this->type ?? 'simple',
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),

            // Variant options grouped by variant type
            'variant_options' => $this->when($this->type === 'variable', function () {
                return $this->getVariantOptions();
            }),



            // Variants summary
            'variants_summary' => $this->when($this->type === 'variable', function () {
                $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();

                if ($variants->isEmpty()) {
                    return [
                        'total_variants' => 0,
                        'active_variants' => 0,
                        'total_stock' => 0,
                        'price_range' => [
                            'min' => null,
                            'max' => null,
                        ],
                    ];
                }

                return [
                    'total_variants' => $variants->count(),
                    'active_variants' => $variants->where('is_active', true)->count(),
                    'total_stock' => $variants->sum(function ($variant) {
                        return $variant->branchVariantStocks->sum('quantity');
                    }),
                    'price_range' => [
                        'min' => $variants->min('price'),
                        'max' => $variants->max('price'),
                    ],
                ];
            }),
        ];
    }

    /**
     * Get variant options grouped by variant type
     *
     * @return array
     */
    protected function getVariantOptions(): array
    {
        if (!$this->relationLoaded('variants')) {
            $this->load('variants.values.variantOption.variant');
        }

        $groupedOptions = [];

        foreach ($this->variants as $productVariant) {
            foreach ($productVariant->values as $value) {
                if ($value->variantOption && $value->variantOption->variant) {
                    $variantId = $value->variantOption->variant->id;
                    $variantName = $value->variantOption->variant->name;
                    $optionId = $value->variantOption->id;
                    $optionName = $value->variantOption->name;
                    $optionCode = $value->variantOption->code;

                    // Initialize variant group if not exists
                    if (!isset($groupedOptions[$variantId])) {
                        $groupedOptions[$variantId] = [
                            'variant_id' => $variantId,
                            'variant_name' => $variantName,
                            'options' => [],
                        ];
                    }

                    // Add option if not already added (avoid duplicates)
                    $optionExists = false;
                    foreach ($groupedOptions[$variantId]['options'] as $existingOption) {
                        if ($existingOption['id'] === $optionId) {
                            $optionExists = true;
                            break;
                        }
                    }

                    if (!$optionExists) {
                        $groupedOptions[$variantId]['options'][] = [
                            'id' => $optionId,
                            'name' => $optionName,
                            'code' => $optionCode,
                        ];
                    }
                }
            }
        }

        // Convert to indexed array
        return array_values($groupedOptions);
    }
}
