<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller
{
    // implements HasMiddleware
    // public static function middleware(): array{
    //     return [
    //         new Middleware('permission:បញ្ជីប្រភេទផលិតផល', only: [index]),
    //         new Middleware('permission:កែប្រែប្រភេទផលិតផល', only: [edit]),
    //         new Middleware('permission:បង្កើតភេទផលិតផល', only: [create]),
    //         new Middleware('permission:លុបប្រភេទផលិតផល', only: [destroy]),
    //     ];
    // }

    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->user()->can('បញ្ជីប្រភេទផលិតផល')) {
    //             return redirect()->route('categories.index')->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
    //         }
    //         return $next($request);
    //     })->only(['index']);

    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->user()->can('បង្កើតប្រភេទផលិតផល')) {
    //             return redirect()->route('categories.index')->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
    //         }
    //         return $next($request);
    //     })->only(['create']);

    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->user()->can('កែប្រែប្រភេទផលិតផល')) {
    //             return redirect()->route('categories.index')->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
    //         }
    //         return $next($request);
    //     })->only(['edit']);

    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->user()->can('លុបប្រភេទផលិតផល')) {
    //             return redirect()->route('categories.index')->with('error', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
    //         }
    //         return $next($request);
    //     })->only(['destroy']);
    // }



    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីប្រភេទផលិតផល',
            'create' => 'បង្កើតប្រភេទផលិតផល',
            'edit' => 'កែប្រែប្រភេទផលិតផល',
            'destroy' => 'លុបប្រភេទផលិតផល',
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
        $categories = Category::all();
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
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'nullable|string|max:255',
        ],[
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
