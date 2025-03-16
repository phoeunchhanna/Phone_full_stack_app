<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class PermissionController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីការអនុញ្ញាត',
            'create' => 'បង្កើតការអនុញ្ញាត',
            'edit' => '	កែប្រែការអនុញ្ញាត',
            'destroy' => 'លុបការអនុញ្ញាត',
        ];

        foreach ($permissions as $action => $permission) {
            $this->middleware(function ($request, $next) use ($permission) {
                if (!auth()->user()->can($permission)) {
                    return back()->with('warning', 'អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ!');
                }

                return $next($request);
            })->only([$action]);
        }
    }

    public function index()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('permission.index', compact('permissions'));
    }
    public function create()
    {

        return view('permission.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3',
        ]);

        if ($validator->passes()) {
            Permission::create(['name'=> $request->name]);
            return redirect()->route('permissions.index')
                                  ->with('success', 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ!');
        }else{
            return redirect()->route('permissions.create')->with('warning', 'មានបញ្ហាក្នុងការបង្កើតទិន្នន័យ!');
        }
    }
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        // Return the view with the permission data
        return view('permission.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        // Find the permission by its ID
        $permission = Permission::findOrFail($id);

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,' . $id, // Correct table name and unique check
        ]);

        // Check if validation passes
        if ($validator->passes()) {
            // Update the permission name
            $permission->name = $request->name;
            $permission->save();  // Save the updated permission

            // Redirect back to the permissions index with a success message
            return redirect()->route('permissions.index')
                             ->with('success', 'ទិន្នន័យត្រូវបានអាប់ដេតដោយជោគជ័យ!');
        } else {
            // Redirect back to the edit page with an error message if validation fails
            return redirect()->route('permissions.edit', $id)
                             ->with('warning', 'មានបញ្ហាក្នុងការអាប់ដេតទិន្នន័យ!');
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $permission = Permission::find($id);

        // Check if the permission exists
        if ($permission == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission not found!',
            ]);
        }

        // Delete the permission
        $permission->delete();

        // Return success message
        return redirect()->route('permissions.index')->with('success', 'ទិន្នន័យការអនុញ្ញាតត្រូវបានលុបដោយជោគជ័យ។');
    }


    // public function destroy($id)
    // {
    //     $permission = Permission::findOrFail($id);

    //     $permission->delete();

    //     return redirect()->route('permissions.index')->with('success', 'ទិន្នន័យការអនុញ្ញាតត្រូវបានលុបដោយជោគជ័យ។');
    // }

}
