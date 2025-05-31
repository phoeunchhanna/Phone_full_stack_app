<?php

namespace App\Http\Controllers\Report;

use App\Exports\SaleReturnReportExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleReturnDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SaleReturnReportController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $products  = Product::all();
        $returns     = []; // ទុកអោយអថិ.ចាប់ផ្តើមគ្មានទិន្នន័យ

        // return view('admin.reports.purchase-report', compact('returns', 'customers', 'products'));
        // $startDate = Carbon::now()->subDays(7)->startOfDay();
        // $endDate   = Carbon::now()->endOfDay();

        // $returns = SaleReturnDetail::whereHas('saleReturn', function ($query) use ($startDate, $endDate) {
        //     $query->whereBetween('date', [$startDate, $endDate]);
        // })->with(['saleReturn', 'product'])
        //   ->orderByDesc('created_at')
        //   ->get();

        return view('admin.reports.sale-return-report', compact('returns', 'customers', 'products'));
    }

    public function SaleReturnReport(Request $request)
    {
        $customers = Customer::all();
        $products  = Product::all();
        $query     = SaleReturnDetail::query();

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                    $query->whereHas('saleReturn', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date range format.']);
                }
            }
        }

        // if ($request->filled('customer_id')) {
        //     $query->where('customer_id', $request->customer_id);
        // }

        // $returns = $query->with(['saleReturn', 'product'])->get();
        // ✅ Fixed: Filter by Customer
        if ($request->filled('customer_id')) {
            $query->whereHas('saleReturn', function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            });
        }

        $returns = $query->with(['saleReturn', 'product'])->get();

        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new SaleReturnReportExport($returns), 'sale_return_report.xlsx');
        }

        return view('admin.reports.sale-return-report', compact('returns', 'customers', 'products'));
    }

    public function printReport(Request $request)
    {
        $customers = Customer::all();
        $products  = Product::all();
        $query     = SaleReturnDetail::query();

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                    $query->whereHas('saleReturn', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date range format.']);
                }
            }
        }

        $returns = $query->with(['saleReturn.customer', 'product'])->get();
        return view('admin.reports.sale-return-report-print', compact('returns', 'customers', 'products', 'request'));
    }

    public function exportReport(Request $request)
    {
        $query = SaleReturnDetail::query();

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                    $query->whereHas('saleReturn', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date range format.']);
                }
            }
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $returns = $query->with(['saleReturn', 'product'])->get();

        return Excel::download(new SaleReturnReportExport($returns), 'sale_return_report_' . now()->format('d-m-Y') . '.xlsx');
    }
}
