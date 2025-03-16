<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Exports\PurchasesReportExport;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PurchasesReportController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        $products  = Product::all();
        $purchases     = []; // ទុកអោយអថិ.ចាប់ផ្តើមគ្មានទិន្នន័យ

        return view('admin.reports.purchase-report', compact('purchases', 'suppliers', 'products'));
    }
    public function PurchaseReport(Request $request)
    {
        $suppliers = Supplier::all();
        $products  = Product::all();
        $query     = PurchaseDetail::query();

        // Filter by Date Range
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (is_array($dates) && count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                    // Ensure proper relationship filtering
                    $query->whereHas('purchase', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date range format.']);
                }
            }
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->whereHas('purchase', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        // Filter by Payment Method
        if ($request->filled('payment_method')) {
            $query->whereHas('purchase', function ($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        $purchases = $query->with(['purchase.supplier', 'product'])->get();

        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new PurchasesReportExport($purchases), 'purchases_report_' . now()->format('d-m-Y') . '.xlsx');
        }

        return view('admin.reports.purchase-report', compact('purchases', 'suppliers', 'products'));
    }

    public function printReport(Request $request)
    {
        $suppliers = Supplier::all();
        $products  = Product::all();
        $query     = PurchaseDetail::query();

        // Filter by Date Range
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (is_array($dates) && count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                    $query->whereHas('purchase', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date range format.']);
                }
            }
        }

        // Retrieve purchases data
        $purchases = $query->with(['purchase.supplier', 'product'])->get();

        return view('admin.reports.purchase-report-print', compact('purchases', 'suppliers', 'products', 'request'));
    }

    public function exportReport(Request $request)
    {
        $query = PurchaseDetail::query();

        // Filter by Date Range
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (is_array($dates) && count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                    $query->whereHas('purchase', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date range format.']);
                }
            }
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->whereHas('purchase', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        // Filter by Payment Method
        if ($request->filled('payment_method')) {
            $query->whereHas('purchase', function ($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        // Fetch filtered results
        $purchases = $query->with(['purchase', 'product'])->get();

        // Export to Excel
        return Excel::download(new PurchasesReportExport($purchases), 'purchases_report_' . now()->format('d-m-Y') . '.xlsx');
    }
}
