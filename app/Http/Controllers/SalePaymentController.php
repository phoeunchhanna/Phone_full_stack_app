<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalePaymentController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីការទូទាត់ការលក់',
            'edit' => 'កែរប្រែការទូទាត់ការលក់',
            'destroy' => 'លុបការទូទាត់ការលក់',

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
        $payments = SalePayment::with('sale')->paginate(10);
        return view('admin.sales.sale_payments.index', compact('payments'));
    }

    public function create($sale_id)
    {
        $sale = Sale::findOrFail($sale_id); // Fetch sale by sale_id
        return view('admin.sales.sale_payments.create', compact('sale')); // Pass the sale variable to the view
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            SalePayment::create([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'sale_id' => $request->sale_id,
                'payment_method' => $request->payment_method,
            ]);

            $sale = Sale::findOrFail($request->sale_id);

            $due_amount = $sale->due_amount - $request->amount;

            if ($due_amount == $sale->total_amount) {
                $payment_status = 'មិនទាន់ទូទាត់';
            } elseif ($due_amount > 0) {
                $payment_status = 'បានទូទាត់ខ្លះ';
            } else {
                $payment_status = 'បានទូទាត់រួច';
            }

            $sale->update([
                'paid_amount' => ($sale->paid_amount + $request->amount),
                'due_amount' => $due_amount,
                'payment_status' => $payment_status,
            ]);
        });

        return redirect()->back()->with('success', 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ.');
    }

    public function edit($sale_id, SalePayment $salePayment)
    {
        $sale = Sale::findOrFail($sale_id); // Fetch the sale using sale_id
        return view('admin.sales.sale_payments.edit', compact('salePayment', 'sale'));
    }

    public function update(Request $request, SalePayment $salePayment)
    {
        // Validation rules
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $salePayment) {
            $sale = $salePayment->sale;

            // Calculate due amount after payment
            $due_amount = ($sale->due_amount + $salePayment->amount) - $request->amount;

            // Determine payment status based on due amount
            if ($due_amount == $sale->total_amount) {
                $payment_status = 'មិនទាន់ទូទាត់';
            } elseif ($due_amount > 0) {
                $payment_status = 'បានទូទាត់ខ្លះ';
            } else {
                $payment_status = 'បានទូទាត់រួច';
            }

            // Update the sale details
            $sale->update([
                'paid_amount' => (($sale->paid_amount - $salePayment->amount) + $request->amount),
                'due_amount' => $due_amount,
                'payment_status' => $payment_status,
            ]);

            // Update the sale payment details
            $salePayment->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'sale_id' => $request->sale_id,
                'payment_method' => $request->payment_method,
            ]);
        });

        return redirect()->back()->with('success', 'វិធីសាស្ត្រទូទាត់ត្រូវបានបង្កើតដោយជោគជ័យ.');
    }

    public function destroy(SalePayment $salePayment)
    {
        $salePayment->delete();
        return redirect()->route('sale_payments.index')->with('success', 'ទិន្នន័យត្រូវបានលុបដោយជោគជ័យ.');
    }
}
