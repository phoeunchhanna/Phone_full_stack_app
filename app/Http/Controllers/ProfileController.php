<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{

    // បង្ហាញទំព័រដើម្បីកែប្រែព័ត៌មានផ្ទាល់ខ្លួន
    public function edit()
    {
        return view('admin.users.profile');
    }

    // អនុវត្តការកែប្រែព័ត៌មានផ្ទាល់ខ្លួន
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'image' => 'nullable|image|max:500',
        ]);

        $user = auth()->user();

        // បញ្ចូលរូបភាពថ្មី
        if ($request->hasFile('image')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $user->avatar = $request->file('image')->store('avatars');
        }

        // កែប្រែព័ត៌មានអ្នកប្រើប្រាស់
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'បានកែប្រែព័ត៌មានផ្ទាល់ខ្លួនដោយជោគជ័យ។');
    }

    /**
     * កែប្រែពាក្យសម្ងាត់អ្នកប្រើប្រាស់
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        // ផ្ទៀងផ្ទាត់ពាក្យសម្ងាត់បច្ចុប្បន្ន
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'ពាក្យសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវទេ។']);
        }

        // កែប្រែទៅពាក្យសម្ងាត់ថ្មី
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'បានកែប្រែពាក្យសម្ងាត់ដោយជោគជ័យ។');
    }
}
