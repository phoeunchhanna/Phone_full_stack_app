<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct()
    {
        $permissions = [
            'index'   => 'បញ្ជីប្រភេទផលិតផល',
            'create'  => 'បង្កើតប្រភេទផលិតផល',
            'edit'    => 'កែប្រែប្រភេទផលិតផល',
            'destroy' => 'លុបប្រភេទផលិតផល',
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
        $categories = Category::orderBy('created_at', 'desc')->get(); // Show newest first
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Allow Khmer or English letters (must include at least one letter), may include English digits, but reject if only digits or contains Khmer digits
                'regex:/^(?=.*[\x{1780}-\x{17A2}a-zA-Z])(?!^[\x{17E0}-\x{17E9}0-9\s]+$)[\x{1780}-\x{17FF}a-zA-Z0-9\s]+$/u',
                Rule::unique('categories', 'name'),
            ],
        ], [
            'name.unique' => 'ប្រភេទផលិតផលនេះមានរួចហើយ។',
        ]);

        Category::create([
            'name'        => $request->name,
            'description' => $request->description ?? 'គ្មាន',
        ]);

        return redirect()->back()->with('success', 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => [
                'required',
                'string',
                'max:255',
                // Allow Khmer or English letters (must include at least one letter), may include English digits, but reject if only digits or contains Khmer digits
               'regex:/^(?=.*[\x{1780}-\x{17A2}a-zA-Z])(?!^[\x{17E0}-\x{17E9}0-9\s]+$)[\x{1780}-\x{17FF}a-zA-Z0-9\s]+$/u',
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'nullable|string|max:255',
        ], [
            'name.unique' => 'ម៉ាកយីហោនេះមានរួចហើយ។',
        ]);

        $category->update([
            'name'        => $request->name,
            'description' => $request->description ?? 'គ្មាន',
        ]);

        return redirect()->route('categories.index')->with('success', 'ទិន្នន័យត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'ទិន្នន័យត្រូវបានលុបដោយជោគជ័យ.');
    }
}
