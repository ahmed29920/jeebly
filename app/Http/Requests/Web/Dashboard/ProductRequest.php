<?php

namespace App\Http\Requests\Web\Dashboard;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * @property Product|null $product
 */
class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = request()->get('type', 'simple');

        $rules = [
            'type'                  => 'required|in:simple,variable',
            'categories'          => 'nullable|array',
            'categories.*'        => 'exists:categories,id',
            'name.en'              => 'required|string|max:255',
            'name.ar'              => 'required|string|max:255',
            'slug'                 => ['required', 'string', 'max:255', unique_among_active('products', 'slug', $this->product?->id)],
            'description.en'       => 'nullable|string',
            'description.ar'       => 'nullable|string',
            'short_description.en' => 'nullable|string',
            'short_description.ar' => 'nullable|string',
            'sku'                  => ['required', 'string', 'max:255', unique_among_active('products', 'sku', $this->product?->id)],
            'discount'             => 'nullable|numeric|min:0',
            'discount_type'        => 'nullable|in:percentage,fixed',
            'max_order_quantity'   => 'nullable|integer|min:1',
            'manage_stock'         => 'boolean',
            'is_active'            => 'boolean',
            'is_featured'          => 'boolean',
            'is_new'               => 'boolean',
            'is_bookable'          => 'boolean',
            'unit_id'             => 'nullable|integer|exists:units,id',
            'images'               => 'nullable|array',
            'images.*'             => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'attributes'           => 'nullable|array',
            'attributes.*.attribute_id'         => 'required|exists:attributes,id',
            'attributes.*.attribute_option_id'  => 'nullable|exists:attribute_options,id',
            'attributes.*.value'                => 'nullable|string|max:255',

            'related_products'   => 'nullable|array',
            'related_products.*' => 'integer|exists:products,id',

            'cross_sell_products'   => 'nullable|array',
            'cross_sell_products.*' => 'integer|exists:products,id',

            // SEO fields
            'meta_title.en'       => 'nullable|string|max:255',
            'meta_title.ar'       => 'nullable|string|max:255',
            'meta_description.en' => 'nullable|string|max:500',
            'meta_description.ar' => 'nullable|string|max:500',
            'meta_keywords.en'    => 'nullable|string|max:255',
            'meta_keywords.ar'    => 'nullable|string|max:255',
        ];

        // Conditional validation based on product type
        if ($type === 'simple') {
            $rules['price'] = 'required|numeric|min:0';
            $rules['stock'] = 'nullable|integer|min:0';
            $rules['product_variants'] = 'nullable|array';
            $rules['branch_stocks'] = 'nullable|array';
            $rules['branch_stocks.*'] = 'nullable|integer|min:0';
        } else {
            $rules['price'] = 'nullable|numeric|min:0';
            $rules['stock'] = 'nullable|integer|min:0';
            $rules['product_variants'] = 'required|array|min:1';
            $rules['product_variants.*.id'] = 'nullable|exists:product_variants,id';
            $rules['product_variants.*.name.en'] = 'required|string|max:255';
            $rules['product_variants.*.name.ar'] = 'required|string|max:255';
            $rules['product_variants.*.slug'] = 'required|string|max:255';
            $rules['product_variants.*.sku'] = 'required|string|max:255';
            $rules['product_variants.*.price'] = 'required|numeric|min:0';
            $rules['product_variants.*.stock'] = 'nullable|integer|min:0';
            $rules['product_variants.*.is_active'] = 'nullable|boolean';
            $rules['product_variants.*.variant_values'] = 'nullable|array';
            $rules['product_variants.*.variant_values.*.variant_option_id'] = 'required|exists:variant_options,id';
            $rules['product_variants.*.images'] = 'nullable|array';
            $rules['product_variants.*.images.*'] = 'image|mimes:jpeg,png,jpg,gif,webp|max:4096';
            $rules['product_variants.*.remove_images'] = 'nullable|array';
            $rules['product_variants.*.remove_images.*'] = 'nullable|exists:product_images,id';
            $rules['product_variants.*.branch_stocks'] = 'nullable|array';
            $rules['product_variants.*.branch_stocks.*'] = 'nullable|integer|min:0';
        }

        return $rules;
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $productId = $this->product?->id;
            $type = request()->get('type', 'simple');
            $productVariants = request()->get('product_variants', []);

            if ($productId) {
                if (in_array($productId, $this->related_products ?? [])) {
                    $validator->errors()->add('related_products', 'Product cannot be related to itself.');
                }

                if (in_array($productId, $this->cross_sell_products ?? [])) {
                    $validator->errors()->add('cross_sell_products', 'Product cannot be cross-sell of itself.');
                }
            }

            if ($type === 'variable') {
                if (empty($productVariants) || count($productVariants) === 0) {
                    $validator->errors()->add('product_variants', 'Variable products must have at least one variant.');
                } else {
                    $this->validateVariantUniqueness($validator, $productVariants);
                }
            }
        });
    }

    protected function validateVariantUniqueness(Validator $validator, array $productVariants): void
    {
        $slugs = [];
        $skus = [];

        foreach ($productVariants as $index => $variant) {
            $slug = $variant['slug'] ?? null;
            $sku = $variant['sku'] ?? null;
            $variantId = $variant['id'] ?? null;

            if ($slug) {
                if (in_array($slug, $slugs, true)) {
                    $validator->errors()->add("product_variants.{$index}.slug", 'Duplicate variant slug in this form.');
                } else {
                    $slugs[] = $slug;

                    $slugTaken = ProductVariant::whereNull('deleted_at')
                        ->where('slug', $slug)
                        ->when($variantId, fn ($q) => $q->where('id', '!=', $variantId))
                        ->exists();

                    if ($slugTaken) {
                        $validator->errors()->add("product_variants.{$index}.slug", 'Variant slug must be unique.');
                    }
                }
            }

            if ($sku) {
                if (in_array($sku, $skus, true)) {
                    $validator->errors()->add("product_variants.{$index}.sku", 'Duplicate variant SKU in this form.');
                } else {
                    $skus[] = $sku;

                    $skuTaken = ProductVariant::whereNull('deleted_at')
                        ->where('sku', $sku)
                        ->when($variantId, fn ($q) => $q->where('id', '!=', $variantId))
                        ->exists();

                    if ($skuTaken) {
                        $validator->errors()->add("product_variants.{$index}.sku", 'Variant SKU must be unique.');
                    }
                }
            }
        }
    }

    public function messages(): array
    {
        return [
            'name.en.required' => 'The English name is required.',
            'name.ar.required' => 'The Arabic name is required.',
            'slug.required' => 'The slug is required.',
            'sku.required' => 'The SKU is required.',
        ];
    }
}
