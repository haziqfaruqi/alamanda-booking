@extends('layouts.app', [
    'title' => 'Register - Alamanda Houseboat',
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
            <!-- Register Card -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-lg border border-zinc-200">
                <!-- Header -->
                <div class="text-center mb-8">
                    <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Alamanda Logo" class="w-20 h-20 mx-auto mb-4">
                    <h1 class="text-2xl font-bold text-zinc-900 mb-2">Create Account</h1>
                    <p class="text-zinc-500">Join Alamanda Houseboat today</p>
                </div>

                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <iconify-icon icon="lucide:alert-circle" width="18" class="text-red-600"></iconify-icon>
                        <span class="font-semibold text-red-700">Please fix the following errors:</span>
                    </div>
                    <ul class="ml-8 list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-red-600">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Register Form -->
                <form method="POST" action="{{ url('/register') }}">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                            placeholder="Enter your full name">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                            placeholder="0123456789">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                            placeholder="your@email.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                class="w-full px-4 py-3 pr-12 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                                placeholder="Create a password">
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                                <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <input id="confirmPass" name="password_confirmation" type="password" required
                                class="w-full px-4 py-3 pr-12 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                                placeholder="Confirm your password">
                            <button type="button" onclick="togglePassword('confirmPass')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                                <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 bg-zinc-900 text-white rounded-xl font-semibold hover:bg-zinc-800 transition-all hover:-translate-y-0.5">
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <p class="text-center mt-6 text-sm text-zinc-500">
                    Already have an account?
                    <a href="{{ url('/login') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold ml-1">Sign In</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const button = input.nextElementSibling;
    const icon = button.querySelector('iconify-icon');
    if (input.type === "password") {
        input.type = "text";
        icon.setAttribute('icon', 'lucide:eye-off');
    } else {
        input.type = "password";
        icon.setAttribute('icon', 'lucide:eye');
    }
}
</script>
@endpush
