<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsReportExport implements FromCollection, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get()->map(function ($product) {
            return [
                'Product Name' => $product->name,
                'Category' => $product->category->name,
                'Brand' => $product->brand->name,
                'Stock' => $product->stock,
                'Price' => $product->selling_price,
                'Created At' => $product->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return ['Product Name', 'Category', 'Brand', 'Stock', 'Price', 'Created At'];
    }
}
