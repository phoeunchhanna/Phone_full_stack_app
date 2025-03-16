<?php

namespace App\Http\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SaleDetail;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;

class SalesReport extends Component
{
    use WithPagination;

    public $date_range;
    public $customer_id;
    public $payment_method;
    public $payment_status;

    public function updating($property)
    {
        // Reset pagination when a filter is changed
        $this->resetPage();
    }

    public function render()
    {
        $customers = Customer::all();
        $products = Product::all();

        $query = SaleDetail::query()->with(['sale.customer', 'product']);

        // Handle Date Range
        if ($this->date_range) {
            $dates = explode(' to ', $this->date_range);
            if (count($dates) == 2) {
                $start_date = Carbon::parse($dates[0])->startOfDay();
                $end_date = Carbon::parse($dates[1])->endOfDay();

                $query->whereHas('sale', function ($query) use ($start_date, $end_date) {
                    $query->whereBetween('date', [$start_date, $end_date]);
                });
            }
        }

        // Filter by Customer
        if ($this->customer_id) {
            $query->whereHas('sale', function ($query) {
                $query->where('customer_id', $this->customer_id);
            });
        }

        // Filter by Payment Method
        if ($this->payment_method) {
            $query->whereHas('sale', function ($query) {
                $query->where('payment_method', $this->payment_method);
            });
        }

        // Filter by Payment Status
        if ($this->payment_status) {
            $query->whereHas('sale', function ($query) {
                $query->where('payment_status', $this->payment_status);
            });
        }

        // Paginate the results
        $salesDetails = $query->paginate(10);

        return view('livewire.reports.sales-report', compact('salesDetails', 'customers', 'products'));
    }
}
