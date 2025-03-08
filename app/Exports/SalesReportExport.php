<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportExport implements FromCollection, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get()->map(function ($saleDetail) {
            return [
                'Date'           => \Carbon\Carbon::parse($saleDetail->sale->date)->format('d-m-Y'),
                'Reference'      => $saleDetail->sale->reference,
                'Product Name'   => $saleDetail->product ? $saleDetail->product->name : 'N/A',
                'Product Code'   => $saleDetail->product ? $saleDetail->product->code : 'N/A',
                'Customer'       => $saleDetail->sale->customer ? $saleDetail->sale->customer->name : 'N/A',
                'Unit Price'     => $saleDetail->unit_price . ' $',
                'Discount'       => $saleDetail->discount . ' $',
                'Quantity'       => $saleDetail->quantity,
                'Total'          => $saleDetail->total_price . ' $',
                'Status'         => $saleDetail->sale->status,
                'Payment Status' => $saleDetail->sale->payment_status,
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
            'Customer',
            'Unit Price',
            'Discount',
            'Quantity',
            'Total',
            'Status',
            'Payment Status',
        ];
    }
}
