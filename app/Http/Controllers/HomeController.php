<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\SaleReturn;
use App\Models\Customer;
use App\Models\Supplier;

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
    public function index()
    {
        // គណនាចំណូលដោយដកការបង្វែត្រឡប់ចេញ
        $totalRevenue = Sale::sum('total_amount') - SaleReturn::sum('total_amount');
        
        // គណនាចំណាយសរុប (ការទិញ + ចំណាយផ្សេងៗ)
        $totalExpenses = Purchase::sum('total_amount') + Expense::sum('amount');

        // គណនាចំណេញ/ខាត (Rename to $totalProfit)
        $totalProfit = $totalRevenue - $totalExpenses;

        // គណនាចំនួនប្រតិបត្តិការសំខាន់ៗ
        $totalSales = Sale::count();
        $totalReturns = SaleReturn::count();
        $totalPurchases = Purchase::count();
        $totalCustomers = Customer::count();
        $totalSuppliers = Supplier::count();

        return view('home', compact(
            'totalRevenue',
            'totalExpenses',
            'totalProfit', // Fixed variable name
            'totalSales',
            'totalReturns',
            'totalPurchases',
            'totalCustomers',
            'totalSuppliers'
        ));
    }
    // public function index()
    // {
    //     $totalProducts = DB::table('products')->count();
    //     $totalRevenue  = SalePayment::sum('amount');
    //     $totalExpenses =  Expense::sum('amount') + PurchasePayment::sum('amount') + SaleReturnPayment::sum('amount');
    //     $totalProfit = $totalRevenue - $totalExpenses;
    //     return view('home', compact('totalProducts', 'totalRevenue', 'totalExpenses', 'totalProfit'));
    // }
    public function userhome()
    {
        return view('userhome');
    }
}
