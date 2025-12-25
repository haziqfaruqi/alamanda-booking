@extends('layouts.app', [
    'title' => 'Edit Profile - Alamanda Houseboat',
    'header' => [
        'links' => [
            'Home' => '/home',
            'Book Now' => '/booking',
        ],
    ],
])

@section('content')
    <!-- Main Content -->
    <div class="pt-32 pb-24 px-6">
        <div class="max-w-xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-semibold tracking-tight text-zinc-900 mb-2">Edit Profile</h1>
                <p class="text-zinc-500">Update your account information</p>
            </div>

            @session('success')
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-3">
                <iconify-icon icon="lucide:check-circle" width="20"></iconify-icon>
                {{ session('success') }}
            </div>
            @endsession

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <div class="flex items-center gap-3 mb-2">
                    <iconify-icon icon="lucide:alert-circle" width="20"></iconify-icon>
                    <span class="font-semibold">Please fix the following errors:</span>
                </div>
                <ul class="ml-8 list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-zinc-200">
                <form method="POST" action="{{ route('update-profile') }}">
                    @csrf

                    <!-- Profile Info -->
                    <div class="space-y-6 mb-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <iconify-icon icon="lucide:user" width="20" class="text-indigo-600"></iconify-icon>
                            </div>
                            <h2 class="text-lg font-semibold text-zinc-900">Personal Information</h2>
                        </div>

                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required
                                class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="border-t border-zinc-100 pt-6">
                        <button type="button" onclick="togglePassword()" class="flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors mb-4">
                            <iconify-icon icon="lucide:lock" width="16"></iconify-icon>
                            Change Password
                        </button>

                        <div id="passwordSection" class="hidden space-y-4">
                            <!-- Current Password -->
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-2">Current Password</label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="currentPass"
                                        class="w-full px-4 py-3 pr-12 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                    <button type="button" onclick="toggleShow('currentPass')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                                        <iconify-icon icon="lucide:eye" width="18"></iconify-icon>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-2">New Password</label>
                                <div class="relative">
                                    <input type="password" name="new_password" id="newPass"
                                        class="w-full px-4 py-3 pr-12 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                    <button type="button" onclick="toggleShow('newPass')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                                        <iconify-icon icon="lucide:eye" width="18"></iconify-icon>
                                    </button>
                                </div>
                                @error('new_password')
                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm New Password -->
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-2">Confirm New Password</label>
                                <div class="relative">
                                    <input type="password" name="new_password_confirmation" id="confirmPass"
                                        class="w-full px-4 py-3 pr-12 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                    <button type="button" onclick="toggleShow('confirmPass')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600">
                                        <iconify-icon icon="lucide:eye" width="18"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full mt-8 py-4 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <iconify-icon icon="lucide:save" width="18"></iconify-icon>
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Toggle password section
function togglePassword() {
    const sec = document.getElementById("passwordSection");
    sec.classList.toggle("hidden");
}

// Show / hide password
function toggleShow(id) {
    const input = document.getElementById(id);
    const icon = input.nextElementSibling.querySelector('iconify-icon');
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
