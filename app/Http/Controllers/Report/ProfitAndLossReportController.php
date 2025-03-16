<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\Purchase;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitAndLossReportController extends Controller
{
    public function profitLoss(Request $request)
    {
        // Get start and end date from request (default: current month)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Calculate total revenue (Sales - Sale Returns)
        $totalRevenue = Sale::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        $totalReturns = SaleReturn::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        $netRevenue = $totalRevenue - $totalReturns;

        // Calculate total expenses (Purchases + Other Expenses)
        $totalPurchases = Purchase::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $totalCost = $totalPurchases + $totalExpenses;

        // Calculate profit/loss
        $profitLoss = $netRevenue - $totalCost;

        return view('admin.reports.profit-loss', compact(
            'startDate', 'endDate', 'totalRevenue', 'totalReturns', 'netRevenue', 'totalPurchases', 'totalExpenses', 'totalCost', 'profitLoss'
        ));
    }
}
