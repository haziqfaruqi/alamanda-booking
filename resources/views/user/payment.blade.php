@extends('layouts.app', [
    'title' => 'Payment - Alamanda Houseboat',
    'header' => [
        'links' => [
            'Home' => '/home',
            'Book Now' => '/booking',
        ],
    ],
])

@section('content')
    <!-- Main Content -->
    <div class="pt-28 pb-16 min-h-screen">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900 mb-2">Complete Payment</h1>
                <p class="text-zinc-500">Select your payment method and complete the transaction</p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-3">
                <iconify-icon icon="lucide:check-circle" width="20"></iconify-icon>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <div class="flex items-center gap-3 mb-2">
                    <iconify-icon icon="lucide:alert-circle" width="18"></iconify-icon>
                    <span class="font-semibold">Please fix the following errors:</span>
                </div>
                <ul class="ml-8 list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Booking Summary Card -->
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden mb-6">
                <!-- Card Header -->
                <div class="bg-teal-600 px-6 py-4 border-b border-zinc-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <iconify-icon icon="lucide:anchor" width="24" class="text-white"></iconify-icon>
                        </div>
                        <div>
                            <h2 class="font-semibold text-white">Booking Summary</h2>
                            <p class="text-sm text-teal-100">{{ $booking->package->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <!-- Booking Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                <iconify-icon icon="lucide:calendar" width="18" class="text-indigo-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-xs text-zinc-500">Check-in</p>
                                <p class="text-sm font-medium text-zinc-900">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                <iconify-icon icon="lucide:calendar-check" width="18" class="text-indigo-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-xs text-zinc-500">Check-out</p>
                                <p class="text-sm font-medium text-zinc-900">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                <iconify-icon icon="lucide:clock" width="18" class="text-indigo-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-xs text-zinc-500">Duration</p>
                                <p class="text-sm font-medium text-zinc-900">{{ $booking->duration }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                <iconify-icon icon="lucide:users" width="18" class="text-indigo-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-xs text-zinc-500">Total Guests</p>
                                <p class="text-sm font-medium text-zinc-900">{{ $booking->total_guests }} pax</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="mb-6 pb-6 border-b border-zinc-200">
                        <div class="flex items-center gap-3 mb-3">
                            <iconify-icon icon="lucide:user" width="16" class="text-zinc-500"></iconify-icon>
                            <span class="text-sm font-semibold text-zinc-700">Contact Person</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-zinc-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="lucide:user" width="20" class="text-zinc-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="font-medium text-zinc-900">{{ $booking->contact_name }}</p>
                                <p class="text-sm text-zinc-500">{{ $booking->contact_phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="bg-teal-600 rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-teal-100">Total Amount Due</span>
                            <span class="text-2xl font-bold text-white">RM {{ number_format($booking->total_price, 0, ',', ',') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method Card -->
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
                <!-- Card Header -->
                <div class="bg-teal-600 px-6 py-4 border-b border-zinc-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <iconify-icon icon="lucide:credit-card" width="24" class="text-white"></iconify-icon>
                        </div>
                        <div>
                            <h2 class="font-semibold text-white">Select Payment Method</h2>
                            <p class="text-sm text-teal-100">Choose how you want to pay</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <form method="POST" action="{{ route('payment.process', ['booking' => $booking->id]) }}">
                        @csrf

                        <!-- Payment Method Options -->
                        <div class="grid grid-cols-1 gap-4 mb-6">
                            <!-- ToyyibPay -->
                            <div class="relative">
                                <input type="radio" name="payment_method" value="toyyibpay" id="method_toyyibpay" class="peer" checked>
                                <label for="method_toyyibpay"
                                       class="flex items-center gap-4 p-6 border-2 border-zinc-200 rounded-2xl cursor-pointer transition-all peer-checked:border-teal-500 peer-checked:bg-teal-50 hover:border-teal-300">
                                    <div class="w-16 h-16 bg-zinc-100 rounded-xl flex items-center justify-center peer-checked:bg-teal-100 flex-shrink-0">
                                        <iconify-icon icon="lucide:wallet" width="32" class="text-zinc-600 peer-checked:text-teal-600"></iconify-icon>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-zinc-900 text-lg">ToyyibPay</p>
                                        <p class="text-sm text-zinc-500">Multiple payment options - FPX, Credit/Debit Card, Touch 'n Go, GrabPay, Boost</p>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 border-zinc-300 peer-checked:border-teal-500 peer-checked:bg-teal-500 flex items-center justify-center flex-shrink-0">
                                        <iconify-icon icon="lucide:check" width="14" class="text-white opacity-0 peer-checked:opacity-100"></iconify-icon>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- ToyyibPay Info -->
                        <div class="mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                                <div class="flex items-start gap-3">
                                    <iconify-icon icon="lucide:info" width="20" class="text-blue-600 flex-shrink-0 mt-0.5"></iconify-icon>
                                    <div>
                                        <p class="font-semibold text-blue-900">Secure Payment with ToyyibPay</p>
                                        <p class="text-sm text-blue-700">You will be redirected to ToyyibPay's secure payment gateway to complete your transaction.</p>
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-white rounded-lg text-xs text-zinc-600">
                                                <iconify-icon icon="lucide:credit-card" width="14"></iconify-icon> FPX
                                            </span>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-white rounded-lg text-xs text-zinc-600">
                                                <iconify-icon icon="lucide:credit-card" width="14"></iconify-icon> Credit/Debit Card
                                            </span>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-white rounded-lg text-xs text-zinc-600">
                                                <iconify-icon icon="lucide:wallet" width="14"></iconify-icon> E-Wallets
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-3 pt-4 border-t border-zinc-200">
                            <a href="{{ route('invoice.show', ['booking' => $booking->id]) }}"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-zinc-100 text-zinc-700 rounded-xl text-sm font-medium hover:bg-zinc-200 transition-all">
                                <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                                Back to Invoice
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-8 py-3 bg-teal-600 text-white rounded-xl text-sm font-semibold hover:bg-teal-700 transition-all hover:-translate-y-0.5 flex-1 md:flex-none justify-center">
                                <iconify-icon icon="lucide:shield-check" width="18"></iconify-icon>
                                Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
