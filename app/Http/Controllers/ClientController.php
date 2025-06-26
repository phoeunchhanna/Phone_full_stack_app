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
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Telegram\Bot\Api;

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

    // show product category 
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        $categories = Category::all(); // For the sidebar

        return view('loyouts_user.product_list.category_show', compact('category', 'categories'));
    }

    public function show_brands($id)
    {
        $brand = Brand::with('products')->findOrFail($id);
        $brands = brand::all(); // For the sidebar

        return view('loyouts_user.product_list.brand_show', compact('category', 'categories'));
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
            ->where('stock_alert', '>', 0)
            ->get(); // Changed from get() to paginate()

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
            // ពិនិត្យមើលថាតើរូបភាពមាននៅក្នុង storage ឬទេ
            $imagePath = Storage::exists('public/' . $product->image)
                ? Storage::url($product->image)
                : asset('images/default-product.png'); // រូបភាពលំនាំដើមប្រសិនបើគ្មាន

            $cart[$id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->selling_price,
                'image' => $imagePath,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'ផលិតផលត្រូវបានបញ្ចូលក្នុងរទេះ!');
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
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'discount_type' => 'required|in:none,percentage,fixed',
            'discount_percentage' => 'required_if:discount_type,percentage|nullable|numeric|min:0|max:100',
            'discount_amount' => 'required_if:discount_type,fixed|nullable|numeric|min:0',
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
        $discountType = $request->discount_type;

        if ($discountType === 'percentage') {
            $percentage = $request->discount_percentage;
            $discount = ($subtotal * $percentage) / 100;
        } elseif ($discountType === 'fixed') {
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
            return redirect()->back()
                ->with('error', 'Order ID not found in session.');
        }

        $order = Sale::with(['customer', 'saleDetails.product'])
            ->findOrFail($orderId);

        // Send formatted order to Telegram
        $this->sendOrderNotification($order);

        return view('print_cart', compact('order'));
    }

    private function sendOrderNotification(Sale $order)
    {
        $message = $this->createOrderMessage($order);
        $this->sendToTelegram($message);
    }

    private function createOrderMessage(Sale $order): string
    {
        return <<<MSG
<b>🛒 NEW ORDER RECEIPT #{$order->reference}</b>
━━━━━━━━━━━━━━━━━━
📅 <b>Date:</b> {$order->date->format('d/m/Y H:i')}
👤 <b>Customer:</b> {$order->customer->name}
📱 <b>Phone:</b> {$order->customer->phone}
🏠 <b>Address:</b> {$order->customer->address}
💳 <b>Payment:</b> {$this->formatPaymentMethod($order->payment_method)}
━━━━━━━━━━━━━━━━━━
<b>📦 ORDER ITEMS:</b>
{$this->formatOrderItems($order->saleDetails)}
━━━━━━━━━━━━━━━━━━
💰 <b>Subtotal:</b> \${$this->formatPrice($order->total_amount)}
{$this->formatDiscount($order->discount)}
💵 <b>Total Paid:</b> \${$this->formatPrice($order->paid_amount)}
━━━━━━━━━━━━━━━━━━
Thank you for your order!
MSG;
    }

    private function formatPaymentMethod(string $method): string
    {
        return ucfirst(str_replace('_', ' ', $method));
    }

    private function formatOrderItems($items): string
    {
        return $items->map(function ($item) {
            return "➡️ {$item->product->name} (×{$item->quantity}) - \${$this->formatPrice($item->total_price)}";
        })->implode("\n");
    }

    private function formatDiscount(float $discount): string
    {
        return $discount > 0
            ? "🎁 <b>Discount:</b> -\${$this->formatPrice($discount)}\n"
            : '';
    }

    private function formatPrice(float $amount): string
    {
        return number_format($amount, 2);
    }

    private function sendToTelegram(string $message): void
    {
        try {
            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

            $telegram->sendMessage([
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true
            ]);
        } catch (\Exception $e) {
            \Log::error("Telegram notification error: {$e->getMessage()}");
        }
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



    public function removeFromCart(Request $request)
    {
        $id = $request->id;
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            // Calculate new subtotal
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            return response()->json([
                'success' => true,
                'cartCount' => array_sum(array_column($cart, 'quantity')),
                'subtotal' => $subtotal,
                'message' => 'ធាតុត្រូវបានលុបចេញពីរទេះដោយជោគជ័យ។'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'រកមិនឃើញធាតុក្នុងរទេះ។'
        ], 404);
    }

    public function viewCart()
    {
        $cart = session('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return view('cart', compact('cart', 'subtotal'));
    }


    public function increaseQuantity($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'cartCount' => array_sum(array_column($cart, 'quantity')),
                'subtotal' => $this->calculateSubtotal($cart),
                'itemPrice' => $cart[$id]['price'],
                'message' => 'Quantity increased successfully'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart'], 404);
    }

    public function decreaseQuantity($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity'] -= 1;
                session()->put('cart', $cart);
            }

            return response()->json([
                'success' => true,
                'cartCount' => array_sum(array_column($cart, 'quantity')),
                'subtotal' => $this->calculateSubtotal($cart),
                'itemPrice' => $cart[$id]['price'],
                'message' => 'Quantity decreased successfully'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart'], 404);
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $newQuantity = max(1, (int)$request->quantity);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $newQuantity;
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'cartCount' => array_sum(array_column($cart, 'quantity')),
                'subtotal' => $this->calculateSubtotal($cart),
                'itemPrice' => $cart[$id]['price'],
                'message' => 'Quantity updated successfully'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart'], 404);
    }

    private function calculateSubtotal($cart)
    {
        return array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function showContactForm()
    {
        return view('contact');
    }


    public function submitContactForm(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
            $chatId = env('TELEGRAM_CHAT_ID');

            $text = "📩 **សារប្រមូលផ្តុំពីអតិថិជន** 📩\n\n"
                . "👤 **ឈ្មោះ:** " . $request->full_name . "\n"
                . "📧 **អ៊ីម៉ែល:** " . $request->email . "\n"
                . "📱 **លេខទូរសព្ទ៍:** " . $request->phone . "\n"
                . "💬 **សារ:** " . $request->message;

            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $text
            ]);

            return redirect()->back()->with('success', 'Thank you for your message! We will contact you soon.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was an error sending your message. Please try again later.')
                ->withInput();
        }
    }
}
