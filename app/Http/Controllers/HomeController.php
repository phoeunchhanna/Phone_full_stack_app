<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\SalePayment;
use App\Models\SaleReturnPayment;
use Illuminate\Support\Facades\DB;

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
        $totalProducts = DB::table('products')->count();
        $totalRevenue  = SalePayment::sum('amount');
        $totalExpenses =  Expense::sum('amount') + PurchasePayment::sum('amount') + SaleReturnPayment::sum('amount');
        $totalProfit = $totalRevenue - $totalExpenses;
        return view('home', compact('totalProducts', 'totalRevenue', 'totalExpenses', 'totalProfit'));
    }
    //     // Total Products and Customers
    //     $totalProducts  = DB::table('products')->count();

    //     $income = DB::table('sale_details')->sum('total_price');
    //     $Revenue   = DB::table('sales')->sum(DB::raw('paid_amount'));

    //     // Total Expenses
    //     $expence = DB::table('expenses')->sum('amount');
    //     $totalExpenses = DB::table('purchase_payments')->sum(DB::raw('amount')) + $expence;

    //     $totalRevenue      = DB::table('sale_details')->sum(DB::raw('total_price'));
    //     $totalSalePayments = DB::table('sale_payments')->sum('amount');

    //     $totalProfit = $totalRevenue - $totalExpenses;

    //     // Pass the data to the view
    //     return view('home', compact('totalProducts', 'totalExpenses', 'totalProfit', 'totalRevenue'));

    // }

    public function userhome()
    {
        return view('userhome');
    }
}
