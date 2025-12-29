@extends('layouts.app', [
    'title' => 'Forgot Password - Alamanda Houseboat',
    'header' => [
        'links' => [
            'Home' => '/home',
            'Book Now' => '/booking',
        ],
    ],
])

@section('content')
    <div class="min-h-screen flex items-center justify-center px-6 py-24">
        <div class="w-full max-w-md">
            <!-- Forgot Password Card -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-lg border border-zinc-200">
                <!-- Header -->
                <div class="text-center mb-8">
                    <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Alamanda Logo" class="w-20 h-20 mx-auto mb-4">
                    <h1 class="text-2xl font-bold text-zinc-900 mb-2">Forgot Password</h1>
                    <p class="text-zinc-500">Enter your email to receive a reset link</p>
                </div>

                @if(session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm flex items-center gap-3">
                    <iconify-icon icon="lucide:check-circle" width="18" class="text-emerald-600"></iconify-icon>
                    <span class="text-emerald-700">{{ session('status') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm flex items-center gap-3">
                    <iconify-icon icon="lucide:alert-circle" width="18" class="text-red-600"></iconify-icon>
                    <span class="text-red-700">{{ $errors->first() }}</span>
                </div>
                @endif

                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                            placeholder="your@email.com">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 bg-zinc-900 text-white rounded-xl font-semibold hover:bg-zinc-800 transition-all hover:-translate-y-0.5">
                        Send Reset Link
                    </button>
                </form>

                <!-- Back to Login -->
                <p class="text-center mt-6 text-sm text-zinc-500">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold ml-1">Sign In</a>
                </p>
            </div>
        </div>
    </div>
@endsection
