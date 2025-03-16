<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesReportExport implements FromCollection, WithHeadings
{
    protected $purchases;

    public function __construct($purchases)
    {
        $this->purchases = $purchases;
    }

    public function collection()
    {
        return $this->purchases->map(function ($purchase) {
            return [
                'កាលបរិច្ឆេទ'      => $purchase->purchase->date ?? 'N/A',
                'លេខយោង'          => $purchase->purchase->reference ?? 'N/A',
                'ឈ្មោះផលិតផល'      => $purchase->product->name ?? 'N/A',
                'លេខកូដផលិតផល'    => $purchase->product->code ?? 'N/A',
                'អ្នកផ្គត់ផ្គង់'      => $purchase->purchase->supplier->name ?? 'N/A',
                'បរិមាណ'           => $purchase->quantity,
                'តម្លៃឯកតា'        => $purchase->unit_price,
                'បញ្ចុះតម្លៃ'       => $purchase->discount ?? 0,
                'សរុប'              => ($purchase->quantity * $purchase->unit_price) - ($purchase->discount ?? 0),
                'វិធីបង់ប្រាក់'      => ucfirst($purchase->purchase->payment_method ?? 'N/A'),
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
            'អ្នកផ្គត់ផ្គង់',
            'បរិមាណ',
            'តម្លៃឯកតា',
            'បញ្ចុះតម្លៃ',
            'សរុប',
            'វិធីបង់ប្រាក់',
        ];
    }
}
