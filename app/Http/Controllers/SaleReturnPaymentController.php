<?php

namespace App\Http\Controllers;

use App\Models\SaleReturnPayment;
use App\Models\SaleReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReturnPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $permissions = [
            'index' => 'បញ្ជីការទូទាត់ការប្រគល់វិក័យបត្រ',
            'edit' => 'កែរប្រែការទូទាត់ការប្រគល់វិក័យបត្រ',
            'destroy' => 'លុបការទូទាត់ការប្រគល់វិក័យបត្រ',
        ];

        foreach ($permissions as $method => $permission) {
            $this->middleware(function ($request, $next) use ($permission) {
                if (!auth()->user()->can($permission)) {
                    return redirect()->route('home')->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
                }
                return $next($request);
            })->only($method);
        }
    }

    public function index()
    {
        $payments = SaleReturnPayment::with('saleReturn')->paginate(10);
        return view('admin.sale_returns.sales_return_payments.index', compact('payments'));
    }

    public function create($sale_return_id)
    {
        $saleReturn = SaleReturn::findOrFail($sale_return_id);
        return view('admin.sale_returns.sales_return_payments.create', compact('saleReturn'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_return_id' => 'required|exists:sale_returns,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            $payment = SaleReturnPayment::create([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'sale_return_id' => $request->sale_return_id,
                'payment_method' => $request->payment_method,
            ]);

            $saleReturn = SaleReturn::findOrFail($request->sale_return_id);
            $due_amount = $saleReturn->due_amount - $request->amount;
            
            $payment_status = $due_amount == $saleReturn->total_amount ? 'មិនទាន់ទូទាត់' : ($due_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'បានទូទាត់រួច');

            $saleReturn->update([
                'paid_amount' => $saleReturn->paid_amount + $request->amount,
                'due_amount' => $due_amount,
                'payment_status' => $payment_status,
            ]);
        });

        return redirect()->route('sale_return_payments.index')->with('success', 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ.');
    }

    public function edit($sale_return_id, SaleReturnPayment $saleReturnPayment)
    {
        $saleReturn = SaleReturn::findOrFail($sale_return_id);
        return view('admin.sale_returns.sales_return_payments.edit', compact('saleReturnPayment', 'saleReturn'));
    }

    public function update(Request $request, SaleReturnPayment $saleReturnPayment)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $saleReturnPayment) {
            $saleReturn = $saleReturnPayment->saleReturn;
            $due_amount = ($saleReturn->due_amount + $saleReturnPayment->amount) - $request->amount;

            $payment_status = $due_amount == $saleReturn->total_amount ? 'មិនទាន់ទូទាត់' : ($due_amount > 0 ? 'បានទូទាត់ខ្លះ' : 'បានទូទាត់រួច');

            $saleReturn->update([
                'paid_amount' => ($saleReturn->paid_amount - $saleReturnPayment->amount) + $request->amount,
                'due_amount' => $due_amount,
                'payment_status' => $payment_status,
            ]);

            $saleReturnPayment->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'sale_return_id' => $request->sale_return_id,
                'payment_method' => $request->payment_method,
            ]);
        });

        return redirect()->route('sale_return_payments.index')->with('success', 'វិធីសាស្ត្រទូទាត់ត្រូវបានកែប្រែដោយជោគជ័យ.');
    }

    public function destroy(SaleReturnPayment $saleReturnPayment)
    {
        DB::transaction(function () use ($saleReturnPayment) {
            $saleReturn = $saleReturnPayment->saleReturn;

            $saleReturn->update([
                'paid_amount' => $saleReturn->paid_amount - $saleReturnPayment->amount,
                'due_amount' => $saleReturn->due_amount + $saleReturnPayment->amount,
                'payment_status' => $saleReturn->due_amount + $saleReturnPayment->amount == $saleReturn->total_amount ? 'មិនទាន់ទូទាត់' : ($saleReturn->due_amount + $saleReturnPayment->amount > 0 ? 'បានទូទាត់ខ្លះ' : 'បានទូទាត់រួច'),
            ]);

            $saleReturnPayment->delete();
        });

        return redirect()->route('sale_return_payments.index')->with('success', 'ទិន្នន័យត្រូវបានលុបដោយជោគជ័យ.');
    }
}
