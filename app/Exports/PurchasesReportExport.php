<?php

namespace App\Exports;

use App\Models\PurchaseDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesReportExport implements FromCollection, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get()->map(function ($purchaseDetail) {
            return [
                'Date' => \Carbon\Carbon::parse($purchaseDetail->purchase->date)->format('d-m-Y'),
                'Reference' => $purchaseDetail->purchase->reference,
                'Product Name' => $purchaseDetail->product ? $purchaseDetail->product->name : 'N/A',
                'Product Code' => $purchaseDetail->product ? $purchaseDetail->product->code : 'N/A',
                'Supplier' => $purchaseDetail->purchase->supplier ? $purchaseDetail->purchase->supplier->name : 'N/A',
                'Unit Price' => $purchaseDetail->unit_price . ' $',
                'Discount' => $purchaseDetail->purchase->discount . ' $',
                'Quantity' => $purchaseDetail->quantity,
                'Total' => $purchaseDetail->total_price . ' $',
                'Status' => $purchaseDetail->purchase->status,
                'Payment Status' => $purchaseDetail->purchase->payment_status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Reference',
            'Product Name',
            'Product Code',
            'Supplier',
            'Unit Price',
            'Discount',
            'Quantity',
            'Total',
            'Status',
            'Payment Status',
        ];
    }
}
