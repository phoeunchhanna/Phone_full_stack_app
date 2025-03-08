<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\SaleReturnPayment;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SaleReturnController extends Controller
{

    public function index()
    {
        Session::forget('cart');
        $salesReturns = SaleReturn::all();
        return view('admin.sale_return.index', compact('salesReturns'));
    }
    public function create()
    {
        return view('admin.sale_return.reference');

    }
    public function store(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('sales.index')->with('error', 'គ្មានទិន្នន័យ');
        }

        $totalAmount   = 0;
        $totalDiscount = 0;

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

        // Create the Sale Return record
        $saleReturn = SaleReturn::create([
            'date'           => $request->date,
            'reference'      => 'SR-10000' . mt_rand(1, 100000),
            'customer_id'    => $request->customer_id,
            'total_amount'   => $totalAmount,
            'discount'       => $totalDiscount,
            'paid_amount'    => $request->paid_amount,
            'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
            'status'         => $request->status ?? 'កំពុងរង់ចាំ',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->paid_amount >= $totalAmount - $totalDiscount
            ? 'បានទូទាត់រួច'
            : ($request->paid_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'មិនទាន់ទូទាត់'),
            'description'    => $request->description ?? 'គ្មាន',
        ]);

        foreach ($cart as $productId => $item) {
            // Calculate the total amount and discount again for each item
            $totalAmount   = 0;
            $totalDiscount = 0;

            foreach ($cart as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            if ($discountType == 'percentage') {
                $totalDiscount = ($totalAmount * $discountAmount) / 100;
            } elseif ($discountType == 'fixed') {
                $totalDiscount = $discountAmount;
            }

            $totalDiscount = min($totalDiscount, $totalAmount);

            SaleReturnDetail::create([
                'sale_return_id' => $saleReturn->id,
                'product_id'     => $productId,
                'quantity'       => $item['quantity'],
                'unit_price'     => $item['price'],
                'discount'       => $saleReturn->discount,
                'total_price'    => ($item['price'] * $item['quantity']) - ($saleReturn->discount),
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
                }
                $product->quantity = $stock->current;

                $product->save();
            }
        }

        Session::forget('cart');

        // Create a payment entry for the returned sale
        if ($saleReturn->paid_amount > 0) {
            SaleReturnPayment::create([
                'sale_return_id' => $saleReturn->id,
                'date'           => $request->date,
                'reference'      => 'SRINV/' . $saleReturn->reference,
                'amount'         => $saleReturn->paid_amount,
                'payment_method' => $request->payment_method,
            ]);
        }

        return redirect()->route('sale-returns.index')->with('success', 'ការត្រឡប់ការលក់បានបង្កើតជោគជ័យ។');
    }
    public function show(SaleReturn $saleReturn)
    {
        $saleReturn->load('details'); // Ensure the relationship is loaded
        return view('admin.sale_return.show', compact('saleReturn'));
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

        return view('admin.sale_return.edit', compact('customers', 'saleReturn', 'discountType', 'SaleReturnDetails', 'discountAmount'));
    }
    public function update(Request $request, SaleReturn $saleReturn)
    {
        $cart = Session::get('cart', []);
    
        if (empty($cart)) {
            return redirect()->route('sales.index')->with('error', 'គ្មានទិន្នន័យ');
        }
    
        $totalAmount   = 0;
        $totalDiscount = 0;
    
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
    
        // Update the existing Sale Return record
        $saleReturn->update([
            'date'           => $request->date,
            'customer_id'    => $request->customer_id,
            'total_amount'   => $totalAmount,
            'discount'       => $totalDiscount,
            'paid_amount'    => $request->paid_amount,
            'due_amount'     => $totalAmount - $totalDiscount - $request->paid_amount,
            'status'         => $request->status ?? 'កំពុងរង់ចាំ',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->paid_amount >= $totalAmount - $totalDiscount
                ? 'បានទូទាត់រួច'
                : ($request->paid_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'មិនទាន់ទូទាត់'),
            'description'    => $request->description ?? 'គ្មាន',
        ]);
    
        // Delete old SaleReturnDetail records
        $saleReturn->Details()->delete();
    
        foreach ($cart as $productId => $item) {
            SaleReturnDetail::create([
                'sale_return_id' => $saleReturn->id,
                'product_id'     => $productId,
                'quantity'       => $item['quantity'],
                'unit_price'     => $item['price'],
                'discount'       => $saleReturn->discount,
                'total_price'    => ($item['price'] * $item['quantity']) - ($saleReturn->discount),
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
    
        Session::forget('cart');
    
        // Update or create payment entry
        SaleReturnPayment::updateOrCreate(
            ['sale_return_id' => $saleReturn->id],
            [
                'date'           => $request->date,
                'reference'      => 'SRINV/' . $saleReturn->reference,
                'amount'         => $saleReturn->paid_amount,
                'payment_method' => $request->payment_method,
            ]
        );
    
        return redirect()->route('sale-returns.index')->with('success', 'ការត្រឡប់ការលក់ត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ។');
    }
    
    public function destroy(SaleReturn $saleReturn)
    {
        $saleReturn->delete();
        return redirect()->route('admin.sale_return.index')->with('success', 'ការទិញត្រូវបានលុបដោយជោគជ័យ.');
    }
    public function showReferenceForm()
    {
        return view('admin.sale_return.reference');
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
        return view('admin.sale_return.create', [
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

        // Check if the new quantity exceeds the sale_quantity
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

    // public function updateQuantity(Request $request)
    // {
    //     // Validate the incoming data
    //     $validated = $request->validate([
    //         'product_id' => 'required|exists:carts,id',
    //         'quantity'   => 'required|numeric|min:1',
    //     ]);

    //     $cartItem = Cart::find($request->product_id);

    //     // Check if the return quantity is greater than the sold quantity
    //     if ($request->quantity > $cartItem->quantity) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Return quantity cannot be greater than the sold quantity.',
    //         ]);
    //     }

    //     // Update the return quantity
    //     $cartItem->return_quantity = $request->quantity;
    //     $cartItem->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Quantity updated successfully.',
    //     ]);
    // }

    // // Method to update the deduction
    // public function updatededuction(Request $request)
    // {
    //     // Validate the incoming data
    //     $validated = $request->validate([
    //         'product_id' => 'required|exists:carts,id',
    //         'deduction'  => 'required|numeric|min:0',
    //     ]);

    //     $cartItem = Cart::find($request->product_id);

    //     // Calculate max deduction (unit_price * quantity)
    //     $maxDeduction = $cartItem->price * $cartItem->quantity;

    //     // Check if the deduction is greater than the maximum allowed
    //     if ($request->deduction > $maxDeduction) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Deduction cannot be greater than the total price.',
    //         ]);
    //     }

    //     // Update the deduction
    //     $cartItem->deduction = $request->deduction;
    //     $cartItem->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Deduction updated successfully.',
    //     ]);
    // }
    public function showSaleReturnInvoice($saleReturnId)
    {
        $saleReturn = SaleReturn::with(['sale', 'sale.customer', 'saleReturnDetails.product'])
            ->findOrFail($saleReturnId);

        $saleReturnDetails = $saleReturn->saleReturnDetails;
        $customer          = $saleReturn->sale->customer; // Assuming Sale model has a customer relation
        return view('admin.sale_return.sale_return_invoice', compact('saleReturn', 'saleReturnDetails', 'customer'));
        // return view('sale_return_invoice', compact('saleReturn', 'saleReturnDetails', 'customer'));

    }

}
