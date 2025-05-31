<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\SaleDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function userhome()
    {

        // Get all products with quantity > 0 and include stock_alert
        $products = Product::where('quantity', '>', 0)->get();

        // Categories with product count
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('quantity', '>', 0);
        }])->get();

        // Brands with their products (quantity > 0 and stock_alert > 0)
        $brands = Brand::with(['products' => function ($query) {
            $query->where('quantity', '>', 0);
        }])->get();

        // Top products based on selling price
        $topProducts = Product::where('quantity', '>', 0)
            ->orderByDesc('selling_price')
            ->take(3)
            ->get();

        return view('userhome', compact('products', 'topProducts', 'categories', 'brands'));
    }

    public function pro_show($id)
    {
        $pro_shows = Product::findOrFail($id);
        $products = Product::all();
        $brands = Brand::all();
        $categories = Category::all();

        return view('product_show', compact('pro_shows', 'products', 'brands', 'categories'));
    }

    public function pro_list_show($id)
    {

        $product = Product::with(['brand', 'category'])->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->where('quantity', '>', 0)
            ->take(4)
            ->get();
        return view('loyouts_user.product_list.product_sale_list', compact('product', 'relatedProducts'));
    }


    // fillter product by brands
    public function productsByBrand($id)
    {
        $brand = Brand::findOrFail($id);
        $products = Product::where('brand_id', $id)
            ->where('quantity', '>', 0)
            ->get();

        $categories = Category::all();
        $brands = Brand::all();

        return view(
            'loyouts_user.product_list.Product_by_brand',
            compact('brand', 'products', 'brands', 'categories')
        );
    }

    // fillter product by category 
    public function productsByCategory($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::where('category_id', $id)
            ->where('quantity', '>', 0)
            ->get();
        $categories = Category::all();
        $brands = Brand::all();

        return view('loyouts_user.product_list.Product_by_category', compact('category', 'products', 'brands', 'categories'));
    }

    // add to cart 
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Generate public URL for image (e.g., /storage/products/apple.jpg)
            $imagePath = Storage::exists($product->image)
                ? Storage::url($product->image)
                : asset('image.png'); // fallback image if not found

            $cart[$id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->selling_price,
                'image' => $imagePath, // <<--- FIXED: Use the public URL
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

 

    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('cart', 'subtotal'));
    }
    /**
     * Process checkout and create sale
     */
    public function processCheckout(Request $request)
    {
        // Validate customer information
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'discount' => 'nullable|numeric|min:0|max:100', // Discount percentage (0-100)
            'discount_amount' => 'nullable|numeric|min:0', // Fixed discount amount
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }

        // Calculate subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Calculate discount (either percentage or fixed amount)
        $discount = 0;
        if ($request->filled('discount')) {
            $discount = ($subtotal * $request->discount) / 100;
        } elseif ($request->filled('discount_amount')) {
            $discount = min($request->discount_amount, $subtotal);
        }
        $total = $subtotal - $discount;

        // Create or find customer
        $customer = Customer::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => $request->name,
                'address' => $request->address,
            ]
        );

        // Create the sale
        $sale = Sale::create([
            'date' => now(),
            'reference' => 'ORD-' . Str::upper(Str::random(8)),
            'customer_id' => $customer->id,
            'user_id' => auth()->id() ?? null,
            'total_amount' => $subtotal,
            'discount' => $discount,
            'paid_amount' => $total, // Payment after discount
            'due_amount' => 0,
            'status' => 'completed',
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
        ]);

        // Create sale details
        foreach ($cart as $id => $item) {
            $product = Product::find($id);

            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $id,
                'unit_price' => $item['price'],
                'quantity' => $item['quantity'],
                'discount' => 0, // Individual item discount can be 0 or calculated differently
                'total_price' => $item['price'] * $item['quantity'],
            ]);

            // Update product stock
            if ($product) {
                $product->decrement('quantity', $item['quantity']);
            }
        }

        // Store sale ID in session for printing
        session()->put('last_order_id', $sale->id);

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('order.confirmation')->with('success', 'Order placed successfully!');
    }

    //   Show order confirmation

    public function orderConfirmation()
    {
        $orderId = session('last_order_id');
        if (!$orderId) {
            return redirect()->route('client.home')->with('error', 'No order found');
        }

        $order = Sale::with(['customer', 'saleDetails.product'])->find($orderId);
        return view('order_confirmation', compact('order'));
    }


    public function printOrder()
    {
        $orderId = session('last_order_id');

        if (!$orderId) {
            return redirect()->back()->with('error', 'Order ID not found in session.');
        }

        $order = Sale::with(['customer', 'saleDetails.product'])->findOrFail($orderId);
        return view('print_cart', compact('order'));
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $id = $request->id;
        $quantity = $request->quantity;

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cartCount' => array_sum(array_column($cart, 'quantity')),
            'message' => 'Cart updated successfully'
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $id = $request->id;
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cartCount' => array_sum(array_column($cart, 'quantity')),
            'message' => 'Item removed from cart.'
        ]);
    }

    /**
     * View cart
     */
    public function viewCart()
    {
        $cart = session('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return view('cart', compact('cart', 'subtotal'));
    }
}
