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
        // ✅ Default to last 7 days if no date range is provided
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
            // ✅ Default to last 7 days
            $startDate = Carbon::now()->subDays(7)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }

        // ✅ Calculate Total Revenue (Sales - Sale Returns)
        $totalRevenue = Sale::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        $totalReturns = SaleReturn::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        $netRevenue = $totalRevenue - $totalReturns;

        // ✅ Calculate Total Expenses (Purchases + Other Expenses)
        $totalPurchases = Purchase::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $totalCost = $totalPurchases + $totalExpenses;

        // ✅ Calculate Profit/Loss
        $profitLoss = $netRevenue - $totalCost;

        return view('admin.reports.profit-loss', compact(
            'startDate', 'endDate', 'totalRevenue', 'totalReturns', 'netRevenue', 'totalPurchases', 'totalExpenses', 'totalCost', 'profitLoss'
        ));
    }
}
