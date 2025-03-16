<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ExpenseController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីការចំណាយ',
            'create' => 'បង្កើតការចំណាយ',
            'edit' => 'កែប្រែការចំណាយ',
            'destroy' => 'លុបការចំណាយ',
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
        $expenses = Expense::with('category')->orderBy('created_at', 'desc')->get(); // Show newest first
        return view('admin.expense.index', compact('expenses'));
    }
    

    public function create()
    {
        $categories = ExpenseCategory::all();
        return view('admin.expense.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'amount' => 'required|integer',
            'details' => 'nullable|string'
        ]);

        $reference = $request->input('reference', 'EXP-' . strtoupper(Str::random(6)));

        Expense::create([
            'category_id' => $request->category_id,
            'date' => $request->date,
            'reference' => $reference ?? 'EXP-10000' . mt_rand(1, 100000),
            'amount' => $request->amount,
            'details' => $request->details,
        ]);

        return redirect()->route('expenses.index')->with('success', 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ!.');
    }

    public function show(Expense $expense)
    {
        return view('admin.expense.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::all();
        return view('admin.expense.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'amount' => 'required|integer',
            'details' => 'nullable|string'
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'ទិន្នន័យត្រូវបានកែរប្រែដោយជោគជ័យ.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'ទិន្នន័យត្រូវបានលុបដោយជោគជ័យ.');
    }
}
