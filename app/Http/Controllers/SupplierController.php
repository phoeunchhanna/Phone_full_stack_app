<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីអ្នកផ្គត់ផ្គង់',
            'create' => 'បង្កើតអ្នកផ្គត់ផ្គង់',
            'edit' => 'កែប្រែអ្នកផ្គត់ផ្គង់',
            'destroy' => 'លុបអ្នកផ្គត់ផ្គង់',

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
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));

    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^\d{8,10}$/',
                Rule::unique('suppliers', 'phone'),
            ],
            'address' => 'nullable|string|max:500',
        ], [
            'phone.unique' => 'ទិន្នន័យនេះមានរួចហើយ!',
            'phone.regex' => 'លេខទូរស័ព្ទមិនត្រឹមត្រូវ!',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'ទិន្នន័យអ្នកផ្គត់ផ្គង់ត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^\d{8,10}$/',
                Rule::unique('suppliers', 'phone')->ignore($supplier->id),
            ],
            'address' => 'nullable|string|max:500',
        ], [
            'phone.unique' => 'ទិន្នន័យនេះមានរួចហើយ!',
            'phone.regex' => 'លេខទូរស័ព្ទមិនត្រឹមត្រូវ!',
        ]);
        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with('success', 'ទិន្នន័យអ្នកផ្គត់ផ្គង់ត្រូវបានកែប្រែដោយជោគជ័យ។.');
    }
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'ទិន្នន័យអ្នកផ្គត់ផ្គង់ត្រូវបានលុបដោយជោគជ័យ។');
    }

}
