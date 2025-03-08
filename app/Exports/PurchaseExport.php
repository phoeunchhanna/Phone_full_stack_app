<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Purchase::query()->with(['supplier', 'purchaseDetails.product']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Reference',
            'Supplier Name',
            'Total Amount',
            'Paid Amount',
            'Due Amount',
            'Status',
            'Payment Status',
            'Description',
        ];
    }

    public function map($purchase): array
    {
        return [
            $purchase->id,
            $purchase->date,
            $purchase->reference,
            $purchase->supplier?->name ?? 'N/A', // Get supplier name
            $purchase->total_amount,
            $purchase->paid_amount,
            $purchase->due_amount,
            $purchase->status ? 'Completed' : 'Pending',
            $purchase->payment_status,
            $purchase->description,
        ];
    }
}
