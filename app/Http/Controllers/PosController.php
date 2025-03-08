<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PosController extends Controller
{
    public function __construct()
    {
        $permissions = [
            'index' => 'ផ្ទាំងលក់ផលិតផល',
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
        $categories = Category::all();
        $customers = Customer::all();
        $products = Product::all();
        $stocks = Stock::all();
        Session::forget('cart');
        return view('admin.pos.pos_screen', compact('products', 'customers', 'categories', 'stocks'));
    }
    public function fetchProducts(Request $request)
    {
        $offset = $request->input('offset', 0); // Start point
        $limit = 15; // Number of products to fetch

        $products = Product::skip($offset)->take($limit)->get();

        return response()->json($products);
    }

    public function searchByCategory($categoryName)
    {
        if ($categoryName == 'All') {
            // Fetch all products and limit to 15
            $products = Product::take(15)->get();
        } else {
            // Fetch products by category and limit to 15
            $products = Product::whereHas('category', function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })->take(15)->get();
        }

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        $totalAmount = collect($cart)->sum(fn($item) => $item['quantity'] * $item['price']);
        $totalDiscount = collect($cart)->sum(fn($item) => $item['discount'] ?? 0);
        $paidAmount = $request->input('paid_amount', 0);

        $dueAmount = $totalAmount - $totalDiscount - $paidAmount;
        $paymentStatus = $dueAmount <= 0 ? 'បង់រួច' : ($paidAmount > 0 ? 'បង់ខ្លះ' : 'មិនទាន់បង់');

        $sale = Sale::create([
            'date' => $request->date,
            'reference' => 'SL-' . now()->format('YmdHis'),
            'user_id' => Auth::id(),
            'customer_id' => $request->customer_id,
            'total_amount' => $totalAmount,
            'discount' => $totalDiscount,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'payment_status' => $paymentStatus,
        ]);

        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total' => $item['quantity'] * $item['price'],
            ]);
            $product->decrement('quantity', $item['quantity']);
        }

        session()->forget('cart');
        return redirect()->route('sales.print-pos', $sale->id)->with([
            'message' => 'Sale created successfully!',
            'alert-type' => 'success',
        ]);
    }
    // public function search(Request $request)
    // {
    //     $query = $request->query('query');
    //     $products = Product::where('name', 'like', "%$query%")
    //         ->orWhere('barcode', 'like', "%$query%")
    //         ->limit(10)
    //         ->get();

    //     return response()->json(view('partials.product_list', compact('products'))->render());
    // }

    // public function getDetails(Request $request)
    // {
    //     $product = Product::findOrFail($request->id);
    //     return response()->json($product);
    // }

    // public function view_cashinhand()
    // {
    //     return view('admin.pos.cash_in_hand');
    // }
    // public function index()
    // {
    //     $customers = Customer::all();
    //     $categories = Category::all();
    //     $carts = Cart::all();
    //     $products = Product::all();
    //     $global_discount = 10;
    //     $discountAmount = 0;
    //     foreach ($carts as $cart) {
    //         $discountAmount += ($cart->product->selling_price * $global_discount) / 100;
    //     }

    //     return view('admin.pos.pos_screen', compact('categories', 'products', 'carts', 'customers', 'global_discount', 'discountAmount'));
    // }
    // public function barcodescan(Request $request)
    // {
    //     $product = Product::where('barcode', $request->productCode)->first();
    //     $userId = auth()->id();
    //     $carts = Cart::where('user_id', $userId)->get();
    //     $itemQuantityInCart = $carts->where('product_id', $product->id)->sum('quantity');

    //     // Check stock availability
    //     if ($product->quantity <= $itemQuantityInCart) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Product is out of stock!',
    //         ]);
    //     }
    //     // Check if the product already exists in the cart
    //     $cartItem = $carts->where('name', $product->name)->first();
    //     if ($cartItem) {
    //         if ($itemQuantityInCart < $product->quantity) {
    //             $cartItem->increment('quantity');
    //             return response()->json([
    //                 'status' => 200,
    //                 'carts' => Cart::where('user_id', $userId)->get(),
    //                 'message' => 'Cart updated Quantity successfully',
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 300,
    //                 'message' => 'Product limit reached',
    //             ]);
    //         }
    //     } else {
    //         $cart = Cart::create([
    //             'product_id' => $product->id,
    //             'user_id' => $userId,
    //             'price' => $product->selling_price,
    //             'quantity' => 1,
    //             'stock' => $product->quantity,
    //             'name' => $product->name,
    //         ]);
    //         return response()->json([
    //             'status' => 200,
    //             'carts' => Cart::where('user_id', $userId)->get(),
    //             'message' => 'Product added to cart successfully',
    //         ]);
    //     }
    // }

    // public function search(Request $request)
    // {
    //     $products = Product::where('name', 'like', '%' . $request->search . '%')->get();

    //     return json_encode($products);
    // }

    // public function loadMore(Request $request)
    // {
    //     $offset = $request->input('offset', 0);
    //     $limit = $request->input('limit', 8);
    //     $products = Product::skip($offset)->take($limit)->get();
    //     return response()->json(['products' => $products]);
    // }
    // public function filterProducts(Request $request)
    // {
    //     $query = Product::query();

    //     // Filter by search term
    //     if ($request->has('search') && !empty($request->search)) {
    //         $query->where('name', 'LIKE', '%' . $request->search . '%');
    //     }

    //     // Limit the number of products based on showCount
    //     if ($request->has('showCount') && !empty($request->showCount)) {
    //         $query->limit($request->showCount);
    //     }

    //     $products = $query->get();

    //     return response()->json(['products' => $products]);
    // }

}
