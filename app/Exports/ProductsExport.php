<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with('categories')->get()->map(function ($product) {
            return [
                'id'                     => $product->id,
                'name_en'                => $product->getTranslation('name', 'en'),
                'name_ar'                => $product->getTranslation('name', 'ar'),
                'short_description_en'   => $product->getTranslation('short_description', 'en'),
                'short_description_ar'   => $product->getTranslation('short_description', 'ar'),
                'description_en'         => $product->getTranslation('description', 'en'),
                'description_ar'         => $product->getTranslation('description', 'ar'),
                'sku'                    => $product->sku,
                'slug'                   => $product->slug,
                'price'                  => $product->price,
                'discount'               => $product->discount,
                'stock'                  => $product->stock,
                'is_featured'            => $product->is_featured,
                'is_active'              => $product->is_active,
                'is_new'                 => $product->is_new,
                'category'               => $product->categories->pluck('name')->implode(', '),
                'meta_title_en'          => $product->getTranslation('meta_title', 'en'),
                'meta_title_ar'          => $product->getTranslation('meta_title', 'ar'),
                'meta_description_en'    => $product->getTranslation('meta_description', 'en'),
                'meta_description_ar'    => $product->getTranslation('meta_description', 'ar'),
                'meta_keywords_en'       => $product->getTranslation('meta_keywords', 'en'),
                'meta_keywords_ar'       => $product->getTranslation('meta_keywords', 'ar'),
                'created_at'             => $product->created_at->toDateTimeString(),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name EN',
            'Name AR',
            'Short Description EN',
            'Short Description AR',
            'Description EN',
            'Description AR',
            'SKU',
            'Slug',
            'Price',
            'Discount',
            'Stock',
            'Is Featured',
            'Is Active',
            'Is New',
            'Category',
            'Meta Title EN',
            'Meta Title AR',
            'Meta Description EN',
            'Meta Description AR',
            'Meta Keywords EN',
            'Meta Keywords AR',
            'Created At',
        ];
    }
}
