<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\SaleReturn;
use App\Models\Customer;
use App\Models\Supplier;
use Carbon\Carbon;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $permissions = [
            // 'index' => 'ផ្ទាំទំព័រដើម',
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
    // public function index()
    // {
    //     // គណនាចំណូលដោយដកការបង្វែត្រឡប់ចេញ
    //     $totalRevenue = Sale::sum('total_amount') - SaleReturn::sum('total_amount');
        
    //     // គណនាចំណាយសរុប (ការទិញ + ចំណាយផ្សេងៗ)
    //     $totalExpenses = Purchase::sum('total_amount') + Expense::sum('amount');

    //     // គណនាចំណេញ/ខាត (Rename to $totalProfit)
    //     $totalProfit = $totalRevenue - $totalExpenses;

    //     // គណនាចំនួនប្រតិបត្តិការសំខាន់ៗ
    //     $totalSales = Sale::count();
    //     $totalReturns = SaleReturn::count();
    //     $totalPurchases = Purchase::count();
    //     $totalCustomers = Customer::count();
    //     $totalSuppliers = Supplier::count();

    //     return view('home', compact(
    //         'totalRevenue',
    //         'totalExpenses',
    //         'totalProfit', // Fixed variable name
    //         'totalSales',
    //         'totalReturns',
    //         'totalPurchases',
    //         'totalCustomers',
    //         'totalSuppliers'
    //     ));
    // }
    public function index(Request $request)
    {
        // ✅ Default date range: last 7 days
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                } catch (\Exception $e) {
                    return back()->withErrors(['date_range' => 'Invalid date format.']);
                }
            }
        } else {
            $startDate = Carbon::now()->subDays(7)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }

        // ✅ Calculate revenue, expenses, and profit within the date range
        $totalRevenue = Sale::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        // $totalReturns = SaleReturn::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        $netRevenue = $totalRevenue ;

        $totalExpenses = Purchase::whereBetween('date', [$startDate, $endDate])->sum('total_amount') 
                          + Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');

        $totalProfit = $netRevenue - $totalExpenses;

        // ✅ Retrieve statistics within date range
        $totalSales = Sale::whereBetween('date', [$startDate, $endDate])->count();
        $totalReturns = SaleReturn::whereBetween('date', [$startDate, $endDate])->count();
        $totalPurchases = Purchase::whereBetween('date', [$startDate, $endDate])->count();
        $totalCustomers = Customer::count(); // All-time
        $totalSuppliers = Supplier::count(); // All-time

        return view('home', compact(
            'startDate', 'endDate', 'totalRevenue', 'totalReturns', 'netRevenue',
            'totalExpenses', 'totalProfit', 'totalSales', 'totalReturns', 
            'totalPurchases', 'totalCustomers', 'totalSuppliers'
        ));
    }
    // public function userhome()
    // {
    //     return view('userhome');
    // }
}
