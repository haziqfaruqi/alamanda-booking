<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin list page
     */
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->latest()
            ->get();

        return view('admin.admin_dashboard', compact('admins'));
    }

    /**
     * Store a new admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'admin',
        ]);

        return back()->with('success', 'Admin added successfully!');
    }

    /**
     * Update an admin
     */
    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        if (!empty($validated['password'])) {
            $admin->password = bcrypt($validated['password']);
        }

        $admin->save();

        return back()->with('success', 'Admin updated successfully!');
    }

    /**
     * Delete an admin
     */
    public function destroy($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        // Prevent deleting the currently logged in admin
        if ($admin->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $admin->delete();

        return back()->with('success', 'Admin deleted successfully!');
    }
}
