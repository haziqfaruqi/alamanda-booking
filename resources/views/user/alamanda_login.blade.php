@extends('layouts.app', [
    'title' => 'Login - Alamanda Houseboat',
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
            <!-- Login Card -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-lg border border-zinc-200">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <iconify-icon icon="lucide:anchor" width="32" class="text-indigo-600"></iconify-icon>
                    </div>
                    <h1 class="text-2xl font-bold text-zinc-900 mb-2">Welcome Back</h1>
                    <p class="text-zinc-500">Sign in to your account</p>
                </div>

                @if(request()->get('redirect') == 'booking')
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm flex items-center gap-3">
                    <iconify-icon icon="lucide:lock" width="18" class="text-amber-600"></iconify-icon>
                    <span class="text-amber-700">Please login first to make a booking</span>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm flex items-center gap-3">
                    <iconify-icon icon="lucide:alert-circle" width="18" class="text-red-600"></iconify-icon>
                    <span class="text-red-700">{{ $errors->first() }}</span>
                </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ url('/login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                            placeholder="your@email.com">
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                class="w-full px-4 py-3 pr-12 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                                placeholder="Enter your password">
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                                <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- Forgot Password -->
                    <div class="mb-6 text-right">
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Forgot Password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 bg-zinc-900 text-white rounded-xl font-semibold hover:bg-zinc-800 transition-all hover:-translate-y-0.5">
                        Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center gap-4 my-6">
                    <div class="flex-1 h-px bg-zinc-200"></div>
                    <span class="text-xs text-zinc-400">OR</span>
                    <div class="flex-1 h-px bg-zinc-200"></div>
                </div>

                <!-- Social Login -->
                <button class="w-full py-3 bg-white border border-zinc-300 rounded-xl font-medium hover:bg-zinc-50 transition-all flex items-center justify-center gap-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/Gmail_Icon.png" class="w-5 h-5" alt="Gmail">
                    Continue with Google
                </button>

                <!-- Register Link -->
                <p class="text-center mt-6 text-sm text-zinc-500">
                    Don't have an account?
                    <a href="{{ url('/register') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold ml-1">Sign Up</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const p = document.getElementById("password");
    const icon = document.querySelector("#password + button iconify-icon");
    if (p.type === "password") {
        p.type = "text";
        icon.setAttribute('icon', 'lucide:eye-off');
    } else {
        p.type = "password";
        icon.setAttribute('icon', 'lucide:eye');
    }
}
</script>
@endpush
