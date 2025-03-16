<?php
namespace App\Http\Controllers;

use App\Exports\ProductsReportExport;
use App\Exports\PurchasesReportExport;
use App\Exports\SalesReportExport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Expense as Expend;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'SaleReport'     => 'របាយការណ៍ការលក់ទំនិញ',
            'PurchaseReport' => 'របាយការណ៍ការទិញ',
            'ProfitAndLoss'  => 'របាយការណ៍ប្រាក់ចំណេញ និងខាត',
            'ProductReport'  => 'របាយការណ៍ផលិតផល',
        ];

        foreach ($permissions as $action => $permission) {
            $this->middleware(function ($request, $next) use ($permission) {
                if (! auth()->user()->can($permission)) {
                    return back()->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
                }
                return $next($request);
            })->only([$action]);
        }
    }

    // public function homeReport()
    // {
    //     return view('admin.reports.index');
    // }
    public function ProfitAndLoss(Request $request)
    {
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate   = Carbon::parse($dates[1])->endOfDay();
            }
        } else {
            $startDate = Carbon::now()->startOfMonth()->startOfDay();
            $endDate   = Carbon::now()->endOfMonth()->endOfDay();
        }
        //ចំណូលសរុប = ប្រាក់បានពីការលក់ - ការលក់ត្រឡប់ + ប្រាក់ដែលបានទូទាត់ពីអតិថិជន
        $revenue = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('paid_amount')
         - SaleReturn::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount')
         + SalePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount');

        //ចំណាយសរុប = ប្រាក់ទិញទំនិញ + ការចំណាយផ្សេងៗ - ការបង់ប្រាក់
        $expense = Purchase::whereBetween('created_at', [$startDate, $endDate])->sum('paid_amount')
         + Expend::whereBetween('created_at', [$startDate, $endDate])->sum('amount')
         - PurchasePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount');

        //ចំណេញសរុប = ចំណូល - ចំណាយ
        $grossProfit = $revenue - Purchase::whereBetween('created_at', [$startDate, $endDate])->sum('paid_amount');

        $netProfit = $grossProfit - Expend::whereBetween('created_at', [$startDate, $endDate])->sum('amount')
         + SalePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount')
         - PurchasePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount');

        // Calculate total sales (Income)
        $salesAmount = SalePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $totalSales  = Sale::whereBetween('created_at', [$startDate, $endDate])->count();

        // Calculate total purchases
        $purchasesAmount = PurchasePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $totalPurchases  = Purchase::whereBetween('created_at', [$startDate, $endDate])->count();

        //ចំណូល
        $salesAmount = SalePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount');

        // ចំណាយ
        $expensesAmount  = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $purchasesAmount = PurchasePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $expends         = $expensesAmount + $purchasesAmount;

        // ប្រាក់ចំណេញ
        $profitAmount = $salesAmount - $expends;

        // Calculate total expenses (Expenses)
        $expensesAmount = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');

        // Calculate profit
        $sales      = Sale::with('saleDetails.product')->whereBetween('created_at', [$startDate, $endDate])->get();
        $cost_price = 0;
        foreach ($sales as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                $cost_price += $saleDetail->product->cost_price;
            }
        }
        $profitAmount = $salesAmount - $cost_price;

        // Payments received and sent
        $paymentsReceivedAmount = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('paid_amount');
        $paymentsSentAmount     = Purchase::whereBetween('created_at', [$startDate, $endDate])->sum('paid_amount') + $expensesAmount;
        $paymentsNetAmount      = $paymentsReceivedAmount - $paymentsSentAmount;

        return view('admin.reports.profit_loss', [
            'sales_amount'             => $salesAmount, // Income (ចំណូល)
            'total_sales'              => $totalSales,
            'purchases_amount'         => $purchasesAmount,
            'total_purchases'          => $totalPurchases,
            'expenses_amount'          => $expensesAmount, // Expenses (ចំណាយ)
            'profit_amount'            => $profitAmount,
            'payments_received_amount' => $paymentsReceivedAmount,
            'payments_sent_amount'     => $paymentsSentAmount,
            'payments_net_amount'      => $paymentsNetAmount,
            'date_range'               => $request->date_range,
        ]);
    }

   

    public function ProductReport(Request $request)
    {
        $categories = Category::all();
        $brands     = Brand::all();

        $query = Product::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate   = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by brand if brand_id is provided
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        $products = $query->get();
        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new ProductsReportExport($query), 'products_report.xlsx');
        }
        return view('admin.reports.products-report', compact('products', 'categories', 'brands'));
    }
    public function PurchaseReport(Request $request)
    {
        $suppliers = Supplier::all();
        $products  = Product::all();

        $query = PurchaseDetail::query();

        // Handle Date Range Input
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate   = Carbon::parse($dates[1])->endOfDay();
                $query->whereHas('purchase', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            }
        }

        // Filter by Supplier
        if ($request->filled('supplier_id')) {
            $query->whereHas('purchase', function ($query) use ($request) {
                $query->where('supplier_id', $request->supplier_id);
            });
        }

        $purchasesDetails = $query->with(['purchase.supplier', 'product'])->get();

        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new PurchasesReportExport($query), 'purchases_report.xlsx');
        }
        return view('admin.reports.purchase-report', compact('suppliers', 'products', 'purchasesDetails'));
    }
    public function ExpenseReport(Request $request)
    {
        $expenses = Expend::query();

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate   = Carbon::parse($dates[1])->endOfDay();
                $expenses->whereBetween('date', [$startDate, $endDate]);
            }
        }

        $expenses = $expenses->orderBy('id', 'desc')->get();

        return view('admin.reports.expense-report', compact('expenses'));
    }
    public function SaleReturnReport(Request $request)
    {
        $customers = Customer::all();
        $products  = Product::all();
        $query     = SaleReturnDetail::query();

        // Handle Date Range Input
        if ($request->has('date_range') && $request->date_range) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $start_date = Carbon::parse($dates[0])->startOfDay();
                    $end_date   = Carbon::parse($dates[1])->endOfDay();

                    $query->whereHas('saleReturn', function ($q) use ($start_date, $end_date) {
                        $q->whereBetween('date', [$start_date, $end_date]);
                    });
                } catch (\Exception $e) {
                    return back()->with('error', 'Invalid date format.');
                }
            }
        }

        // Filter by Customer
        if ($request->filled('customer_id')) {
            $query->whereHas('saleReturn', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
        }

        // Filter by Payment Status
        if ($request->filled('payment_status')) {
            $query->whereHas('saleReturn', function ($q) use ($request) {
                $q->where('payment_status', $request->payment_status);
            });
        }

        // Fetch Sale Return Details with related models
        $salesDetails = $query->with(['saleReturn.customer', 'product'])->get();

        // Export to Excel if requested
        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new SalesReportExport($salesDetails), 'sale_return_report.xlsx');
        }

        return view('admin.reports.sale_return-report', compact('salesDetails', 'customers', 'products'));
    }
    public function PaymentReport(Request $request)
    {

    }

}
