<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ExpenseCategoryController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីប្រភេទការចំណាយ',
            'create' => 'បង្កើតប្រភេទការចំណាយ',
            'edit' => 'កែប្រែប្រភេទការចំណាយ',
            'destroy' => 'លុបប្រភេទការចំណាយ',
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
        $categories = ExpenseCategory::all();
        return view('admin.expense.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.expense.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('expense_categories', 'name'),
            ],
        ], [
            'name.unique' => 'ប្រភេទផលិតផលនេះមានរួចហើយ។',
        ]);

        ExpenseCategory::create([
            'name' => $request->name,
            'description' => $request->description ?? 'គ្មាន',
        ]);

        return redirect()->route('expense_categories.index')->with('success', 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    public function edit(ExpenseCategory $expense_category)
    {
        return view('admin.expense.categories.edit', compact('expense_category'));
    }

    public function update(Request $request, ExpenseCategory $expense_category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('expense_categories', 'name')->ignore($expense_category->id),
            ],
        ], [
            'name.unique' => 'ប្រភេទផលិតផលនេះមានរួចហើយ។',
        ]);

        $expense_category->update([
            'name' => $request->name,
            'description' => $request->description ?? 'គ្មាន',
        ]);

        return redirect()->route('expense_categories.index')->with('success', 'ទិន្នន័យត្រូវបានកែប្រែដោយជោគជ័យ!');
    }
    public function destroy(ExpenseCategory $expense_category)
    {
        $expense_category->delete();

        return redirect()->route('expense_categories.index')->with('success', 'ទិន្នន័យត្រូវបានលុបដោយជោគជ័យ.');
    }
}
