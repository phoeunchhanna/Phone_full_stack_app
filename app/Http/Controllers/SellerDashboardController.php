<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleReturn;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Apply permission middleware only if needed
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasAnyRole(['admin', 'seller'])) {
                return back()->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
            }
            return $next($request);
        });
        
    }

    public function index()
    {
        $sellerId = Auth::id();

        // Calculate statistics for the logged-in seller
        $totalRevenue = Sale::where('user_id', $sellerId)->sum('total_amount');
        $totalSales   = Sale::where('user_id', $sellerId)->count();
        $totalReturns = SaleReturn::whereHas('sale', function ($query) use ($sellerId) {
            $query->where('user_id', $sellerId);
        })->count();
        $totalCustomers = Customer::whereHas('sales', function ($query) use ($sellerId) {
            $query->where('user_id', $sellerId);
        })->count();

        // Fetch recent sales for the seller
        $recentSales = Sale::where('user_id', $sellerId)->latest()->limit(5)->get();

        return view('admin.seller.dashboard', compact(
            'totalRevenue', 'totalSales', 'totalReturns', 'totalCustomers', 'recentSales'
        ));
    }
}
