<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីតួនាទីអ្នកប្រើប្រាស់',
            'create' => 'បង្កើតតួនាទីអ្នកប្រើប្រាស់',
            'edit' => 'កែប្រែតួនាទីអ្នកប្រើប្រាស់',
            'destroy' => 'លុបតួនាទីអ្នកប្រើប្រាស់',
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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('roles.create', [
            'permissions' => $permissions // Change 'permission' to 'permissions' here
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3',
        ]);

        if ($validator->passes()) {
            $role = Role::create(['name'=> $request->name]);

            if(!empty($request->permission)){
                foreach($request->permission as $name){
                    $role->givePermissionTo($name);
                }
            }

            return redirect()->route('roles.index')
                                  ->with('success', 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ!');
        }else{
            return redirect()->route('roles.create')->with('warning', 'មានបញ្ហាក្នុងការបង្កើតទិន្នន័យ!');
        }

    }


    // public function store(Request $request)
    // {
    //     // Validate the request
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|unique:roles|min:3',
    //     ]);

    //     if ($validator->passes()) {
    //         // Create the new role
    //         $role = Role::create(['name' => $request->name]);

    //         // Check if permissions are selected
    //         if (!empty($request->permission)) {
    //             foreach ($request->permission as $name) {
    //                 // Check if the permission exists
    //                 $permission = Permission::where('name', $name)->first();
    //                 if ($permission) {
    //                     $role->givePermissionTo($permission);
    //                 } else {
    //                     // Optional: Log or handle missing permissions
    //                     dd("Permission not found: $name");
    //                 }
    //             }
    //         }

    //         // Redirect with success message
    //         return redirect()->route('roles.index')
    //                          ->with('success', 'Role and permissions created successfully!');
    //     } else {
    //         // Redirect with error message
    //         return redirect()->route('roles.create')
    //                          ->with('error', 'There was an issue creating the role!');
    //     }
    // }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        // $permissions = Permission::orderby('name', 'ASC')->get();
        $permissions = Permission::all();

        // dd($hasPermissions);
        return view('roles.edit', [
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions,
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $role = Role::findOrFail($id);


    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|unique:roles,name,'.$id.',id|min:3',
    //     ]);

    //     if ($validator->passes()) {

    //         $role->name = $request->name;
    //         $role->save;

    //         if(!empty($request->permission)){
    //             $role->syncPermissions($request->permission);
    //         }else{
    //             $role->syncPermissions([]);
    //         }

    //         return redirect()->route('roles.index')
    //                               ->with('success', 'ទិន្នន័យត្រូវបានកែប្រែដោយជោគជ័យ!');
    //     }else{
    //         return redirect()->route('roles.edit', $id)->with('error', 'មានបញ្ហាក្នុងការកែប្រែទិន្នន័យ!');
    //     }
    // }

    public function update(Request $request, $id)
{
    $role = Role::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|unique:roles,name,'.$id.',id|min:3',
    ]);

    if ($validator->passes()) {

        $role->name = $request->name;
        $role->save();

        if (!empty($request->permission)) {
            $role->syncPermissions($request->permission);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')
                         ->with('success', 'ទិន្នន័យត្រូវបានកែប្រែដោយជោគជ័យ!');
    } else {
        return redirect()->route('roles.edit', $id)
                         ->with('warning', 'មានបញ្ហាក្នុងការកែប្រែទិន្នន័យ!');
    }
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if($role == null){
            return response()->json([
                'status' => false
            ]);
        }
        $role->delete();
        return redirect()->route('roles.index')
        ->with('success', 'ទិន្នន័យតួនារីអ្នកប្រើប្រាស់ត្រូវបានលុបដោយជោគជ័យ។!');

    }

    public function searchpermission(Request $request)
    {
        $search = $request->input('search');

        $permissions = Permission::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })->orderBy('name', 'ASC')->get();

        return view('roles.create', compact('permissions', 'search'));
    }

    public function searchpermissionEdit(Request $request)
    {
        $search = $request->input('search');

        $permissions = Permission::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })->orderBy('name', 'ASC')->get();

        return view('roles.edit', compact('permissions', 'search'));
    }


}


