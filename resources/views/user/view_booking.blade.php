@extends('layouts.app', [
    'title' => 'My Bookings - Alamanda Houseboat',
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
        <div class="max-w-5xl mx-auto px-6">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900 mb-2">My Bookings</h1>
                <p class="text-zinc-500">Manage and view your houseboat reservations</p>
            </div>

            @if($bookings->count() > 0)
                <!-- Booking Cards -->
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                    <div class="bg-white rounded-3xl border border-zinc-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                        <!-- Card Header -->
                        <div class="flex items-start justify-between mb-6 pb-6 border-b border-zinc-100">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <iconify-icon icon="lucide:anchor" width="24" class="text-indigo-600"></iconify-icon>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-zinc-900">{{ $booking->package->name ?? 'Package' }}</h3>
                                    <p class="text-sm text-zinc-500 flex items-center gap-1 mt-1">
                                        <iconify-icon icon="lucide:clock" width="14" class="text-zinc-400"></iconify-icon>
                                        {{ $booking->duration }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold @if($booking->status === 'confirmed') bg-emerald-100 text-emerald-700 @elseif($booking->status === 'pending') bg-amber-100 text-amber-700 @elseif($booking->status === 'cancelled') bg-red-100 text-red-700 @else bg-zinc-100 text-zinc-700 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <!-- Booking Details Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <!-- Check-in -->
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-zinc-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <iconify-icon icon="lucide:calendar" width="18" class="text-zinc-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500">Check-in</p>
                                    <p class="text-sm font-medium text-zinc-900">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <!-- Check-out -->
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-zinc-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <iconify-icon icon="lucide:calendar-check" width="18" class="text-zinc-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500">Check-out</p>
                                    <p class="text-sm font-medium text-zinc-900">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <!-- Guests -->
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-zinc-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <iconify-icon icon="lucide:users" width="18" class="text-zinc-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500">Guests</p>
                                    <p class="text-sm font-medium text-zinc-900">{{ $booking->total_guests }} pax</p>
                                </div>
                            </div>
                            <!-- Price -->
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <iconify-icon icon="lucide:banknote" width="18" class="text-indigo-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500">Total</p>
                                    <p class="text-sm font-semibold text-indigo-600">RM {{ number_format($booking->total_price, 0, ',', ',') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="flex items-center justify-between mb-6 p-4 rounded-2xl @if($booking->payment_status === 'paid') bg-emerald-50 @elseif($booking->payment_status === 'unpaid') bg-amber-50 @else bg-zinc-50 @endif">
                            <div class="flex items-center gap-3">
                                @if($booking->payment_status === 'paid')
                                    <iconify-icon icon="lucide:check-circle-2" width="20" class="text-emerald-600"></iconify-icon>
                                    <span class="text-sm font-medium text-emerald-700">Payment Completed</span>
                                @elseif($booking->payment_status === 'unpaid')
                                    <iconify-icon icon="lucide:alert-circle" width="20" class="text-amber-600"></iconify-icon>
                                    <span class="text-sm font-medium text-amber-700">Payment Pending</span>
                                @else
                                    <iconify-icon icon="lucide:circle" width="20" class="text-zinc-400"></iconify-icon>
                                    <span class="text-sm font-medium text-zinc-600">{{ ucfirst($booking->payment_status) }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-semibold uppercase @if($booking->payment_status === 'paid') text-emerald-600 @elseif($booking->payment_status === 'unpaid') text-amber-600 @else text-zinc-500 @endif">
                                {{ $booking->payment_status }}
                            </span>
                        </div>

                        <!-- Guests Preview -->
                        @if($booking->guests->count() > 0)
                        <div class="mb-6 p-4 bg-zinc-50 rounded-2xl">
                            <div class="flex items-center gap-2 mb-3">
                                <iconify-icon icon="lucide:list" width="16" class="text-zinc-500"></iconify-icon>
                                <span class="text-sm font-medium text-zinc-700">Guest List ({{ $booking->guests->count() }})</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($booking->guests->take(8) as $guest)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-white rounded-full text-xs text-zinc-600 border border-zinc-200">
                                        <iconify-icon icon="lucide:user" width="12" class="text-zinc-400"></iconify-icon>
                                        {{ $guest->guest_name }}
                                    </span>
                                @endforeach
                                @if($booking->guests->count() > 8)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-zinc-100 rounded-full text-xs text-zinc-500">
                                        +{{ $booking->guests->count() - 8 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-3">
                            <button onclick="viewDetails({{ $booking->id }})" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-zinc-300 rounded-xl text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-400 transition-all">
                                <iconify-icon icon="lucide:eye" width="16"></iconify-icon>
                                View Details
                            </button>
                            @if($booking->review && $booking->review->count() > 0)
                            <button disabled class="inline-flex items-center gap-2 px-5 py-2.5 bg-zinc-100 border border-zinc-200 rounded-xl text-sm font-medium text-zinc-400 cursor-not-allowed">
                                <iconify-icon icon="lucide:star" width="16"></iconify-icon>
                                Rated
                            </button>
                            @else
                            <button onclick="openReviewModal({{ $booking->id }}, '{{ $booking->package->name ?? 'Package' }}')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-50 border border-amber-200 rounded-xl text-sm font-medium text-amber-700 hover:bg-amber-100 transition-all">
                                <iconify-icon icon="lucide:star" width="16"></iconify-icon>
                                Rate & Review
                            </button>
                            @endif
                            <a href="{{ route('receipt.generate', ['id' => $booking->id]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-zinc-300 rounded-xl text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-400 transition-all">
                                <iconify-icon icon="lucide:download" width="16"></iconify-icon>
                                Receipt
                            </a>
                            @if($booking->status !== 'cancelled' && $booking->payment_status !== 'paid')
                                <a href="{{ route('payment', ['booking' => $booking->id]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 rounded-xl text-sm font-medium text-white hover:bg-indigo-700 transition-all">
                                    <iconify-icon icon="lucide:credit-card" width="16"></iconify-icon>
                                    Complete Payment
                                </a>
                            @endif
                            @if($booking->status === 'pending')
                                <form method="POST" action="{{ route('booking.cancel', ['id' => $booking->id]) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 border border-red-200 rounded-xl text-sm font-medium text-red-600 hover:bg-red-100 transition-all">
                                        <iconify-icon icon="lucide:x" width="16"></iconify-icon>
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $bookings->appends([])->links('pagination.tailwind') }}
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl border border-zinc-200 p-12 text-center shadow-sm">
                    <div class="w-20 h-20 bg-zinc-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <iconify-icon icon="lucide:calendar-x" width="40" class="text-zinc-400"></iconify-icon>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-900 mb-2">No bookings yet</h3>
                    <p class="text-zinc-500 mb-8 max-w-sm mx-auto">You haven't made any bookings yet. Start by selecting a package and booking your houseboat experience.</p>
                    <a href="{{ route('booking') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 rounded-xl text-white font-semibold hover:bg-indigo-700 transition-all hover:-translate-y-0.5">
                        <iconify-icon icon="lucide:plus" width="18"></iconify-icon>
                        Book Now
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-zinc-900/50 backdrop-blur-sm" onclick="closeModal('detailsModal')"></div>
        <div class="absolute inset-4 md:inset-auto md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 md:w-full md:max-w-2xl md:max-h-[85vh] overflow-auto">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 border-b border-zinc-200 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <iconify-icon icon="lucide:anchor" width="20" class="text-indigo-600"></iconify-icon>
                        </div>
                        <h3 class="text-lg font-semibold text-zinc-900">Booking Details</h3>
                    </div>
                    <button onclick="closeModal('detailsModal')" class="w-10 h-10 rounded-xl hover:bg-zinc-100 flex items-center justify-center transition-colors">
                        <iconify-icon icon="lucide:x" width="20" class="text-zinc-500"></iconify-icon>
                    </button>
                </div>
                <!-- Modal Content -->
                <div id="detailsContent" class="p-6"></div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-zinc-900/50 backdrop-blur-sm" onclick="closeModal('reviewModal')"></div>
        <div class="absolute inset-4 md:inset-auto md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 md:w-full md:max-w-lg overflow-auto">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 bg-gradient-to-r from-amber-50 to-orange-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <iconify-icon icon="lucide:star" width="20" class="text-amber-600"></iconify-icon>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900">Rate Your Experience</h3>
                            <p id="reviewPackageName" class="text-xs text-zinc-500"></p>
                        </div>
                    </div>
                    <button onclick="closeModal('reviewModal')" class="w-10 h-10 rounded-xl hover:bg-zinc-100 flex items-center justify-center transition-colors">
                        <iconify-icon icon="lucide:x" width="20" class="text-zinc-500"></iconify-icon>
                    </button>
                </div>

                <!-- Modal Content -->
                <form id="reviewForm" class="p-6">
                    @csrf
                    <input type="hidden" id="reviewBookingId" name="booking_id">

                    <!-- Star Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-zinc-700 mb-3">How was your experience?</label>
                        <div class="flex items-center gap-2" id="starRating">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    class="star-btn text-3xl transition-all duration-200 hover:scale-110"
                                    data-rating="{{ $i }}"
                                    onclick="setRating({{ $i }})">
                                <iconify-icon icon="lucide:star" width="32" class="text-zinc-300 star-icon"></iconify-icon>
                            </button>
                            @endfor
                        </div>
                        <input type="hidden" id="ratingInput" name="rating" value="5">
                        <p id="ratingText" class="text-sm text-zinc-500 mt-2">Excellent!</p>
                    </div>

                    <!-- Feedback Textarea -->
                    <div class="mb-6">
                        <label for="feedback" class="block text-sm font-medium text-zinc-700 mb-2">Share your thoughts (optional)</label>
                        <textarea id="feedback"
                                  name="feedback"
                                  rows="4"
                                  class="w-full px-4 py-3 border border-zinc-300 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all resize-none"
                                  placeholder="Tell us about your experience... What did you love? What could we improve?"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold hover:from-amber-600 hover:to-orange-600 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <iconify-icon icon="lucide:send" width="18"></iconify-icon>
                        Submit Review
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const bookings = @js($bookings->map(function($b) {
    return (object)[
        'id' => $b->id,
        'package_name' => $b->package->name ?? 'N/A',
        'duration' => $b->duration,
        'check_in' => \Carbon\Carbon::parse($b->check_in_date)->format('d M Y'),
        'check_out' => \Carbon\Carbon::parse($b->check_out_date)->format('d M Y'),
        'total_guests' => $b->total_guests,
        'total_adults' => $b->total_adults,
        'total_children' => $b->total_children,
        'total_price' => number_format($b->total_price, 0, ',', ','),
        'status' => $b->status,
        'payment_status' => $b->payment_status,
        'payment_method' => $b->payment_method,
        'payment_reference' => $b->payment_reference,
        'receipt_path' => $b->receipt_path,
        'contact_name' => $b->contact_name,
        'contact_phone' => $b->contact_phone,
        'contact_email' => $b->contact_email,
        'confirmed_at' => $b->confirmed_at ? \Carbon\Carbon::parse($b->confirmed_at)->format('d M Y H:i') : null,
        'guests' => $b->guests->map(function($g) {
            return ['name' => $g->guest_name, 'ic' => $g->guest_ic];
        })->toArray()
    ];
}));

function viewDetails(bookingId) {
    const booking = bookings.find(b => b.id === bookingId);
    if (!booking) return;

    const paymentMethodLabels = {
        'bank_transfer': 'Bank Transfer',
        'fpx': 'FPX Online Banking',
        'toyyibpay': 'ToyyibPay'
    };

    let guestsHtml = booking.guests.map(g => `
        <div class="flex items-center justify-between py-3 border-b border-zinc-100 last:border-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-zinc-100 rounded-lg flex items-center justify-center">
                    <iconify-icon icon="lucide:user" width="14" class="text-zinc-500"></iconify-icon>
                </div>
                <span class="text-sm text-zinc-700">${escapeHtml(g.name)}</span>
            </div>
            <span class="text-xs text-zinc-500 font-mono">${escapeHtml(g.ic)}</span>
        </div>
    `).join('');

    document.getElementById('detailsContent').innerHTML = `
        <!-- Package Section -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3">Package Information</h4>
            <div class="bg-zinc-50 rounded-2xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600">Package</span>
                    <span class="text-sm font-medium text-zinc-900">${escapeHtml(booking.package_name)}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600">Duration</span>
                    <span class="text-sm font-medium text-zinc-900">${escapeHtml(booking.duration)}</span>
                </div>
            </div>
        </div>

        <!-- Date Section -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3">Date Information</h4>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-indigo-50 rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <iconify-icon icon="lucide:calendar" width="16" class="text-indigo-600"></iconify-icon>
                        <span class="text-xs text-indigo-600 font-medium">Check-In</span>
                    </div>
                    <p class="text-sm font-semibold text-indigo-900">${escapeHtml(booking.check_in)}</p>
                </div>
                <div class="bg-indigo-50 rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <iconify-icon icon="lucide:calendar-check" width="16" class="text-indigo-600"></iconify-icon>
                        <span class="text-xs text-indigo-600 font-medium">Check-Out</span>
                    </div>
                    <p class="text-sm font-semibold text-indigo-900">${escapeHtml(booking.check_out)}</p>
                </div>
            </div>
        </div>

        <!-- Guests Section -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3">Guest Summary</h4>
            <div class="bg-zinc-50 rounded-2xl p-4 grid grid-cols-3 gap-3">
                <div class="text-center">
                    <p class="text-2xl font-bold text-zinc-900">${booking.total_guests}</p>
                    <p class="text-xs text-zinc-500">Total</p>
                </div>
                <div class="text-center border-x border-zinc-200">
                    <p class="text-2xl font-bold text-zinc-900">${booking.total_adults}</p>
                    <p class="text-xs text-zinc-500">Adults</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-zinc-900">${booking.total_children}</p>
                    <p class="text-xs text-zinc-500">Children</p>
                </div>
            </div>
        </div>

        <!-- Guest List Section -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3">Guest List (${booking.guests.length})</h4>
            <div class="bg-zinc-50 rounded-2xl p-4 max-h-48 overflow-y-auto">
                ${guestsHtml}
            </div>
        </div>

        <!-- Contact Section -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3">Contact Information</h4>
            <div class="bg-zinc-50 rounded-2xl p-4 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <iconify-icon icon="lucide:user" width="14" class="text-zinc-500"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500">Name</p>
                        <p class="text-sm font-medium text-zinc-900">${escapeHtml(booking.contact_name)}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <iconify-icon icon="lucide:phone" width="14" class="text-zinc-500"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500">Phone</p>
                        <p class="text-sm font-medium text-zinc-900">${escapeHtml(booking.contact_phone)}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <iconify-icon icon="lucide:mail" width="14" class="text-zinc-500"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500">Email</p>
                        <p class="text-sm font-medium text-zinc-900">${escapeHtml(booking.contact_email)}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Section -->
        <div>
            <h4 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3">Payment Information</h4>
            <div class="bg-indigo-50 rounded-2xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700">Total Amount</span>
                    <span class="text-lg font-bold text-indigo-900">RM ${booking.total_price}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700">Payment Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${booking.payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'}">
                        ${escapeHtml(booking.payment_status)}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700">Booking Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${booking.status === 'confirmed' ? 'bg-emerald-100 text-emerald-700' : booking.status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-zinc-100 text-zinc-700'}">
                        ${escapeHtml(booking.status)}
                    </span>
                </div>
                ${booking.payment_method ? `
                <div class="flex items-center justify-between pt-3 border-t border-indigo-200">
                    <span class="text-sm text-indigo-700">Payment Method</span>
                    <span class="text-sm font-medium text-indigo-900">${paymentMethodLabels[booking.payment_method] || booking.payment_method}</span>
                </div>
                ` : ''}
                ${booking.payment_reference ? `
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700">Reference</span>
                    <span class="text-sm font-medium text-indigo-900 font-mono">${escapeHtml(booking.payment_reference)}</span>
                </div>
                ` : ''}
                ${booking.confirmed_at ? `
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700">Confirmed At</span>
                    <span class="text-sm font-medium text-indigo-900">${escapeHtml(booking.confirmed_at)}</span>
                </div>
                ` : ''}
            </div>
        </div>
    `;

    document.getElementById('detailsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = '';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal('detailsModal');
        closeModal('reviewModal');
    }
});

// Review Modal Functions
const ratingTexts = {
    1: 'Poor',
    2: 'Fair',
    3: 'Good',
    4: 'Very Good',
    5: 'Excellent!'
};

function openReviewModal(bookingId, packageName) {
    document.getElementById('reviewBookingId').value = bookingId;
    document.getElementById('reviewPackageName').textContent = packageName;
    document.getElementById('feedback').value = '';
    setRating(5); // Default to 5 stars
    document.getElementById('reviewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function setRating(rating) {
    document.getElementById('ratingInput').value = rating;
    document.getElementById('ratingText').textContent = ratingTexts[rating];

    // Update star visual
    const stars = document.querySelectorAll('.star-icon');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-zinc-300');
            star.classList.add('text-amber-400', 'fill-amber-400');
        } else {
            star.classList.add('text-zinc-300');
            star.classList.remove('text-amber-400', 'fill-amber-400');
        }
    });
}

// Handle review form submission
document.getElementById('reviewForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<iconify-icon icon="lucide:loader-2" width="18" class="animate-spin"></iconify-icon> Submitting...';

    try {
        const response = await fetch('{{ route('reviews.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Show success message
            closeModal('reviewModal');
            showNotification('success', data.message || 'Thank you for your review!');
        } else {
            // Show error message
            showNotification('error', data.message || 'Failed to submit review. Please try again.');
        }
    } catch (error) {
        console.error('Error submitting review:', error);
        showNotification('error', 'An error occurred. Please try again.');
    } finally {
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    }
});

// Notification helper
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-4 rounded-2xl shadow-lg z-50 flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-300 ${
        type === 'success' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white'
    }`;
    notification.innerHTML = `
        <iconify-icon icon="lucide:${type === 'success' ? 'check-circle' : 'alert-circle'}" width="20"></iconify-icon>
        <span class="font-medium">${message}</span>
    `;
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-y-20', 'opacity-0');
    }, 10);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-y-20', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endpush
