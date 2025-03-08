<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromQuery, WithHeadings, WithMapping
{
    protected $categoryId;
    protected $brandId;

    public function __construct($categoryId = null, $brandId = null)
    {
        $this->categoryId = $categoryId;
        $this->brandId = $brandId;
    }

    public function query()
    {
        $query = Product::query()
            ->with(['category', 'brand']); // Include category and brand relationships

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->brandId) {
            $query->where('brand_id', $this->brandId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ឈ្មោះ',
            'លេខកូដផលិតផល',
            'តម្លៃដើម',
            'តម្លៃលក់',
            'ចំនួន',
            'ការជូនដំណឹងស្តុក',
            'ការពិពណ៌នា',
            'រូបភាព',
            'ស្ថានភាព',
            'ប្រភេទផលិតផល', // Replaces category_id
            'ម៉ាក',    // Replaces brand_id
        ];

    }

    public function map($product): array
    {
        return [
            // $product->id,
            $product->name,
            $product->barcode,
            $product->cost_price,
            $product->selling_price,
            $product->quantity,
            $product->stock_alert,
            $product->description,
            // $product->image,
            $product->status ? 'សកម្ម' : 'អសកម្ម',
            $product->category?->name ?? 'គ្មាន', // Get category name
            $product->brand?->name ?? 'N/A',    // Get brand name
        ];
    }
}
