<?php
namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index'   => 'បញ្ជីការទូទាត់ការបញ្ជាទិញ',
            'edit'    => 'កែប្រែការទូទាត់ការបញ្ជាទិញ',
            'destroy' => 'លុបការទូទាត់ការបញ្ជាទិញ',

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
        $payments = PurchasePayment::with('Purchase')->paginate(10);
        return view('admin.Purchases.Purchase_payments.index', compact('payments'));
    }

    public function create($Purchase_id)
    {
        $Purchase = Purchase::findOrFail($Purchase_id);
        return view('admin.Purchases.Purchase_payments.create', compact('Purchase')); // Pass the Purchase variable to the view
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_id'    => 'required|exists:purchases,id',
            'amount'         => 'required|numeric|min:0',
            'date'           => 'required|date',
            'reference'      => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
        ]);

        PurchasePayment::create([
            'purchase_id'    => $request->purchase_id,
            'date'           => $request->date,
            'reference'      => $request->reference,
            'amount'         => $request->amount,
            'note'           => $request->note ?? 'N/A',	
            'payment_method' => $request->payment_method,
        ]);

        $purchase = Purchase::findOrFail($request->purchase_id);

        $due_amount = $purchase->due_amount - $request->amount;

        if ($due_amount == $purchase->total_amount) {
            $payment_status = 'មិនទាន់ទូទាត់';
        } elseif ($due_amount > 0) {
            $payment_status = 'បានទូទាត់ខ្លះ';
        } else {
            $payment_status = 'បានទូទាត់រួច';
        }

        $purchase->update([
            'paid_amount'    => ($purchase->paid_amount + $request->amount),
            'due_amount'     => $due_amount,
            'payment_status' => $payment_status,
        ]);

        return back()->with('success', 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ.');
    }

    public function edit($purchase_id, PurchasePayment $purchasePayment)
    {
        $purchase = Purchase::findOrFail($purchase_id);
        return view('admin.purchases.Purchase_payments.edit', compact('purchasePayment', 'purchase'));
    }

    public function update(Request $request, PurchasePayment $PurchasePayment)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:0',
            'date'           => 'required|date',
            'reference'      => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'note'           => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $PurchasePayment) {
            $Purchase = $PurchasePayment->purchase;

            if (! $Purchase) {
                throw new \Exception('Purchase record not found.');
            }

            $previousAmount = $PurchasePayment->amount;
            $newPaidAmount  = ($Purchase->paid_amount - $previousAmount) + $request->amount;
            $newDueAmount   = $Purchase->total_amount - $Purchase->discount - $newPaidAmount;

            if ($newDueAmount < 0) {
                throw new \Exception('Payment amount exceeds remaining due amount.');
            }

            if ($newDueAmount == 0) {
                $payment_status = 'បានទូទាត់រួច';
            } elseif ($newPaidAmount > 0) {
                $payment_status = 'បានទូទាត់ខ្លះ';
            } else {
                $payment_status = 'មិនទាន់ទូទាត់';
            }

            $Purchase->update([
                'paid_amount'    => $newPaidAmount,
                'due_amount'     => max(0, $newDueAmount),
                'payment_status' => $payment_status,
            ]);

            $PurchasePayment->update([
                'date'           => $request->date,
                'reference'      => $request->reference,
                'amount'         => $request->amount,
                'note'           => $request->note ?? 'N/A',
                'payment_method' => $request->payment_method,
            ]);
        });

        return redirect()->route('purchases.index')->with('success', 'ការទូទាត់ត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    public function destroy(PurchasePayment $PurchasePayment)
    {
        $PurchasePayment->delete();
        return redirect()->route('Purchase_payments.index')->with('success', 'ទិន្នន័យត្រូវបានលុបដោយជោគជ័យ.');
    }
}
