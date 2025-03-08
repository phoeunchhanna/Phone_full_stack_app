<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleExport implements FromQuery, WithHeadings, WithMapping
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
        $query = Sale::query()->with(['customer', 'saleDetails.product']);

        // Apply category filter if provided
        if ($this->categoryId) {
            $query->whereHas('saleDetails.product.category', function ($query) {
                $query->where('category_id', $this->categoryId);
            });
        }

        // Apply brand filter if provided
        if ($this->brandId) {
            $query->whereHas('saleDetails.product.brand', function ($query) {
                $query->where('brand_id', $this->brandId);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ថ្ងៃខែឆ្នាំ',
            'លេខយោង',
            'ឈ្មោះអតិថិជន',
            'តម្លៃសរុប',
            'បញ្ចុះតម្លៃ',
            'ទឹកប្រាក់ដែលបានបង់',
            'ទឹកប្រាក់ដែលនៅខ្វះ',
            'ស្ថានភាព',
            'ការពិពណ៌នា',
        ];

    }

    public function map($sale): array
    {
        return [
            $sale->date,
            $sale->reference,
            $sale->customer?->name ?? 'គ្មាន', // Get customer name
            $sale->total_amount,
            $sale->discount,
            $sale->paid_amount,
            $sale->due_amount,
            $sale->status ? 'បញ្ចប់' : 'រង់ចាំ',
            $sale->description,
        ];
    }
}
