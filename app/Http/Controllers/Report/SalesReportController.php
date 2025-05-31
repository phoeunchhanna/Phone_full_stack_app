<?php
namespace App\Http\Controllers\Report;

use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $products  = Product::all();
        $sales     = [];

        return view('admin.reports.sales-report', compact('sales', 'customers', 'products'));
    }
    // public function index()
    // {
    //     $customers = Customer::all();
    //     $products  = Product::all();
    //     $startDate = Carbon::now()->subDays(7)->startOfDay();
    //     $endDate   = Carbon::now()->endOfDay();

    //     $sales = SaleDetail::whereHas('sale', function ($query) use ($startDate, $endDate) {
    //         $query->whereBetween('date', [$startDate, $endDate]);
    //     })->with(['sale', 'product'])
    //       ->orderByDesc('created_at') // Newest first
    //       ->get();

    //     return view('admin.reports.sales-report', compact('sales', 'customers', 'products'));
    // }

    public function SaleReport(Request $request)
    {
        $customers = Customer::all();
        $products  = Product::all();
        $query     = SaleDetail::query();
        // Filter by Date Range
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    // Parse the start and end dates
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                    // Filter by Sale's date field (assuming the relationship is defined correctly)
                    $query->whereHas('sale', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date range format.']);
                }
            }
        }

        // Filter by Customer
        // if ($request->filled('customer_id')) {
        //     $query->where('customer_id', $request->customer_id);
        // }

        // // Filter by Payment Status
        // if ($request->filled('payment_status')) {
        //     $query->where('payment_status', $request->payment_status);
        // }

        // // Filter by Payment Method
        // if ($request->filled('payment_method')) {
        //     $query->where('payment_method', $request->payment_method);
        // }

        // // $sales = $query->with(['customer', 'saleDetails.product'])->get();
        // $sales = $query->with(['sale', 'product'])->get();
        if ($request->filled('customer_id')) {
            $query->whereHas('sale', function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            });
        }
    
        // ✅ Corrected: Filter by Payment Status
        if ($request->filled('payment_status')) {
            $query->whereHas('sale', function ($query) use ($request) {
                $query->where('payment_status', $request->payment_status);
            });
        }
    
        // ✅ Corrected: Filter by Payment Method
        if ($request->filled('payment_method')) {
            $query->whereHas('sale', function ($query) use ($request) {
                $query->where('payment_method', $request->payment_method);
            });
        }
    
        $sales = $query->with(['sale', 'product'])->get();
    
        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new SalesReportExport($sales), 'sales.report.export.xlsx');
        }
        // return view('admin.reports.sales-report', compact('sales', 'customers', 'products'));
        return view('admin.reports.sales-report', compact('sales', 'customers', 'products'));
    }
    public function printReport(Request $request)
    {
        $customers = Customer::all();
        $products  = Product::all();
        $query     = SaleDetail::query();

        // Filter by Date Range
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                    $query->whereHas('sale', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date range format.']);
                }
            }
        }

        // Retrieve sales data
        $sales = $query->with(['sale.customer', 'product'])->get();
        return view('admin.reports.sales-report-print', compact('sales', 'customers', 'products', 'request'));
    }

    public function exportReport(Request $request)
    {
        $query = SaleDetail::query();

        // Filter by Date Range
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                    // Ensure the sale date falls within the selected range
                    $query->whereHas('sale', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date range format.']);
                }
            }
        }

        // Filter by Customer (if selected)
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by Payment Method
        if ($request->filled('payment_method')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        // Fetch filtered results
        $sales = $query->with(['sale', 'product'])->get();

        // Export to Excel
        return Excel::download(new SalesReportExport($sales), 'sales_report_' . now()->format('d-m-Y') . '.xlsx');
    }
}
