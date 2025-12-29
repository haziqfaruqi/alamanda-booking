<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin()
    {
        return view('user.alamanda_login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            // Redirect to intended page or booking for regular users
            return redirect()->intended('/booking');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show register page
     */
    public function showRegister()
    {
        return view('user.alamanda_register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect('/booking');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/alamanda_home');
    }

    /**
     * Show forgot password page
     */
    public function showForgotPassword()
    {
        return view('user.forgot_password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Generate a random token
            $token = Str::random(60);

            // Store token in remember_token field (we'll use it for password reset)
            $user->remember_token = hash('sha256', $token);
            $user->save();

            // Create reset link
            $resetUrl = route('password.reset', $token);

            // Send password reset email
            Mail::to($user->email)->send(new PasswordResetMail($resetUrl, $user->name));
        }

        // Always show success message to prevent email enumeration
        return back()->with('status', 'If an account exists with that email, a password reset link has been sent.');
    }

    /**
     * Show reset password page
     */
    public function showResetPassword($token)
    {
        return view('user.reset_password', ['token' => $token]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Find user by email and verify token
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->remember_token !== hash('sha256', $request->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Your password has been reset successfully. Please login with your new password.');
    }
}
