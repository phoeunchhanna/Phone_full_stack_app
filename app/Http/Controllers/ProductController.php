<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\variations;
use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីផលិតផល',
            'create' => 'បង្កើតផលិតផល',
            'edit' => 'កែប្រែផលិតផល',
            'destroy' => 'លុបផលិតផល',
            'exportProductsToExcel' => 'ទាញយកទិន្នន័យផលិតផល',
        ];

        foreach ($permissions as $method => $permission) {
            $this->middleware(function ($request, $next) use ($permission) {
                if (!auth()->user()->can($permission)) {
                    return back()->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
                }
                return $next($request);
            })->only($method);
        }
    }



    public function index()
    {
        $products = Product::with('category', 'brand')->orderBy('id', 'desc')->get();
        $categories = Category::all();
        $brands = Brand::all();
        $stocks = Stock::all();
        return view('admin.products.index', compact('products', 'categories', 'brands', 'stocks'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('products', 'name')],
            'code' => 'nullable|string|unique:products,code',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_alert' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status' => 'required|string',
            'condition' => 'nullable|string',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'brand_id' => 'nullable|integer',
        ], [
            'name.unique' => 'ផលិតផលនេះមានរួចហើយ។',
            'code.unique' => 'បាកូដនេះមានរួចហើយ។',
        ]);
        $product = new Product();
        $stocks = new Stock();

        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('uploads', 'public');
            $product->image = '/storage/' . $filePath;
        } else {
            $product->image = 'assets/img/defaults/image.png';
        }
         // Store variations
        $product->code = $request->code ?? str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
        $product->name = $request->name;
        $product->cost_price = $request->cost_price;
        $product->selling_price = $request->selling_price;
        $product->quantity =  $request->quantity ?? 0;
        $product->stock_alert = $request->stock_alert;
        $product->status = $request->status ?? 1;
        $product->condition = $request->condition ?? 'ថ្មី';
        $product->description = $request->description ?? 'គ្មាន';
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $product->save();



        $stocks->product_id = $product->id; // Associate stock with the newly created product
        $stocks->last_stock = $request->input('last_stock', 0);
        $stocks->date = now();
        $stocks->purchase = 0;
        $stocks->current =  $stocks->purchase + $stocks->last_stock ;
        $stocks->save();
        return redirect()->route('products.index')->with('success', 'ទិន្នន័យបន្ថែមដោយជោគជ័យ។');
    }

    // public function edit(Product $product)
    // {
    //     $categories = Category::all();
    //     $brands = Brand::all();
    //     $stocks = Stock::all();
    //     return view('admin.products.edit', compact('product', 'categories', 'brands', 'stocks'));
    // }


    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $stock = Stock::where('product_id', $product->id)->first();
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'stock'));
    }

    public function show(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $stock = $product->stock;  // Get the related Stock record for the specific product

        return view('admin.products.show', compact('product', 'categories', 'brands', 'stock'));
    }


    public function update(Request $request, Product $product)
    {
        // Validate the input data
        $request->validate([
            'name' => ['required', 'string', Rule::unique('products', 'name')->ignore($product->id)],
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_alert' => 'required|integer',
            'status' => 'required|string',
            'condition' => 'nullable|string',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'brand_id' => 'nullable|integer',
        ], [
            'name.unique' => 'ផលិតផលនេះមានរួចហើយ។',
        ]);

        if ($request->hasFile('image')) {
            if (File::exists(public_path('storage/' . $product->image))) {
                File::delete(public_path('storage/' . $product->image));
            }

            $filePath = $request->file('image')->store('uploads', 'public');
            $product->image = 'storage/' . $filePath;
        }

        $product->update([
            'code' => $request->code ?? $product->code,
            'name' => $request->name,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
            'stock_alert' => $request->stock_alert,
            'image' => isset($filename) ? $path . $filename : $product->image,
            'status' => $request->status,
            'condition' => $request->condition,
            'description' => $request->description ?? $product->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

        $stock = Stock::where('product_id', $product->id)->first();

        if ($stock) {
            // Update existing stock record
            $stock->update([
                'last_stock' => $request->input('last_stock', 0), // Default to 0 if no value provided
                'current' => $stock->purchase + $request->input('last_stock', 0), // Update current stock
            ]);
        }

        return redirect()->route('products.index')->with('success', 'ទិន្នន័យត្រូវបានកែប្រែដោយជោគជ័យ');
    }
    public function destroy(Product $product)
    {
        if (File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        $stock = Stock::where('product_id', $product->id)->first();
        if ($stock) {
            $stock->delete();
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'ទិន្នន័យត្រូវបានលុបដោយជោគជ័យ.');
    }


    public function filterProducts(Request $request)
    {
        $showCount = $request->input('showCount');

        $products = Product::query()
            ->when($showCount, function ($query, $showCount) {
                return $showCount ? $query->take($showCount) : $query;
            })
            ->get(['id', 'name', 'selling_price', 'quantity', 'image']);
        return response()->json($products);
    }
    public function exportProductsToExcel(Request $request)
    {
        $categoryName = Category::find($request->category_id)?->name ?? 'AllCategories';
        $brandName = Brand::find($request->brand_id)?->name ?? 'AllBrands';
        $fileName = "products_{$categoryName}_{$brandName}_" . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ProductExport, $fileName);
    }

    public function search(Request $request)
    {
        $term = $request->get('term', '');
        $products = Product::where('name', 'like', "%$term%" . $request->search . "%$term%")
            ->orWhere('code', 'like', "%$term%" . $request->search . "%$term%")
            ->limit(5)
            ->get();
        return response()->json($products);
    }
}
