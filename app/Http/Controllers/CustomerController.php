<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីអតិថិជន',
            'create' => 'បង្កើតអតិថិជន',
            'edit' => 'កែប្រែអតិថិជន',
            'destroy' => 'លុបអតិថិជន',

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
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
    }
    public function create()
    {
        return view('admin.customers.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^\d{8,10}$/',
                Rule::unique('customers', 'phone'),
            ],
            'address' => 'nullable|string|max:500',
        ], [
            'phone.unique' => 'លេខទូរស័ព្ទនេះមានរួចហើយ!',
            'phone.regex' => 'លេខទូរស័ព្ទមិនត្រឹមត្រូវ!',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('warning', 'ទិន្នន័យនេះត្រូវបានបង្កើតរួចរាល់ម្តងរួចមកហើយ!');
        }

        Customer::create($request->all());

        return redirect()->back()->with('success', 'ទិន្នន័យអតិថិជនត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^\d{8,10}$/',
                Rule::unique('customers', 'phone')->ignore($customer->id),
            ],
            'address' => 'nullable|string|max:500',
        ], [
            'phone.unique' => 'លេខទូរស័ព្ទនេះមានរួចហើយ!',
            'phone.regex' => 'លេខទូរស័ព្ទមិនត្រឹមត្រូវ!',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'ទិន្នន័យអតិថិជនត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'ទិន្នន័យអតិថិជនត្រូវបានលុបដោយជោគជ័យ។');
    }

}
