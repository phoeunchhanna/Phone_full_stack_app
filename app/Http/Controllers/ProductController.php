<?php
namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index'                 => 'បញ្ជីផលិតផល',
            'create'                => 'បង្កើតផលិតផល',
            'edit'                  => 'កែប្រែផលិតផល',
            'destroy'               => 'លុបផលិតផល',
            'exportProductsToExcel' => 'ទាញយកទិន្នន័យផលិតផល',
        ];

        foreach ($permissions as $method => $permission) {
            $this->middleware(function ($request, $next) use ($permission) {
                if (! auth()->user()->can($permission)) {
                    return back()->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
                }
                return $next($request);
            })->only($method);
        }
    }

    public function index()
    {
        $products   = Product::with('category', 'brand')->orderBy('id', 'desc')->get();
        $categories = Category::all();
        $brands     = Brand::all();
        $stocks     = Stock::all();
        return view('admin.products.index', compact('products', 'categories', 'brands', 'stocks'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands     = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }
    public function store(Request $request)
    {
        // Validate input fields
        $request->validate([
            'name'          => ['required', 'string'],
            'code'          => 'nullable|string|unique:products,code',
            'cost_price'    => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_alert'   => 'required|integer',
            'image'         => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'condition'     => 'required|string',
            'description'   => 'nullable|string',
            'category_id'   => 'required|integer',
            'brand_id'      => 'nullable|integer',
        ], [
            'name.required'      => 'សូមបញ្ចូលឈ្មោះផលិតផល។',
            'condition.required' => 'សូមបញ្ចូលស្ថានភាពផលិតផល។',
            'code.unique'        => 'បាកូដនេះមានរួចហើយ។',
        ]);

        $existingProduct = Product::where('name', $request->name)
            ->where('condition', $request->condition)
            ->first();

        if ($existingProduct) {
            return back()->with('error', 'ផលិតផលនេះមានរួចហើយ។')->withInput();
        }

        // Create new Product
        $product = new Product();

        // Handle image upload
        if ($request->hasFile('image')) {
            $fileName       = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath      = $request->file('image')->storeAs('uploads', $fileName, 'public');
            $product->image = $imagePath;
        } else {
            $product->image = 'uploads/default.png';
        }

        // Store product data
        $product->code          = $request->code ?? str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
        $product->name          = $request->name;
        $product->cost_price    = $request->cost_price;
        $product->selling_price = $request->selling_price;
        $product->quantity      = $request->quantity ?? 0;
        $product->stock_alert   = $request->stock_alert;
        $product->condition     = $request->condition;
        $product->description   = $request->description ?? 'N/A';
        $product->category_id   = $request->category_id;
        $product->brand_id      = $request->brand_id;

        // Save the product
        $product->save();

        // Save stock data
        Stock::create([
            'product_id' => $product->id,
            'last_stock' => $request->input('last_stock', 0),
            'date'       => now(),
            'purchase'   => 0,
            'current'    => $request->input('last_stock', 0),
        ]);

        return redirect()->route('products.index')->with('success', 'ទិន្នន័យបន្ថែមដោយជោគជ័យ។');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name'          => ['required', 'string', Rule::unique('products', 'name')],
    //         'code'          => 'nullable|string|unique:products,code',
    //         'cost_price'    => 'required|numeric',
    //         'selling_price' => 'required|numeric',
    //         'stock_alert'   => 'required|integer',
    //         'image'         => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
    //         'condition'     => 'nullable|string',
    //         'description'   => 'nullable|string',
    //         'category_id'   => 'required|integer',
    //         'brand_id'      => 'nullable|integer',
    //     ], [
    //         'name.unique' => 'ផលិតផលនេះមានរួចហើយ។',
    //         'code.unique' => 'បាកូដនេះមានរួចហើយ។',
    //     ]);

    //     // Create new Product and Stock instances
    //     $product = new Product();
    //     $stocks  = new Stock();

    //     // Handle image upload
    //     if ($request->hasFile('image')) {
    //         $fileExtension  = $request->file('image')->getClientOriginalExtension();
    //         $fileName       = time() . '.' . $fileExtension;
    //         $imagePath      = $request->file('image')->storeAs('uploads', $fileName, 'public');
    //         $product->image = $imagePath;
    //     } else {
    //         $product->image = 'uploads/default.png';
    //     }

    //     // Store product data
    //     $product->code          = $request->code ?? str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
    //     $product->name          = $request->name;
    //     $product->cost_price    = $request->cost_price;
    //     $product->selling_price = $request->selling_price;
    //     $product->quantity      = $request->quantity ?? 0;
    //     $product->stock_alert   = $request->stock_alert;
    //     $product->condition     = $request->condition ?? 'ថ្មី';
    //     $product->description   = $request->description ?? 'N/A';
    //     $product->category_id   = $request->category_id;
    //     $product->brand_id      = $request->brand_id;

    //     // Save the product
    //     $product->save();

    //     // Save stock data
    //     $stocks->product_id = $product->id;
    //     $stocks->last_stock = $request->input('last_stock', 0);
    //     $stocks->date       = now();
    //     $stocks->purchase   = 0;
    //     $stocks->current    = $stocks->purchase + $stocks->last_stock;
    //     $stocks->save();

    //     // Redirect with success message
    //     return redirect()->route('products.index')->with('success', 'ទិន្នន័យបន្ថែមដោយជោគជ័យ។');
    // }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands     = Brand::all();
        $stock      = Stock::where('product_id', $product->id)->first();
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'stock'));
    }

    public function show(Product $product)
    {
        $categories = Category::all();
        $brands     = Brand::all();
        $stock      = $product->stock;

        return view('admin.products.show', compact('product', 'categories', 'brands', 'stock'));
    }

    public function update(Request $request, Product $product)
    {
        // Validate the input data
        $request->validate([
            'name'          => ['required', 'string'],
            'cost_price'    => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_alert'   => 'required|integer',
            'condition'     => 'required|string',
            'description'   => 'nullable|string',
            'category_id'   => 'required|integer',
            'brand_id'      => 'nullable|integer',
            'image'         => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ], [
            'name.required'      => 'សូមបញ្ចូលឈ្មោះផលិតផល។',
            'condition.required' => 'សូមបញ្ចូលស្ថានភាពផលិតផល។',
        ]);

        $existingProduct = Product::where('name', $request->name)
            ->where('condition', $request->condition)
            ->where('id', '!=', $product->id) // Exclude the current product
            ->exists();

        if ($existingProduct) {
            return back()->with('error', 'ផលិតផលនេះមានរួចហើយ។')->withInput();
        }

        if ($request->hasFile('image')) {
            if ($product->image && $product->image !== 'uploads/default.png') {
                Storage::disk('public')->delete($product->image);
            }

            $fileExtension  = $request->file('image')->getClientOriginalExtension();
            $fileName       = time() . '.' . $fileExtension;
            $imagePath      = $request->file('image')->storeAs('uploads', $fileName, 'public');
            $product->image = $imagePath;
        }
        
        // Update product data
        $product->update([
            'name'          => $request->name,
            'cost_price'    => $request->cost_price,
            'selling_price' => $request->selling_price,
            'stock_alert'   => $request->stock_alert,
            'condition'     => $request->condition,
            'description'   => $request->description ?? 'N/A',
            'category_id'   => $request->category_id,
            'brand_id'      => $request->brand_id,
        ]);

        $stock = Stock::where('product_id', $product->id)->first();

        if ($stock) {
            $stock->update([
                'last_stock' => $request->input('last_stock', 0),
                'current'    => $stock->purchase + $request->input('last_stock', 0),
            ]);
        }
        
        return redirect()->route('products.index')->with('success', 'ទិន្នន័យត្រូវបានកែប្រែដោយជោគជ័យ');
    }
    public function destroy(Product $product)
    {
        $stock = Stock::where('product_id', $product->id)->first();
        // Delete Stock data
        if ($stock) {
            $stock->delete();
        }
        // Delete Product Image
        if ($product->image && $product->image !== 'uploads/default.png') {
            Storage::disk('public')->delete($product->image);
        }
        // Delete Product
        $product->delete();

        return redirect()->route('products.index')->with('success', 'ផលិតផលត្រូវបានលុបជោគជ័យ។.');
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
        $brandName    = Brand::find($request->brand_id)?->name ?? 'AllBrands';
        $fileName     = "products_{$categoryName}_{$brandName}_" . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ProductExport, $fileName);
    }

    public function search(Request $request)
    {
        $term     = $request->get('term', '');
        $products = Product::where('name', 'like', "%$term%" . $request->search . "%$term%")
            ->orWhere('code', 'like', "%$term%" . $request->search . "%$term%")
            ->limit(5)
            ->get();
        return response()->json($products);
    }
}
