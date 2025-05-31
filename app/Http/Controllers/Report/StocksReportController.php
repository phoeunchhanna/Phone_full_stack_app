<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StocksReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['product.brand', 'product.category']);

        // Filter by Product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by Brand
        if ($request->filled('brand_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            });
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $stocks     = $query->latest()->paginate(10);
        $products   = Product::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.reports.stock-report', compact('stocks', 'products', 'brands', 'categories'));
    }
}
