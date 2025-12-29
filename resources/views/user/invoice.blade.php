@extends('layouts.app', [
    'title' => 'Invoice - Alamanda Houseboat',
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
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900 mb-2">Invoice</h1>
                <p class="text-zinc-500">Please review your invoice details and proceed to payment</p>
            </div>

            <!-- Invoice Card -->
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
                <!-- Invoice Header -->
                <div class="bg-indigo-600 px-6 py-4 border-b border-zinc-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <iconify-icon icon="lucide:file-text" width="24" class="text-white"></iconify-icon>
                            </div>
                            <div>
                                <h2 class="font-semibold text-white">{{ $invoiceNumber }}</h2>
                                <p class="text-sm text-indigo-200">Invoice Date: {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white text-indigo-600">
                            UNPAID
                        </span>
                    </div>
                </div>

                <!-- Invoice Content -->
                <div class="p-6">
                    <!-- Billed To -->
                    <div class="mb-6 pb-6 border-b border-zinc-200">
                        <h3 class="text-sm font-semibold text-zinc-700 mb-3">BILL TO</h3>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-zinc-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="lucide:user" width="20" class="text-zinc-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="font-medium text-zinc-900">{{ $booking->contact_name }}</p>
                                <p class="text-sm text-zinc-500">{{ $booking->contact_email }}</p>
                                <p class="text-sm text-zinc-500">{{ $booking->contact_phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="mb-6 pb-6 border-b border-zinc-200">
                        <h3 class="text-sm font-semibold text-zinc-700 mb-3">BOOKING DETAILS</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                    <iconify-icon icon="lucide:anchor" width="18" class="text-indigo-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500">Package</p>
                                    <p class="text-sm font-medium text-zinc-900">{{ $booking->package->name ?? 'N/A' }}</p>
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
                        </div>
                    </div>

                    <!-- Guest Summary -->
                    <div class="mb-6 pb-6 border-b border-zinc-200">
                        <h3 class="text-sm font-semibold text-zinc-700 mb-3">GUESTS ({{ $booking->guests->count() }})</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($booking->guests->take(10) as $guest)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-zinc-50 rounded-full text-xs text-zinc-600 border border-zinc-200">
                                    <iconify-icon icon="lucide:user" width="12" class="text-zinc-400"></iconify-icon>
                                    {{ $guest->guest_name }}
                                </span>
                            @endforeach
                            @if($booking->guests->count() > 10)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-zinc-100 rounded-full text-xs text-zinc-500">
                                    +{{ $booking->guests->count() - 10 }} more
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="mb-6">
                        <div class="bg-indigo-600 rounded-2xl p-4">
                            @if($booking->original_price && $booking->original_price > $booking->total_price)
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-indigo-100">Original Price</span>
                                    <span class="text-base text-white/70 line-through">RM {{ number_format($booking->original_price, 0, ',', ',') }}</span>
                                </div>
                                @if($booking->coupon_code)
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-emerald-200">Discount ({{ $booking->coupon_code }})</span>
                                    <span class="text-base font-medium text-emerald-200">-RM {{ number_format($booking->discount_amount, 0, ',', ',') }}</span>
                                </div>
                                @endif
                            @endif
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-indigo-100">Total Amount Due</span>
                                <span class="text-2xl font-bold text-white">RM {{ number_format($booking->total_price, 0, ',', ',') }}</span>
                            </div>
                            <p class="text-xs text-indigo-200">Payment due within 3 days</p>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-zinc-700 mb-3">PAYMENT DETAILS</h3>
                        <div class="bg-zinc-50 rounded-2xl p-4">
                            <div class="grid grid-cols-1 gap-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-zinc-500">Bank</span>
                                    <span class="font-medium text-zinc-900">{{ $bankDetails['bankName'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-zinc-500">Account Name</span>
                                    <span class="font-medium text-zinc-900">{{ $bankDetails['accountName'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-zinc-500">Account Number</span>
                                    <span class="font-medium text-zinc-900 font-mono">{{ $bankDetails['accountNumber'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('invoice.generate', ['id' => $booking->id]) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-medium hover:bg-amber-600 transition-all">
                            <iconify-icon icon="lucide:download" width="16"></iconify-icon>
                            Download Invoice
                        </a>
                        <a href="{{ route('payment', ['booking' => $booking->id]) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-all">
                            <iconify-icon icon="lucide:credit-card" width="16"></iconify-icon>
                            Proceed to Payment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
