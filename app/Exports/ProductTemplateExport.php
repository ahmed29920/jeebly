<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ProductTemplateExport implements FromArray, WithHeadings, WithEvents
{
    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return [
            'sku',
            'name_en',
            'name_ar',
            'description_en',
            'description_ar',
            'short_description_en',
            'short_description_ar',
            'discount',
            'discount_type',     // percent | fixed
            'max_order_quantity',
            'manage_stock',      // TRUE | FALSE
            'is_active',         // TRUE | FALSE
            'is_featured',       // TRUE | FALSE
            'is_new',            // TRUE | FALSE
            'categories_list',   // dropdown (reference)
            'categories',        // manual input (IDs separated by |)
            'image_url',         // one or more URLs separated by |
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = 1000;

                /** 🔹 Categories dropdown */
                $categories = Category::select('id', 'name')->get();
                $categoryList = $categories->map(fn($c) => $c->id . ' (' . $c->name . ')')->implode(',');

                for ($row = 2; $row <= $highestRow; $row++) {
                    $validation = $sheet->getCell("O{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1('"' . $categoryList . '"');
                    $sheet->getCell("O{$row}")->setDataValidation($validation);
                }

                /** 🔹 Comment for categories column */
                $sheet->getComment('P1')->getText()->createTextRun(
                    "Enter one or more category IDs separated by |.\nExample: 1|3|8\nUse 'categories_list' as reference."
                );

                /** 🔹 Discount Type dropdown */
                for ($row = 2; $row <= $highestRow; $row++) {
                    $validation = $sheet->getCell("I{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1('"percent,fixed"');
                    $sheet->getCell("I{$row}")->setDataValidation($validation);
                }

                /** 🔹 Boolean dropdowns */
                $boolCols = ['K', 'L', 'M', 'N'];
                foreach ($boolCols as $col) {
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $validation = $sheet->getCell("{$col}{$row}")->getDataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(true);
                        $validation->setShowDropDown(true);
                        $validation->setFormula1('"TRUE,FALSE"');
                        $sheet->getCell("{$col}{$row}")->setDataValidation($validation);
                    }
                }

                /** 🔹 Image instructions */
                $sheet->getComment('Q1')->getText()->createTextRun(
                    "You can add one or more image URLs separated by |"
                );
            },
        ];
    }
}
