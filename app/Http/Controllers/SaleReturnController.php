<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\SaleReturnPayment;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SaleReturnController extends Controller
{

    public function index()
    {
        Session::forget('cart');
        $salesReturns = SaleReturn::orderBy('id', 'desc')->get();
        return view('admin.sale_returns.index', compact('salesReturns'));
    }
    public function create()
    {
        return view('admin.sale_returns.reference');

    }
    public function store(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('sales.index')->with('error', 'គ្មានទិន្នន័យ');
        }

        return DB::transaction(function () use ($request, $cart) {
            // Step 1: Calculate Total Amount & Discount
            $totalAmount = 0;
            foreach ($cart as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            $discountType   = $request->discount_type;
            $discountAmount = $request->discount_amount;
            $totalDiscount  = 0;

            if ($discountType == 'percentage') {
                $totalDiscount = ($totalAmount * $discountAmount) / 100;
            } elseif ($discountType == 'fixed') {
                $totalDiscount = min($discountAmount, $totalAmount);
            }

            // Step 2: Create Sale Return Record
            $saleReturn = SaleReturn::create([
                'date'           => $request->date,
                'reference'      => 'SR-' . mt_rand(10000, 99999),
                'customer_id'    => $request->customer_id,
                'sale_id'        => $request->sale_id,
                'total_amount'   => $totalAmount,
                'discount'       => $totalDiscount,
                'paid_amount'    => $request->paid_amount,
                'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
                'status'         => $request->status ?? 'បញ្ចប់',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->paid_amount >= ($totalAmount - $totalDiscount)
                ? 'បានទូទាត់រួច'
                : ($request->paid_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'មិនទាន់បានទូទាត់'),
                'reason'         => $request->reason ?? 'N/A',
            ]);

            // Step 3: Process Each Product Return
            foreach ($cart as $productId => $item) {
                $quantity  = $item['quantity'];
                $unitPrice = $item['price'];

                // Update Sale Detail
                $saleDetail = SaleDetail::where('sale_id', $request->sale_id)
                    ->where('product_id', $productId)
                    ->first();

                if ($saleDetail) {
                    $saleDetail->quantity -= $quantity;
                    $saleDetail->total_price -= ($unitPrice * $quantity);

                    if ($saleDetail->quantity <= 0) {
                        $saleDetail->delete();
                    } else {
                        $saleDetail->save();
                    }
                }

                // Create Sale Return Detail
                SaleReturnDetail::create([
                    'sale_return_id' => $saleReturn->id,
                    'product_id'     => $productId,
                    'quantity'       => $quantity,
                    'unit_price'     => $unitPrice,
                    'discount'       => ($totalDiscount / count($cart)), // Distribute discount evenly
                    'total_price'    => ($unitPrice * $quantity) - ($totalDiscount / count($cart)),
                ]);

                // Update Stock if Sale Return is Completed
                if ($request->status == 'បញ្ចប់') {
                    $product = Product::find($productId);
                    if ($product) {
                        $stock = Stock::where('product_id', $productId)->first();

                        if ($stock) {
                            $stock->update([
                                'last_stock' => $stock->current,
                                'current'    => $stock->current + $quantity,
                                'purchase'   => $quantity,
                            ]);
                        } else {
                            $stock = Stock::create([
                                'product_id' => $productId,
                                'last_stock' => 0,
                                'current'    => $quantity,
                                'purchase'   => $quantity,
                            ]);
                        }

                        // Update product quantity
                        $product->quantity = $stock->current;
                        $product->save();
                    }
                }
            }

            // Step 4: Update the Related Sale
            $sale = Sale::find($request->sale_id);
            if ($sale) {
                $returnAmount = $saleReturn->total_amount;

                // Update Sale total_amount & due_amount
                $sale->total_amount -= $returnAmount;
                $sale->due_amount -= $returnAmount;

                // Update payment_status
                if ($sale->due_amount <= 0) {
                    $sale->payment_status = 'បានទូទាត់រួច';
                } elseif ($sale->due_amount > 0 && $sale->paid_amount > 0) {
                    $sale->payment_status = 'បានទូទាត់ខ្លះ';
                } else {
                    $sale->payment_status = 'មិនទាន់បានទូទាត់';
                }

                $sale->save();
            }

            // Step 5: Create Sale Return Payment if Amount is Paid
            if ($saleReturn->paid_amount > 0) {
                SaleReturnPayment::create([
                    'sale_return_id' => $saleReturn->id,
                    'date'           => $request->date,
                    'reference'      => 'SRINV/' . $saleReturn->reference,
                    'amount'         => $saleReturn->paid_amount,
                    'payment_method' => $request->payment_method,
                    'note'           => 'បង់ទិញតាមលេខវិក័យប័ត្រលេខ ' . $saleReturn->reference,
                ]);
            }

            // Step 6: Clear Session Cart
            Session::forget('cart');
            session()->flash('sale_return_id', $saleReturn->id);
            return redirect()->route('sale-returns.index')->with('success', 'ការត្រឡប់ការលក់បានបង្កើតជោគជ័យ។');
        });
    }
    public function getInvoice($id)
    {
        $saleReturn = SaleReturn::with(['customer', 'details.product'])->findOrFail($id);
        return view('admin.sale_returns.invoice', compact('saleReturn'))->render();
    }
    public function show(SaleReturn $saleReturn)
    {
        $saleReturn->load('details');
        return view('admin.sale_returns.show', compact('saleReturn'));
    }

    public function edit(SaleReturn $saleReturn)
    {
        $customers         = Customer::all();
        $SaleReturnDetails = SaleReturnDetail::where('sale_return_id', $saleReturn->id)->get();
        $cart              = [];
        foreach ($SaleReturnDetails as $SaleReturnDetail) {
            $product            = Product::findOrFail($SaleReturnDetail->product_id);
            $cart[$product->id] = [
                'id'            => $product->id,
                'name'          => $product->name,
                'price'         => $SaleReturnDetail->unit_price,
                'quantity'      => 0,
                'sale_quantity' => $SaleReturnDetail->quantity,
                'total'         => ($SaleReturnDetail->unit_price * $SaleReturnDetail->quantity) - $SaleReturnDetail->discount,
            ];
        }
        session()->put('cart', $cart);

        $discountType   = 'fixed';
        $discountAmount = $SaleReturnDetail->discount > 0 ? $SaleReturnDetail->discount : 0;

        return view('admin.sale_returns.edit', compact('customers', 'saleReturn', 'discountType', 'SaleReturnDetails', 'discountAmount'));
    }
    public function update(Request $request, SaleReturn $saleReturn)
    {
        return DB::transaction(function () use ($request, $saleReturn) {
            $cart = Session::get('cart', []);

            if (empty($cart)) {
                return redirect()->route('sales.index')->with('error', 'គ្មានទិន្នន័យ');
            }

            // Store previous total and paid amounts
            $previousTotalAmount = $saleReturn->total_amount;
            $previousPaidAmount  = $saleReturn->paid_amount;

            // Step 1: Calculate New Total Amount & Discount
            $totalAmount = 0;
            foreach ($cart as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }
            $discountType   = $request->discount_type;
            $discountAmount = $request->discount_amount;
            $totalDiscount  = 0;

            if ($discountType == 'percentage') {
                $totalDiscount = ($totalAmount * $discountAmount) / 100;
            } elseif ($discountType == 'fixed') {
                $totalDiscount = $discountAmount;
            }

            $totalDiscount = min($totalDiscount, $totalAmount);

            // Step 2: Update Sale Return Record
            $saleReturn->update([
                'date'           => $request->date,
                'customer_id'    => $request->customer_id,
                'sale_id'        => $request->sale_id,
                'total_amount'   => $totalAmount,
                'discount'       => $totalDiscount,
                'paid_amount'    => $request->paid_amount,
                'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
                'status'         => $request->status ?? 'កំពុងរង់ចាំ',
                'payment_method' => $request->payment_method,
                'payment_status' => ($request->paid_amount >= $totalAmount - $totalDiscount)
                ? 'បានបង់'
                : ($request->paid_amount > 0 ? 'បានបង់ខ្លះ' : 'មិនទាន់បង់'),
                'reason'         => $request->reason ?? 'N/A',
            ]);

            // Step 4: Delete Old SaleReturnDetail Records
            $saleReturn->details()->delete();

            // Step 3: Reverse Previous Stock Changes (if applicable)
            foreach ($saleReturn->details as $detail) {
                if ($saleReturn->status == 'បញ្ចប់') {
                    $product = Product::find($detail->product_id);
                    if ($product) {
                        $stock = Stock::where('product_id', $detail->product_id)->first();
                        if ($stock) {
                            $stock->update([
                                'current' => $stock->current - $detail->quantity, // Reverse previous stock update
                            ]);
                        }
                    }
                }
            }

            // Step 4: Delete Old SaleReturnDetail Records
            $saleReturn->details()->delete();

            // Step 5: Insert New SaleReturnDetail Records & Update Stock
            foreach ($cart as $productId => $item) {
                SaleReturnDetail::create([
                    'sale_return_id' => $saleReturn->id,
                    'product_id'     => $productId,
                    'quantity'       => $item['quantity'],
                    'unit_price'     => $item['price'],
                    'discount'       => ($totalDiscount / count($cart)), // Distribute discount evenly
                    'total_price'    => ($item['price'] * $item['quantity']) - ($totalDiscount / count($cart)),
                ]);

                if ($request->status == 'បញ្ចប់') {
                    $product = Product::find($productId);
                    if ($product) {
                        $stock = Stock::where('product_id', $productId)->first();

                        if ($stock) {
                            $stock->update([
                                'last_stock' => $stock->current,
                                'current'    => $stock->current + $item['quantity'],
                                'purchase'   => $item['quantity'],
                            ]);
                        } else {
                            Stock::create([
                                'product_id' => $productId,
                                'last_stock' => 0,
                                'current'    => $item['quantity'],
                                'purchase'   => $item['quantity'],
                            ]);
                        }

                        $product->quantity = $stock->current;
                        $product->save();
                    }
                }
            }

            // Step 6: Update or Create Sale Return Payment
            SaleReturnPayment::updateOrCreate(
                ['sale_return_id' => $saleReturn->id],
                [
                    'date'           => $request->date,
                    'reference'      => 'SRINV/' . $saleReturn->reference,
                    'amount'         => $saleReturn->paid_amount,
                    'payment_method' => $request->payment_method,
                ]
            );

            // Step 7: Update Related Sale (if applicable)
            $sale = Sale::find($request->sale_id);
            if ($sale) {
                $returnAmountChange = $totalAmount - $previousTotalAmount;

                // Update Sale total_amount & due_amount
                $sale->total_amount += $returnAmountChange;
                $sale->due_amount += $returnAmountChange;

                // Update payment_status
                if ($sale->due_amount <= 0) {
                    $sale->payment_status = 'បានបង់';
                } elseif ($sale->due_amount > 0 && $sale->paid_amount > 0) {
                    $sale->payment_status = 'បានបង់ខ្លះ';
                } else {
                    $sale->payment_status = 'មិនទាន់បង់';
                }

                $sale->save();
            }

            // Step 8: Clear Session Cart & Redirect
            Session::forget('cart');

            return redirect()->route('sale-returns.index')->with('success', 'ការត្រឡប់ការលក់ត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ។');
        });
    }
    public function destroy(SaleReturn $saleReturn)
    {
        return DB::transaction(function () use ($saleReturn) {
            if ($saleReturn->paid_amount > 0) {
                return redirect()->route('sale-returns.index')
                    ->with('error', 'មិនអាចលុបការត្រឡប់ការលក់ដែលមានការបង់ប្រាក់។');
            }

            $saleReturnDetails = SaleReturnDetail::where('sale_return_id', $saleReturn->id)->get();

            if ($saleReturn->status == 'បញ្ចប់') {
                foreach ($saleReturnDetails as $detail) {
                    $this->rollbackStock($detail->product_id, $detail->quantity);
                }
            }

            SaleReturnDetail::where('sale_return_id', $saleReturn->id)->delete();
            SaleReturnPayment::where('sale_return_id', $saleReturn->id)->delete();

            $sale = Sale::find($saleReturn->sale_id);
            if ($sale) {
                $sale->total_amount += $saleReturn->total_amount;
                $sale->due_amount += $saleReturn->total_amount;

                if ($sale->due_amount <= 0) {
                    $sale->payment_status = 'បានបង់';
                } elseif ($sale->due_amount > 0 && $sale->paid_amount > 0) {
                    $sale->payment_status = 'បានបង់ខ្លះ';
                } else {
                    $sale->payment_status = 'មិនទាន់បង់';
                }

                $sale->save();
            }
            //Delete Sale Return
            $saleReturn->delete();

            return redirect()->route('sale-returns.index')
                ->with('success', 'ការត្រឡប់ការលក់ត្រូវបានលុបដោយជោគជ័យ។');
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
    public function getSaleDetails(Request $request)
    {
        $request->validate([
            'sale_reference' => 'required|string|exists:sales,reference',
        ]);

        $sale = Sale::where('reference', $request->sale_reference)
            ->with(['saleDetails.product'])
            ->first();

        if (! $sale) {
            return response()->json([
                'status'  => 404,
                'message' => 'Sale not found!',
            ]);
        }

        session()->forget('cart');

        $cart       = session('cart', []);
        $totalPrice = collect($cart)->sum(fn($item) => $item['quantity'] * $item['price']);

        foreach ($sale->saleDetails as $detail) {
            $cart[$detail->product_id] = [
                'id'            => $detail->product_id,
                'name'          => $detail->product->name,
                'quantity'      => 0,
                'sale_quantity' => $detail->quantity,
                'price'         => $detail->unit_price,
                'total'         => $detail->total_price,
            ];
        }

        session()->put('cart', $cart);
        return view('admin.sale_returns.create', [
            'sale' => $sale,
            'cart' => $cart,
        ]);
    }

    public function getCarts()
    {
        $cart       = session('cart', []);
        $totalPrice = collect($cart)->sum(fn($item) => $item['quantity'] * $item['price']);

        $cartItems = collect($cart)->map(fn($item, $productId) => [
            'id'            => $productId,
            'name'          => $item['name'],
            'quantity'      => $item['quantity'],
            'sale_quantity' => $item['sale_quantity'],
            'price'         => $item['price'],
            'total'         => $item['quantity'] * $item['price'],
        ])->values();

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
        if (isset($cart[$productId]) && $newQuantity > $cart[$productId]['sale_quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'ចំនួនបង្វិលមិនអាចធំជាងចំនួនដែលបានទិញនោះទេ!.',
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
}
