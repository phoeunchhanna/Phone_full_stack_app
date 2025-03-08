<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use App\Models\Expense as Expend;
use App\Models\Supplier;
use Carbon\Carbon;
use App\Exports\PurchasesReportExport;
use App\Exports\SalesReportExport;
use App\Exports\ProductsReportExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

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

    public function SaleReport(Request $request)
    {
        $customers = Customer::all();
        $products  = Product::all();      // If you want to filter by products as well
        $query     = SaleDetail::query(); // Query SaleDetail instead of Sale

        // Handle Date Range Input
        if ($request->has('date_range') && $request->date_range) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $start_date = $dates[0];
                $end_date   = $dates[1];

                $query->whereHas('sale', function ($query) use ($start_date, $end_date) {
                    $query->whereDate('date', '>=', $start_date)
                        ->whereDate('date', '<=', $end_date);
                });
            }
        }

        // Filter by Customer
        if ($request->filled('customer_id')) {
            $query->whereHas('sale', function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            });
        }

        // Filter by Payment Status
        if ($request->filled('payment_status')) {
            $query->whereHas('sale', function ($query) use ($request) {
                $query->where('payment_status', $request->payment_status);
            });
        }

        // Eager load the necessary relationships (sale, customer, product) and paginate results
        $salesDetails = $query->with(['sale.customer', 'product'])->paginate(10);
        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new SalesReportExport($query), 'sales_report.xlsx');
        }

        return view('admin.reports.sales-report', compact('salesDetails', 'customers', 'products'));
    }
    public function ProductReport(Request $request)
    {
        $categories = Category::all();
        $brands     = Brand::all();

        // Initialize the query builder for products
        $query = Product::query();

        // Filter by date range if both start_date and end_date are provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate   = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Filter by category if category_id is provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by brand if brand_id is provided
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        // Execute the query and paginate the results
        $products = $query->paginate(10);
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

        // Eager load the necessary relationships
        $purchasesDetails = $query->with(['purchase.supplier', 'product'])->get();

        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new PurchasesReportExport($query), 'purchases_report.xlsx');
        }
        return view('admin.reports.purchase-report', compact('suppliers', 'products', 'purchasesDetails'));
    }

}
