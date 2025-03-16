<?php
namespace App\Http\Controllers;

use App\Exports\SaleExport;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SalePayment;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Telegram\Bot\Api;

class SaleController extends Controller
{
    public function __construct()
    {
        $permissions = [
            'index'   => 'បញ្ជីការលក់',
            'create'  => 'បង្កើតការលក់',
            'edit'    => 'កែរប្រែការលក់',
            'destroy' => 'លុបការលក់',

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
        Session::forget('cart');
        $sales = Sale::all();
        return view('admin.sales.index', compact('sales'));
    }
    public function create()
    {
        $customers = Customer::all();
        $products  = Product::all();
        $cart      = Session::get('cart', []);
        Session::forget('cart');
        return view('admin.sales.create', compact('cart', 'products', 'customers'));
    }
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $cart = Session::get('cart', []);
            if (empty($cart)) {
                return redirect()->route('sales.create')->with('error', 'Cart is empty, cannot process sale.');
            }
            $totalAmount   = 0;
            $totalDiscount = 0;

            // Get customer and discount data
            $customer       = Customer::findOrFail($request->customer_id);
            $discountType   = $request->discount_type;
            $discountAmount = $request->discount_amount;

            foreach ($cart as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            if ($discountType == 'percentage') {
                $totalDiscount = ($totalAmount * $discountAmount) / 100;
            } elseif ($discountType == 'fixed') {
                $totalDiscount = $discountAmount;
            }

            $totalDiscount = min($totalDiscount, $totalAmount);

            // Create the sale record
            $sale = Sale::create([
                'date'           => $request->date,
                'reference'      => 'SL-10000' . mt_rand(1, 10000),
                'user_id'        => Auth::id(),
                'customer_id'    => $request->customer_id,
                'total_amount'   => $totalAmount,
                'discount'       => $totalDiscount,
                'paid_amount'    => $request->paid_amount,
                'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
                'status'         => 'បញ្ចប់',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->paid_amount >= $totalAmount - $totalDiscount ? 'បានទូទាត់រួច' : ($request->paid_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'មិនទាន់ទូទាត់'),
                'description'    => $request->description ?? 'N/A',
            ]);

            $saleDetailDis = count($cart) > 0 ? $sale->discount / count($cart) : 0;

            foreach ($cart as $item) {
                $product = Product::where('id', $item['id'])->lockForUpdate()->first();
                if (! $product || $product->quantity < $item['quantity']) {
                    return redirect()->route('sales.create')->with('error', 'ចំនួនក្នុងស្តុកមិនគ្រប់គ្រាន់: ' . ($product->name ?? 'Unknown Product'));
                }
                // $product = Product::find($item['id']);
                // if ($product->quantity < $item['quantity']) {
                //     return redirect()->route('sales.create')->with('error', 'ចំនួនក្នុងស្តុកមិនគ្រប់គ្រាន់!: ' . $product->name);
                // }

                // $saleDetailDis = $sale->discount / count($cart);

                SaleDetail::create([
                    'sale_id'     => $sale->id,
                    'product_id'  => $item['id'],
                    'unit_price'  => $item['price'],
                    'quantity'    => $item['quantity'],
                    'discount'    => $saleDetailDis,
                    'total_price' => ($item['quantity'] * $item['price']) - $saleDetailDis,
                ]);

                // Update stock
                $stock = Stock::where('product_id', $item['id'])->lockForUpdate()->first();
                if ($stock) {
                    $stock->current -= $item['quantity'];
                    $stock->save();
                }

                // Update product quantity
                $product->quantity -= $item['quantity'];
                $product->save();
            }

            if ($sale->paid_amount > 0) {
                SalePayment::create([
                    'sale_id'        => $sale->id,
                    'date'           => $request->date,
                    'reference'      => 'INV/' . $sale->reference,
                    'amount'         => $sale->paid_amount,
                    'payment_method' => $request->payment_method,
                ]);
            }

            // Clear the cart session
            Session::forget('cart');

            // Send Telegram Message
            $this->sendTelegramMessage($sale);
            return redirect()->route('sales.print-pos', $sale->id)->with([
                'message'    => 'Sale saved and printed successfully.',
                'alert-type' => 'success',
            ]);
        });
    }

    // public function store(Request $request)
    // {
    //     $cart          = Session::get('cart', []);
    //     $totalAmount   = 0;
    //     $totalDiscount = 0;

    //     // Get customer and discount data
    //     $customer       = Customer::findOrFail($request->customer_id);
    //     $discountType   = $request->discount_type;
    //     $discountAmount = $request->discount_amount;

    //     foreach ($cart as $item) {
    //         $totalAmount += $item['quantity'] * $item['price'];
    //     }

    //     if ($discountType == 'percentage') {
    //         $totalDiscount = ($totalAmount * $discountAmount) / 100;
    //     } elseif ($discountType == 'fixed') {
    //         $totalDiscount = $discountAmount;
    //     }

    //     $totalDiscount = min($totalDiscount, $totalAmount);

    //     // Create the sale record
    //     $sale = Sale::create([
    //         'date'           => $request->date,
    //         'reference'      => 'SL-10000' . mt_rand(1, 100000),
    //         'user_id'        => Auth::id(),
    //         'customer_id'    => $request->customer_id,
    //         'total_amount'   => $totalAmount,
    //         'discount'       => $totalDiscount,
    //         'paid_amount'    => $request->paid_amount,
    //         'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
    //         'status'         => 'បញ្ចប់',
    //         'payment_method' => $request->payment_method,
    //         'payment_status' => $request->paid_amount >= $totalAmount - $totalDiscount
    //         ? 'បានទូទាត់រួច'
    //         : ($request->paid_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'មិនទាន់ទូទាត់'),

    //         'description'    => $request->description ?? 'គ្មាន',
    //     ]);
    //     // Process each cart item and create SaleDetail records
    //     foreach ($cart as $item) {
    //         $product = Product::find($item['id']);
    //         if ($product->quantity < $item['quantity']) {
    //             return redirect()->route('sales.create')->with('error', 'ចំនួនក្នុងស្តុកមិនគ្រប់គ្រាន់!: ' . $product->name);
    //         }
    //         $saleDetailDis = $sale->discount / count($cart);
    //         // Create SaleDetail record
    //         SaleDetail::create([
    //             'sale_id'     => $sale->id,
    //             'product_id'  => $item['id'],
    //             'unit_price'  => $item['price'],
    //             'quantity'    => $item['quantity'],
    //             'discount'    => $saleDetailDis ?? 0,
    //             'total_price' => ($item['quantity'] * $item['price']) - ($saleDetailDis ?? 0),
    //         ]);

    //                                                                    // Update stock
    //         $stock = Stock::where('product_id', $item['id'])->first(); // Find stock for the product
    //         if ($stock) {
    //             $stock->current -= $item['quantity']; // Decrease current stock by quantity sold
    //             $stock->save();
    //         }

    //         // Update product quantity
    //         $product->quantity -= $item['quantity'];
    //         $product->save();
    //     }

    //     Session::forget('cart');
    //     if ($sale->paid_amount > 0) {
    //         SalePayment::create([
    //             'sale_id'        => $sale->id,
    //             'date'           => $request->date,
    //             'reference'      => 'INV/' . $sale->reference,
    //             'amount'         => $sale->paid_amount,
    //             'payment_method' => $request->payment_method,
    //         ]);
    //     }
    //     $this->sendTelegramMessage($sale);
    //     return redirect()->route('sales.print-pos', $sale->id)->with([
    //         'message'    => 'Success order',
    //         'alert-type' => 'success',
    //     ]);
    // }
    private function sendTelegramMessage($sale)
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $chatId   = env('TELEGRAM_CHAT_ID');

        $message = "🛒 *ការលក់ថ្មីត្រូវបានកត់ត្រា!*\n";
        $message .="លេខវិក័យបត្រ" . $sale->reference . "\n";
        $message .= "📅 *កាលបរិច្ឆេទ:* " . $sale->date->toDateTimeString() . "\n";
        $message .= "📌 *ឈ្មោះអតិថិជន:* {$sale->customer->name}\n";
        $message .= "👤 *អ្នកគិតលុយ:* {$sale->user->name}\n";
        $message .= "💰 *ចំនួនទឹកប្រាក់សរុប:* " . number_format($sale->total_amount, 2) . " USD\n";
        $message .= "📌 *ស្ថានភាពការបង់ប្រាក់:* {$sale->payment_status}\n";
        $message .= "💰 *ចំនួនទឹកប្រាក់នៅជំពាក់:* " . number_format($sale->due_amount, 2) . " USD\n";
        $totalAmount = 0;
        if ($sale->saleDetails->isNotEmpty()) {
            foreach ($sale->saleDetails as $detail) {
                $message .= "📌 *ផលិតផល:* {$detail->product->name} - {$detail->quantity} x " . number_format($detail->total_price, 2) . " USD\n";
            }
        }
        $telegram->sendMessage([
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'Markdown',
        ]);
    }

    public function show(Sale $sale)
    {
        $sale = $sale->load('customer', 'saleDetails.product');
        return view('admin.sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $cart = session()->get('cart', []);

        // Populate the cart with the sale details
        foreach ($sale->saleDetails as $saleDetail) {
            $product            = Product::findOrFail($saleDetail->product_id);
            $cart[$product->id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $saleDetail->unit_price,
                'quantity' => $saleDetail->quantity,
                'discount' => $saleDetail->discount,
                'total'    => ($saleDetail->unit_price - $saleDetail->discount) * $saleDetail->quantity,
            ];
        }

        session()->put('cart', $cart);

        $discountType   = 'fixed';
        $discountAmount = $sale->discount > 0 ? $sale->discount : 0;

        $customers = Customer::all();

        return view('admin.sales.edit', compact('sale', 'customers', 'discountType', 'discountAmount'));
    }

    public function update(Request $request, Sale $sale)
    {
        return DB::transaction(function () use ($request, $sale) {
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return redirect()->route('sales.create')->with('error', 'គ្មានទិន្នន័យ');
            }

            $totalAmount   = 0;
            $totalDiscount = 0;
            // ✅ Restore old stock before updating sale
            $oldSaleDetails = SaleDetail::where('sale_id', $sale->id)->get();
            foreach ($oldSaleDetails as $oldItem) {
                $product = Product::where('id', $oldItem->product_id)->lockForUpdate()->first();
                $stock   = Stock::where('product_id', $oldItem->product_id)->lockForUpdate()->first();
                if ($product) {
                    $product->quantity += $oldItem->quantity;
                    $product->save();
                }
                if ($stock) {
                    $stock->current += $oldItem->quantity;
                    $stock->save();
                }
            }

            SaleDetail::where('sale_id', $sale->id)->delete();

            foreach ($cart as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            if ($request->discount_type == 'percentage') {
                $totalDiscount = ($totalAmount * $request->discount_amount) / 100;
            } elseif ($request->discount_type == 'fixed') {
                $totalDiscount = $request->discount_amount;
            }

            $totalDiscount = min($totalDiscount, $totalAmount);

            // Update Sale Record
            $sale->update([
                'date'           => $request->date,
                'customer_id'    => $request->customer_id,
                'total_amount'   => $totalAmount,
                'discount'       => $totalDiscount,
                'paid_amount'    => $request->paid_amount,
                'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
                'status'         => 'បញ្ចប់',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->paid_amount >= $totalAmount - $totalDiscount ? 'បានទូទាត់រួច' : ($request->paid_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'មិនទាន់ទូទាត់'),
                'description'    => $request->description ?? 'គ្មាន',
            ]);

            $saleDetailDis = count($cart) > 0 ? $sale->discount / count($cart) : 0;

            foreach ($cart as $item) {
                $product = Product::where('id', $item['id'])->lockForUpdate()->first();
                if (! $product || $product->quantity < $item['quantity']) {
                    return redirect()->route('sales.edit', $id)->with('error', 'Stock not enough: ' . ($product->name ?? 'Unknown Product'));
                }

                SaleDetail::create([
                    'sale_id'     => $sale->id,
                    'product_id'  => $item['id'],
                    'unit_price'  => $item['price'],
                    'quantity'    => $item['quantity'],
                    'discount'    => $saleDetailDis,
                    'total_price' => ($item['quantity'] * $item['price']) - $saleDetailDis,
                ]);

                $stock = Stock::where('product_id', $item['id'])->lockForUpdate()->first();
                if ($stock) {
                    $stock->current -= $item['quantity'];
                    $stock->save();
                }

                $product->quantity -= $item['quantity'];
                $product->save();
            }

            SalePayment::updateOrCreate(
                ['sale_id' => $sale->id],
                [
                    'date'           => $request->date,
                    'reference'      => 'INV/' . $sale->reference,
                    'amount'         => $sale->paid_amount,
                    'payment_method' => $request->payment_method,
                ]
            );

            Session::forget('cart');

            // $this->sendTelegramMessage($sale);

            return redirect()->route('sales.print-pos', $sale->id)->with([
                'message'    => 'Sale updated and printed successfully.',
                'alert-type' => 'success',
            ]);
        });
    }
    public function print_invoice_pos(Sale $sales)
    {
        $sales = $sales->load('saleDetails');
        return view('admin.sales.print-pos', compact('sales'));
    }

    // Cart Session
    public function cancel(Sale $sale)
    {
        $sale->update(['status' => 'បានលុប']);
        return redirect()->route('sales.index')->with('success', 'ត្រូវបានបោះបង់');
    }
    // Clear cart
    public function clear()
    {
        if (Session::has('cart')) {
            Session::forget('cart');
            return response()->json(['success' => true, 'message' => 'សម្អាតជោគជ័យ.']);
        }
        return response()->json(['success' => false, 'message' => 'គ្មានអ្វីដែលត្រូវសម្អាតទេ!.']);
    }

    public function add(Request $request)
    {
        $product  = Product::find($request->input('product_id'));
        $quantity = $request->input('quantity', 1);
        $discount = $request->discount ?? 0;

        if (! $product) {
            return response()->json([
                'status'  => 404,
                'message' => 'Product not found!',
            ]);
        }
        if ($product->quantity < $quantity) {
            return response()->json([
                'status'  => 400,
                'message' => 'ស្តុកមិនគ្រប់គ្រាន់សម្រាប់: ' . $product->name,
            ]);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            if ($product->quantity < $cart[$product->id]['quantity'] + $quantity) {
                return response()->json([
                    'status'  => 400,
                    'message' => 'ចំនួនក្នងស្តុកមិនគ្រប់គ្រាន់: ' . $product->name,
                ]);
            } else {
                $cart[$product->id]['quantity'] += $quantity;
            }
        } else {
            $cart[$product->id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'discount' => $discount,
                'quantity' => $quantity,
                'price'    => $product->selling_price,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'ផលិតផលត្រូវបានបន្ថែមជោគជ័យ!',
            'cart'    => $cart,
        ]);
    }

    public function delete(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            $product = Product::find($request->product_id);

            if ($product) {
                $stock = Stock::where('product_id', $request->product_id)->first();

                if ($stock) {
                    $quantityToReturn = $cart[$request->product_id]['quantity'];

                    $stock->update([
                        'current'  => $stock->current + $quantityToReturn,
                        'purchase' => -$quantityToReturn,
                    ]);
                }

                // Remove the product from the cart
                unset($cart[$request->product_id]);
                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'message' => 'ទិន្នន័យត្រូវបានលុប!',
                    'cart'    => $cart,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Product not found!',
            ], 404);
        }

        return response()->json([
            'success' => false,
            'message' => 'គ្មានទិន្នន័យ',
        ], 404);
    }

    public function getCarts()
    {
        $cart = session('cart', []);

        $cartItems = collect($cart)->map(function ($item, $productId) {
            return [
                'id'       => $productId,
                'name'     => $item['name'],
                'quantity' => $item['quantity'],
                'discount' => $item['discount'],
                'price'    => $item['price'],
            ];
        })->values();

        return response()->json([
            'success'   => true,
            'cartItems' => $cartItems,
            'message'   => 'ទិន្នន័យត្រូវបានទាញយកជោគជ័យ!',
        ]);
    }
    public function updateQuantity(Request $request)
    {
        $cart        = session()->get('cart', []);
        $productId   = $request->product_id;
        $newQuantity = $request->quantity;
        $product     = Product::find($productId);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'ផលិតផលក្នុងស្តុកមិនគ្រប់គ្រាន់.',
            ], 404);
        }

        if ($product->quantity < $newQuantity) {
            return response()->json([
                'success' => false,
                'message' => 'ចំនួនក្នុងស្តុកមិនគ្រប់គ្រាន់.',
            ], 400);
        }

        if (isset($cart[$productId]) && $newQuantity > 0) {
            $cart[$productId]['quantity'] = $newQuantity;
            $cart[$productId]['total']    = $newQuantity * $cart[$productId]['price'];
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'ទិន្នន័យត្រូវបានកែប្រែដោយជោគជ័យ!',
                'cart'    => $cart,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'មិនត្រឹមត្រូវ.',
        ], 400);
    }
    public function updatediscount(Request $request)
    {
        // Get the current cart from the session
        $cart           = session()->get('cart', []);
        $productId      = $request->product_id;
        $discountAmount = $request->discount;

        $product = Product::find($productId);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'ផលិតផលមិនមានក្នុងស្តុកទេ។',
            ], 404);
        }
        // Check if the product exists in the cart
        if (isset($cart[$productId])) {
            $discountedPrice = $product->price - $discountAmount;
            if ($discountedPrice < 0) {
                $discountedPrice = 0;
            }

            $cart[$productId]['discount'] = $discountAmount;
            $cart[$productId]['price']    = $discountedPrice;
            $cart[$productId]['total']    = $discountedPrice * $cart[$productId]['quantity'];
            session()->put('cart', $cart);
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['total'];
            }

            return response()->json([
                'success' => true,
                'message' => 'ការបញ្ចុះតម្លៃត្រូវបានកែប្រែដោយជោគជ័យ!',
                'cart'    => $cart,
                'total'   => $total,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'ផលិតផលនេះមិនមានក្នុងកាបូបរបស់អ្នកទេ។',
        ], 404);
    }

    
    public function exportSalesToExcel(Request $request)
    {

        $fileName = "Sale_Export" . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new SaleExport, $fileName);
    }
}
