<?php
namespace App\Http\Controllers;

use App\Exports\PurchaseExport;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index'                  => 'បញ្ជីការបញ្ជាទិញ',
            'create'                 => 'បង្កើតការបញ្ជាទិញ',
            'edit'                   => 'កែប្រែការបញ្ជាទិញ',
            'destroy'                => 'លុបការបញ្ជាទិញ',
            'exportPurchasesToExcel' => 'ទាញយកទិន្នន័យការបញ្ជាទិញ',

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
        $purchases = Purchase::with('supplier')->orderBy('created_at', 'desc')->get();
        return view('admin.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        Session::forget('cart');
        return view('admin.purchases.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $cart = Session::get('cart', []);

            if (empty($cart)) {
                return redirect()->route('products.index')->with('error', 'គ្មានទិន្នន័យ');
            }

            $totalAmount   = 0;
            $totalDiscount = 0;

            $discountType   = $request->discount_type;
            $discountAmount = $request->discount_amount;

            // Calculate total amount before applying discount
            foreach ($cart as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            // Apply discount
            if ($discountType == 'percentage') {
                $totalDiscount = ($totalAmount * $discountAmount) / 100;
            } elseif ($discountType == 'fixed') {
                $totalDiscount = $discountAmount;
            }
            $totalDiscount = min($totalDiscount, $totalAmount);

            // Create the purchase record
            $purchase = Purchase::create([
                'date'           => $request->date,
                'reference'      => 'PUR-10000' . mt_rand(1, 100000),
                'supplier_id'    => $request->supplier_id,
                'total_amount'   => $totalAmount,
                'discount'       => $totalDiscount,
                'paid_amount'    => $request->paid_amount,
                'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
                'status'         => $request->status ?? 'កំពុងរង់ចាំ',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->paid_amount >= ($totalAmount - $totalDiscount)
                ? 'បានទូទាត់រួច'
                : ($request->paid_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'មិនទាន់ទូទាត់'),
                'description'    => $request->description ?? 'N/A',
            ]);

            $purDetailDis = count($cart) > 0 ? $purchase->discount / count($cart) : 0;
            // Save purchase details
            foreach ($cart as $productId => $item) {
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $productId,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['price'],
                    'discount'    => $purDetailDis,
                    'total_price' => ($item['price'] * $item['quantity']) - $purDetailDis,
                ]);

                // ✅ Update stock only if status is "បញ្ចប់"
                if ($request->status == 'បញ្ចប់') {
                    $product = Product::where('id', $productId)->lockForUpdate()->first();

                    if ($product) {
                        $stock = Stock::where('product_id', $productId)->lockForUpdate()->first();

                        if ($stock) {
                            $stock->update([
                                'last_stock' => $stock->current,
                                'current'    => $stock->current + $item['quantity'],
                                'purchase'   => $item['quantity'],
                            ]);
                        } else {
                            $stock = Stock::create([
                                'product_id' => $productId,
                                'last_stock' => 0,
                                'current'    => $item['quantity'],
                                'purchase'   => $item['quantity'],
                            ]);
                        }

                        // ✅ Update product quantity
                        $product->update([
                            'quantity' => $stock->current,
                        ]);
                    }
                }
            }

            Session::forget('cart');

            if ($purchase->paid_amount > 0) {
                PurchasePayment::create([
                    'purchase_id'    => $purchase->id,
                    'date'           => $request->date,
                    'reference'      => 'INV/' . $purchase->reference,
                    'amount'         => $purchase->paid_amount,
                    'payment_method' => $request->payment_method,
                    'note'           => $request->note ?? 'N/A',
                ]);
            }
            return redirect()->route('purchases.index')->with('success', 'ការទិញបានបង្កើតជោគជ័យ។');
        });
    }

    public function show($id)
    {
        $purchase = Purchase::with('purchaseDetails')->findOrFail($id);
        return view('admin.purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $suppliers       = Supplier::all();
        $purchaseDetails = PurchaseDetail::where('purchase_id', $purchase->id)->get();
        $cart            = [];

        foreach ($purchaseDetails as $purchaseDetail) {
            $product            = Product::findOrFail($purchaseDetail->product_id);
            $cart[$product->id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $purchaseDetail->unit_price,
                'quantity' => $purchaseDetail->quantity,
                'total'    => ($purchaseDetail->unit_price * $purchaseDetail->quantity) - $purchaseDetail->discount,
            ];
        }

        session()->put('cart', $cart);

        $discountType   = 'fixed';
        $discountAmount = $purchase->discount > 0 ? $purchase->discount : 0;

        return view('admin.purchases.edit', compact('suppliers', 'purchase', 'discountType', 'purchaseDetails', 'discountAmount'));
    }
    public function update(Request $request, Purchase $purchase)
    {
        return DB::transaction(function () use ($request, $purchase) {
            $request->validate([
                'supplier_id'    => 'required',
                'date'           => 'required|date',
                'status'         => 'required|string',
                'paid_amount'    => 'required|numeric|min:0',
                'payment_method' => 'required|string',
                'note'           => 'nullable|string',
            ]);

            $cart = Session::get('cart', []);
            if (empty($cart)) {
                return redirect()->back()->withErrors('The cart is empty. Please add items before updating the purchase.');
            }

            $totalAmount   = 0;
            $totalDiscount = 0;

            // ✅ Restore old stock before updating purchase
            $oldPurchaseDetails = PurchaseDetail::where('purchase_id', $purchase->id)->get();
            foreach ($oldPurchaseDetails as $oldItem) {
                $product = Product::where('id', $oldItem->product_id)->lockForUpdate()->first();
                $stock   = Stock::where('product_id', $oldItem->product_id)->lockForUpdate()->first();

                if ($product) {
                    $product->quantity -= $oldItem->quantity; // Revert previous stock update
                    $product->save();
                }

                if ($stock) {
                    $stock->current -= $oldItem->quantity; // Revert previous stock update
                    $stock->save();
                }
            }

            // ✅ Delete old purchase details
            PurchaseDetail::where('purchase_id', $purchase->id)->delete();

            // ✅ Calculate total amount and discount
            foreach ($cart as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            $discountType   = $request->discount_type;
            $discountAmount = $request->discount_amount ?? 0;

            if ($discountType == 'percentage') {
                $totalDiscount = ($totalAmount * $discountAmount) / 100;
            } elseif ($discountType == 'fixed') {
                $totalDiscount = $discountAmount;
            }

            $totalDiscount = min($totalDiscount, $totalAmount);

            // ✅ Update the purchase record
            $purchase->update([
                'date'           => $request->date,
                'supplier_id'    => $request->supplier_id,
                'reference'      => $request->reference,
                'total_amount'   => $totalAmount,
                'discount'       => $totalDiscount,
                'paid_amount'    => $request->paid_amount,
                'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
                'status'         => $request->status,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->paid_amount >= $totalAmount - $totalDiscount
                ? 'បានទូទាត់រួច'
                : ($request->paid_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'មិនទាន់ទូទាត់'),
                'description'    => $request->description ?? 'N/A',
            ]);

            // ✅ Prevent Division by Zero
            $purDetailDis = count($cart) > 0 ? $purchase->discount / count($cart) : 0;

            foreach ($cart as $productId => $item) {
                // ✅ Lock product to prevent race conditions
                $product = Product::where('id', $productId)->lockForUpdate()->first();
                if (! $product) {
                    return redirect()->route('purchases.edit', $purchase->id)->with('error', 'Product not found: ' . $productId);
                }

                // ✅ Update stock only if status is "បញ្ចប់"
                if ($request->status == 'បញ្ចប់') {
                    $stock = Stock::where('product_id', $productId)->lockForUpdate()->first();

                    if ($stock) {
                        $stock->update([
                            'last_stock' => $stock->current,
                            'current'    => $stock->current + $item['quantity'],
                            'purchase'   => $item['quantity'],
                        ]);
                    } else {
                        $stock = Stock::create([
                            'product_id' => $productId,
                            'last_stock' => 0,
                            'current'    => $item['quantity'],
                            'purchase'   => $item['quantity'],
                        ]);
                    }

                    // ✅ Update the product's quantity
                    $product->update([
                        'quantity' => $stock->current,
                    ]);
                }

                // ✅ Insert purchase details
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $productId,
                    'unit_price'  => $item['price'],
                    'quantity'    => $item['quantity'],
                    'discount'    => $purDetailDis,
                    'total_price' => ($item['price'] * $item['quantity']) - $purDetailDis,
                ]);
            }

            // ✅ Clear cart session
            Session::forget('cart');

            // ✅ Update or create purchase payment
            if ($purchase->paid_amount > 0) {
                PurchasePayment::updateOrCreate(
                    ['purchase_id' => $purchase->id],
                    [
                        'date'           => $request->date,
                        'reference'      => 'INV/' . $purchase->reference,
                        'amount'         => $purchase->paid_amount,
                        'payment_method' => $request->payment_method,
                        'note'           => $request->note ?? 'N/A',
                    ]
                );
            }

            return redirect()->route('purchases.index')->with('success', 'ការទិញត្រូវបានកែប្រែដោយជោគជ័យ.');
        });
    }
    // public function update(Request $request, Purchase $purchase)
    // {
    //     $request->validate([
    //         'supplier_id'    => 'required',
    //         'date'           => 'required|date',
    //         'status'         => 'required|string',
    //         'paid_amount'    => 'required|numeric|min:0',
    //         'payment_method' => 'required|string',
    //         'note'           => 'nullable|string',
    //     ]);

    //     $cart = Session::get('cart', []);
    //     if (empty($cart)) {
    //         return redirect()->back()->withErrors('The cart is empty. Please add items before updating the purchase.');
    //     }

    //     $totalAmount   = 0;
    //     $totalDiscount = 0;

    //     // Get supplier and discount data
    //     $supplier       = Supplier::findOrFail($request->supplier_id);
    //     $discountType   = $request->discount_type;
    //     $discountAmount = $request->discount_amount ?? 0;

    //     // Calculate total amount and discount
    //     foreach ($cart as $item) {
    //         $totalAmount += $item['quantity'] * $item['price'];
    //     }

    //     if ($discountType == 'percentage') {
    //         $totalDiscount = ($totalAmount * $discountAmount) / 100;
    //     } elseif ($discountType == 'fixed') {
    //         $totalDiscount = $discountAmount;
    //     }

    //     $totalDiscount = min($totalDiscount, $totalAmount);

    //     // Update the purchase record
    //     $purchase->update([
    //         'date'           => $request->date,
    //         'supplier_id'    => $request->supplier_id,
    //         'reference'      => $request->reference,
    //         'total_amount'   => $totalAmount,
    //         'discount'       => $totalDiscount,
    //         'paid_amount'    => $request->paid_amount,
    //         'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
    //         'status'         => $request->status,
    //         'payment_method' => $request->payment_method,
    //         'payment_status' => $request->paid_amount >= $totalAmount - $totalDiscount
    //         ? 'បានទូទាត់រួច'
    //         : ($request->paid_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'មិនទាន់ទូទាត់'),
    //         'description'    => $request->description ?? 'N/A',
    //     ]);

    //     PurchaseDetail::where('purchase_id', $purchase->id)->delete();

    //     foreach ($cart as $productId => $item) {
    //         $product = Product::find($productId);

    //         if ($request->status == 'បញ្ចប់' && $product) {
    //             $stock = Stock::where('product_id', $productId)->first();

    //             if ($stock) {
    //                 $newCurrentStock = $stock->last_stock + $item['quantity'];

    //                 // Update existing stock
    //                 $stock->update([
    //                     'current'  => $newCurrentStock,
    //                     'purchase' => $item['quantity'],
    //                 ]);
    //             }
    //             // Update the product's quantity
    //             $product->update([
    //                 'quantity' => $product->quantity + $item['quantity'],
    //             ]);
    //             $product->quantity = $stock->current;
    //             $product->save();
    //         }

    //         $purDetailDis = $purchase->discount / count($cart);

    //         // Update purchase details
    //         PurchaseDetail::updateOrCreate(
    //             ['purchase_id' => $purchase->id, 'product_id' => $productId],
    //             [
    //                 'unit_price'  => $item['price'],
    //                 'quantity'    => $item['quantity'],
    //                 'discount'    => $purDetailDis,
    //                 'total_price' => ($item['price'] * $item['quantity']) - $purDetailDis,
    //             ]
    //         );
    //     }

    //     // Clear cart session
    //     session()->forget('cart');

    //     // Update or create purchase payment
    //     if ($purchase->paid_amount > 0) {
    //         PurchasePayment::updateOrCreate(
    //             ['purchase_id' => $purchase->id],
    //             [
    //                 'date'           => $request->date,
    //                 'reference'      => 'INV/' . $purchase->reference,
    //                 'amount'         => $purchase->paid_amount,
    //                 'payment_method' => $request->payment_method,
    //                 'note'           => $request->note ?? 'N/A',
    //             ]
    //         );
    //     }

    //     return redirect()->route('purchases.index')->with('success', 'ការទិញត្រូវបានកែប្រែដោយជោគជ័យ.');
    // }
    public function destroy(Purchase $purchase)
    {
        return DB::transaction(function () use ($purchase) {
            if ($purchase->paid_amount > 0) {
                return redirect()->route('purchases.index')->with('error', 'មិនអាចលុបការទិញដែលមានការទូទាត់។ សូមបង្រួបបង្រួមឬដកប្រាក់សងសិន។');
            }
            $purchaseDetails = PurchaseDetail::where('purchase_id', $purchase->id)->get();

            if ($purchase->status == 'បញ្ចប់') {
                foreach ($purchaseDetails as $detail) {
                    $this->rollbackStock($detail->product_id, $detail->quantity);
                }
            }

            // ✅ 4. លុបទិន្នន័យ Purchase Detail និង Payment
            PurchaseDetail::where('purchase_id', $purchase->id)->delete();
            PurchasePayment::where('purchase_id', $purchase->id)->delete();

            // ✅ 5. លុបការទិញ
            $purchase->delete();

            return redirect()->route('purchases.index')->with('success', 'ការទិញត្រូវបានលុបដោយជោគជ័យ។');
        });
    }
    private function rollbackStock($productId, $quantity)
    {
        $stock = Stock::where('product_id', $productId)->first();
        if ($stock) {
            $stock->update([
                'current'  => max(0, $stock->current - $quantity),
                'purchase' => max(0, $stock->purchase - $quantity),
            ]);

            $product = Product::find($productId);
            if ($product) {
                $product->update(['quantity' => $stock->current]);
            }
        }
    }

    // public function destroy(Purchase $purchase)
    // {
    //     $purchase->delete();
    //     return redirect()->route('purchases.index')->with('success', 'ការទិញត្រូវបានលុបដោយជោគជ័យ.');
    // }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('sales.create');
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

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;

        } else {
            $cart[$product->id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'quantity' => $quantity,
                'price'    => $product->cost_price,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart'    => $cart,
        ]);
    }

    public function delete(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
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
            'message' => 'គ្មានទិន្នន័យ',
        ], 404);
    }
    public function getCarts()
    {
        $cart       = session('cart', []);
        $totalPrice = collect($cart)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
        $cartItems = collect($cart)->map(function ($item, $productId) {
            return [
                'id'       => $productId,
                'name'     => $item['name'],
                'quantity' => $item['quantity'],
                'price'    => $item['price'],
                'total'    => $item['quantity'] * $item['price'],
            ];
        })->values();

        return response()->json([
            'success'    => true,
            'cartItems'  => $cartItems,
            'totalPrice' => number_format($totalPrice, 2),
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
                'message' => 'Product not found.',
            ], 404);
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
    public function exportPurchasesToExcel()
    {
        return Excel::download(new PurchaseExport, 'purchases_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

}
