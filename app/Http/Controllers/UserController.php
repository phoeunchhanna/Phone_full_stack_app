<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;


use Toastr;

class UserController extends Controller
{


    public function __construct()
    {
        $permissions = [
            'index' => 'បញ្ជីអ្នកប្រើប្រាស់',
            'create' => 'បង្កើតអ្នកប្រើប្រាស់',
            'edit' => 'កែប្រែអ្នកប្រើប្រាស់',
            'destroy' => 'លុបអ្នកប្រើប្រាស់',
            'show' => 'ព័ត៌មានអ្នកប្រើប្រាស់',
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

    // Admin view: list all users
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Admin view: show create user form
    public function create()
    {
        $roles = Role::orderby('name', 'ASC')->get();
        return view('admin.users.create', [
            'roles' => $roles
        ]);
    }

    // Admin: store new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'user_type' => 'required|string',
        ]);

        $avatarPath = 'avatars/avatar-01.jpg';
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'avatar' => $avatarPath,
        ]);

        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User added successfully!');
    }

    // Admin: show edit user form
    public function edit($id)
    {
        $user = User::findOrFail($id);



        $roles = Role::orderby('name', 'ASC')->get();
        $hasRoles = $user->roles->pluck('id');

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'hasRoles' => $hasRoles
        ]);
    }

    // Admin: update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'user_type' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            if ($avatarPath !== 'avatars/avatar-01.jpg' && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'user_type' => $request->user_type,
            'avatar' => $avatarPath,
        ]);


        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'ទិន្នន័យរបស់អ្នកត្រូវបានកែរប្រែដោយជោគជ័យ!');
    }


    // public function update(Request $request, $id){

    //     $user = User::findOrFail($id);
    //     $validator = validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email, '.$id.', id',
    //         'password' => 'nullable|string|min:6|confirmed',
    //         'user_type' => 'required|string',
    //         'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);


    //     $avatarPath = $user->avatar;
    //     if ($request->hasFile('avatar')) {
    //         if ($avatarPath !== 'avatars/avatar-01.jpg' && Storage::disk('public')->exists($avatarPath)) {
    //             Storage::disk('public')->delete($avatarPath);
    //         }
    //         $avatarPath = $request->file('avatar')->store('avatars', 'public');
    //     }

    //     if($validator->false()){
    //         return redirect()->route('users.edit')->with('error', 'មានបញ្ហាក្នុងការកែប្រែទិន្នន័យ!');
    //     }

    //     $user->update([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => $request->password ? Hash::make($request->password) : $user->password,
    //         'user_type' => $request->user_type,
    //         'avatar' => $avatarPath,
    //     ]);



    //     return redirect()->route('users.index')->with('success', 'ទិន្នន័យរបស់អ្នកត្រូវបានកែរប្រែដោយជោគជ័យ!');

    // }




    public function show(User $user)
    {

        return view('admin.users.show', compact('user'));
    }
    // Admin: delete user
    public function destroy(User $user)
    {
        // Check if the logged-in user is trying to delete their own account
        if (auth()->user()->id === $user->id) {
            Toastr::error('You cannot delete your own account.', 'Error');
            return redirect()->route('users.index');
        }

        // Proceed with the deletion if the user is not deleting their own account
        $user->delete();

        Toastr::success('User deleted successfully!', 'Success');
        return redirect()->route('users.index');
    }

    // User profile: show profile page
    public function showProfile()
    {
        return view('admin.users.profile.show', ['user' => Auth::user()]);
    }
    // User profile: show edit form
    public function editProfile()
    {
        return view('admin.users.profile', ['user' => Auth::user()]);
    }
    // User profile: update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $avatarPath = $user->avatar; // Default to current avatar path

        if ($request->hasFile('avatar')) {
            if ($avatarPath !== 'avatars/avatar-01.jpg' && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $avatarPath, // Store new avatar path or keep existing
        ]);

        return redirect()->back()->with('success', 'បានកែប្រែព័ត៌មានផ្ទាល់ខ្លួនដោយជោគជ័យ។');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'ពាក្យសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវទេ។']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'បានកែប្រែពាក្យសម្ងាត់ដោយជោគជ័យ។');
    }
}
