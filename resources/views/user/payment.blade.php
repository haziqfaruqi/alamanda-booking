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
                <div class="bg-gradient-to-r from-indigo-50 to-amber-50 px-6 py-4 border-b border-zinc-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <iconify-icon icon="lucide:anchor" width="24" class="text-indigo-600"></iconify-icon>
                        </div>
                        <div>
                            <h2 class="font-semibold text-zinc-900">Booking Summary</h2>
                            <p class="text-sm text-zinc-500">{{ $booking->package->name ?? 'N/A' }}</p>
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
                    <div class="bg-indigo-50 rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-indigo-700">Total Amount Due</span>
                            <span class="text-2xl font-bold text-indigo-900">RM {{ number_format($booking->total_price, 0, ',', ',') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method Card -->
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-zinc-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                            <iconify-icon icon="lucide:credit-card" width="24" class="text-amber-600"></iconify-icon>
                        </div>
                        <div>
                            <h2 class="font-semibold text-zinc-900">Select Payment Method</h2>
                            <p class="text-sm text-zinc-500">Choose how you want to pay</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <form method="POST" action="{{ route('payment.process', ['booking' => $booking->id]) }}"
                      enctype="multipart/form-data"
                      x-data="{
                        selectedMethod: 'bank_transfer'
                    }">
                        @csrf

                        <!-- Payment Method Options -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <!-- Bank Transfer -->
                            <div class="relative">
                                <input type="radio" name="payment_method" value="bank_transfer" id="method_bank" class="peer" checked @change="selectedMethod = 'bank_transfer'">
                                <label for="method_bank"
                                       class="flex flex-col items-center gap-3 p-4 border-2 border-zinc-200 rounded-2xl cursor-pointer transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:border-amber-300">
                                    <div class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center peer-checked:bg-amber-100">
                                        <iconify-icon icon="lucide:building-2" width="24" class="text-zinc-600 peer-checked:text-amber-600"></iconify-icon>
                                    </div>
                                    <div class="text-center">
                                        <p class="font-semibold text-zinc-900 text-sm">Bank Transfer</p>
                                        <p class="text-xs text-zinc-500">Direct bank transfer</p>
                                    </div>
                                </label>
                            </div>

                            <!-- FPX -->
                            <div class="relative">
                                <input type="radio" name="payment_method" value="fpx" id="method_fpx" class="peer" @change="selectedMethod = 'fpx'">
                                <label for="method_fpx"
                                       class="flex flex-col items-center gap-3 p-4 border-2 border-zinc-200 rounded-2xl cursor-pointer transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:border-amber-300">
                                    <div class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center peer-checked:bg-amber-100">
                                        <iconify-icon icon="lucide:landmark" width="24" class="text-zinc-600 peer-checked:text-amber-600"></iconify-icon>
                                    </div>
                                    <div class="text-center">
                                        <p class="font-semibold text-zinc-900 text-sm">FPX</p>
                                        <p class="text-xs text-zinc-500">Online banking</p>
                                    </div>
                                </label>
                            </div>

                            <!-- ToyyibPay -->
                            <div class="relative">
                                <input type="radio" name="payment_method" value="toyyibpay" id="method_toyyibpay" class="peer" @change="selectedMethod = 'toyyibpay'">
                                <label for="method_toyyibpay"
                                       class="flex flex-col items-center gap-3 p-4 border-2 border-zinc-200 rounded-2xl cursor-pointer transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:border-amber-300">
                                    <div class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center peer-checked:bg-amber-100">
                                        <iconify-icon icon="lucide:wallet" width="24" class="text-zinc-600 peer-checked:text-amber-600"></iconify-icon>
                                    </div>
                                    <div class="text-center">
                                        <p class="font-semibold text-zinc-900 text-sm">ToyyibPay</p>
                                        <p class="text-xs text-zinc-500">Multiple payment options</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Bank Transfer Details -->
                        <div x-show="selectedMethod === 'bank_transfer'" class="mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                                <div class="flex items-start gap-3 mb-4">
                                    <iconify-icon icon="lucide:info" width="20" class="text-blue-600 flex-shrink-0 mt-0.5"></iconify-icon>
                                    <div>
                                        <p class="font-semibold text-blue-900">Bank Transfer Instructions</p>
                                        <p class="text-sm text-blue-700">Transfer the total amount to the account below and upload your receipt.</p>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-4 border border-blue-100">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <p class="text-zinc-500">Bank</p>
                                            <p class="font-semibold text-zinc-900">HONG LEONG BANK</p>
                                        </div>
                                        <div>
                                            <p class="text-zinc-500">Account Name</p>
                                            <p class="font-semibold text-zinc-900">SYAHDINA AIMAN BT KAMARUDDIN</p>
                                        </div>
                                        <div>
                                            <p class="text-zinc-500">Account Number</p>
                                            <p class="font-semibold text-zinc-900 font-mono">305-51-060609</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Form -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 mb-2">Upload Receipt *</label>
                                    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf"
                                           class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                    <p class="text-xs text-zinc-500 mt-1">JPG, PNG, PDF (Max 5MB)</p>
                                    @error('receipt')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 mb-2">Reference Number *</label>
                                    <input type="text" name="reference_number" placeholder="Enter your transaction reference"
                                           class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                                    @error('reference_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- FPX Details -->
                        <div x-show="selectedMethod === 'fpx'" class="mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                                <div class="flex items-start gap-3">
                                    <iconify-icon icon="lucide:info" width="20" class="text-blue-600 flex-shrink-0 mt-0.5"></iconify-icon>
                                    <div>
                                        <p class="font-semibold text-blue-900">FPX Online Banking</p>
                                        <p class="text-sm text-blue-700">You will be redirected to the FPX payment gateway to complete your payment securely.</p>
                                        <p class="text-sm text-blue-700 mt-2">Supported banks: Maybank, CIMB, Public Bank, RHB, Hong Leong, and more.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ToyyibPay Details -->
                        <div x-show="selectedMethod === 'toyyibpay'" class="mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                                <div class="flex items-start gap-3">
                                    <iconify-icon icon="lucide:info" width="20" class="text-blue-600 flex-shrink-0 mt-0.5"></iconify-icon>
                                    <div>
                                        <p class="font-semibold text-blue-900">ToyyibPay Payment Gateway</p>
                                        <p class="text-sm text-blue-700">You will be redirected to ToyyibPay secure payment gateway.</p>
                                        <p class="text-sm text-zinc-600 mt-2">Supported:</p>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="inline-flex items-center px-2 py-1 bg-white rounded-lg text-xs text-zinc-600">FPX</span>
                                            <span class="inline-flex items-center px-2 py-1 bg-white rounded-lg text-xs text-zinc-600">Credit/Debit Card</span>
                                            <span class="inline-flex items-center px-2 py-1 bg-white rounded-lg text-xs text-zinc-600">Touch 'n Go</span>
                                            <span class="inline-flex items-center px-2 py-1 bg-white rounded-lg text-xs text-zinc-600">GrabPay</span>
                                            <span class="inline-flex items-center px-2 py-1 bg-white rounded-lg text-xs text-zinc-600">Boost</span>
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
                                    class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl text-sm font-semibold hover:from-amber-600 hover:to-orange-600 transition-all hover:-translate-y-0.5 flex-1 md:flex-none justify-center">
                                <iconify-icon icon="lucide:shield-check" width="18"></iconify-icon>
                                Confirm Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
