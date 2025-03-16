<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Toastr;

class BrandController extends Controller
{



    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីម៉ាកយីហោ',
            'create' => 'បង្កើតម៉ាកយីហោ',
            'edit' => 'កែប្រែម៉ាកយីហោ',
            'destroy' => 'លុបម៉ាកយីហោ',
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
        $brands = Brand::orderBy('created_at', 'desc')->get(); // Show newest first
        return view('admin.brands.index', compact('brands'));
    }
    

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name'),
            ],
        ], [
            'name.unique' => 'ម៉ាកយីហោនេះមានរួចហើយ។',
        ]);

        Brand::create([
            'name' => $request->name,
            'description' => $request->description ?? 'គ្មាន',
        ]);

        return redirect()->back()->with('success', 'ម៉ាកយីហោត្រូវបានបន្ថែមដោយជោគជ័យ។');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name'        => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($brand->id),
            ],
            'description' => 'nullable|string|max:255',
        ],[
            'name.unique' => 'ប្រភេទផលិតផលនេះមានរួចហើយ។',
        ]);

        $brand->update([
            'name'        => $request->name,
            'description' => $request->description ?? 'គ្មាន',
        ]);
        Toastr::success('ម៉ាកយីហោត្រូវបានកែប្រែដោយជោគជ័យ។', 'ជោគជ័យ');
        return redirect()->route('brands.index')->with('success', 'ម៉ាកយីហោត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'ទិន្នន័យត្រូវបានលុបដោយជោគជ័យ.');
    }

    public function show(Brand $brand)
    {
        return view('admin.brands.show', compact('brand'));
    }
}
