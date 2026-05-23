<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Category::all()->map(function ($category) {
            return [
                'id'         => $category->id,
                'name_en'    => $category->getTranslation('name', 'en'),
                'name_ar'    => $category->getTranslation('name', 'ar'),
                'slug'       => $category->slug,
                'created_at' => $category->created_at->toDateTimeString(),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name EN',
            'Name AR',
            'Slug',
            'Created At',
        ];
    }
}
