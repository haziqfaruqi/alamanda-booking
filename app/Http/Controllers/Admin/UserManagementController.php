<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display the user list page
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->latest()
            ->get();

        return view('admin.user_dashboard', compact('users'));
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'user',
        ]);

        return back()->with('success', 'User added successfully!');
    }

    /**
     * Update a user
     */
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return back()->with('success', 'User updated successfully!');
    }

    /**
     * Delete a user
     */
    public function destroy($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }
}
