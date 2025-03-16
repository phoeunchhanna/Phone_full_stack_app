<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportExport implements FromCollection, WithHeadings
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales->map(function ($sale) {
            return [
                'កាលបរិច្ឆេទ'      => $sale->sale->date ?? 'N/A',
                'លេខយោង'          => $sale->sale->reference ?? 'N/A',
                'ឈ្មោះផលិតផល'      => $sale->product->name ?? 'N/A',
                'លេខកូដផលិតផល'    => $sale->product->code ?? 'N/A',
                'អតិថិជន'          => $sale->sale->customer->name ?? 'N/A',
                'បរិមាណ'           => $sale->quantity,
                'តម្លៃឯកតា'        => $sale->unit_price,
                'បញ្ចុះតម្លៃ'       => $sale->discount ?? 0,
                'សរុប'              => ($sale->quantity * $sale->unit_price) - ($sale->discount ?? 0),
                'វិធីបង់ប្រាក់'      => ucfirst($sale->sale->payment_method ?? 'N/A'),
                'ស្ថានភាព'         => $sale->sale->status ?? 'N/A',
                'ស្ថានភាពទូទាត់'   => $sale->sale->payment_status ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'កាលបរិច្ឆេទ',
            'លេខយោង',
            'ឈ្មោះផលិតផល',
            'លេខកូដផលិតផល',
            'អតិថិជន',
            'បរិមាណ',
            'តម្លៃឯកតា',
            'បញ្ចុះតម្លៃ',
            'សរុប',
            'វិធីបង់ប្រាក់',
            'ស្ថានភាព',
            'ស្ថានភាពទូទាត់',
        ];
    }
}
