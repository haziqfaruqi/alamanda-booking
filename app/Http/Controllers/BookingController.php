<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Guest;
use App\Models\Package;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Show the booking page
     */
    public function create()
    {
        $packages = Package::active()->get();
        return view('user.booking', compact('packages'));
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'total_guests' => 'required|integer|min:1|max:30',
            'total_adults' => 'nullable|integer|min:1',
            'total_children' => 'nullable|integer|min:0',
            'guests' => 'required|array|min:1|max:30',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.id_type' => 'required|in:ic,passport',
            'guests.*.id_number' => 'required|string|max:50',
            'guests.*.date_of_birth' => 'nullable|date|before:today',
            'guests.*.age' => 'nullable|integer|min:0|max:150',
            'coupon_code' => 'nullable|string|max:50',
        ], [
            'guests.*.id_type.required' => 'ID type is required.',
            'guests.*.id_number.required' => 'ID number is required.',
            'guests.*.date_of_birth.required' => 'Date of birth is required for passport holders.',
        ]);

        // Custom validation: DOB is required for passport holders
        foreach ($request->input('guests', []) as $index => $guest) {
            if (($guest['id_type'] ?? '') === 'passport' && empty($guest['date_of_birth'])) {
                return back()->withInput()->withErrors([
                    "guests.{$index}.date_of_birth" => 'Date of birth is required for passport holders.'
                ]);
            }
        }

        // Check for duplicate ID numbers within the same booking
        $idNumbers = array_column($validated['guests'], 'id_number');
        $duplicateIds = array_filter(array_count_values($idNumbers), fn($count) => $count > 1);

        if (!empty($duplicateIds)) {
            return back()->withInput()->withErrors([
                'error' => 'Duplicate ID numbers detected within this booking: ' . implode(', ', array_keys($duplicateIds)) . '. Each guest must have a unique ID number.'
            ]);
        }

        $package = Package::findOrFail($validated['package_id']);
        $user = auth()->user();

        DB::beginTransaction();

        try {
            // Calculate total price
            $totalPrice = $this->calculateTotalPrice($package, $validated);

            // Apply coupon discount if provided
            $discountAmount = 0;
            $appliedCoupon = null;

            if (!empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', strtoupper($validated['coupon_code']))->first();

                if ($coupon && $coupon->isValid($validated['total_guests'])) {
                    $discountAmount = $coupon->calculateDiscount($totalPrice);
                    $appliedCoupon = $coupon;

                    // Increment coupon usage
                    $coupon->incrementUsedCount();

                    // Ensure discount doesn't exceed total price
                    $discountAmount = min($discountAmount, $totalPrice);
                }
            }

            $finalPrice = $totalPrice - $discountAmount;

            // Determine package type (standard or fullboard)
            $packageType = str_contains($package->name, 'Full Board') ? 'fullboard' : 'standard';

            // Create booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'package_type' => $packageType,
                'duration' => $package->duration,
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'total_guests' => $validated['total_guests'],
                'total_adults' => $validated['total_adults'] ?? $validated['total_guests'],
                'total_children' => $validated['total_children'] ?? 0,
                'total_price' => $finalPrice,
                'original_price' => $totalPrice,
                'discount_amount' => $discountAmount,
                'coupon_code' => $appliedCoupon ? $appliedCoupon->code : null,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'contact_name' => $validated['contact_name'],
                'contact_phone' => $validated['contact_phone'],
                'contact_email' => $validated['contact_email'],
            ]);

            // Create guests
            foreach ($validated['guests'] as $guestData) {
                // Determine guest type based on age (if provided)
                $guestAge = $guestData['age'] ?? null;
                $guestType = 'adult'; // default to adult

                if ($guestAge !== null) {
                    // Consider 12 and below as child
                    $guestType = $guestAge <= 12 ? 'child' : 'adult';
                }

                Guest::create([
                    'booking_id' => $booking->id,
                    'guest_name' => $guestData['name'],
                    'guest_ic' => $guestData['id_type'] === 'ic' ? $guestData['id_number'] : null,
                    'id_type' => $guestData['id_type'],
                    'id_number' => $guestData['id_number'],
                    'guest_type' => $guestType,
                    'age' => $guestAge,
                    'date_of_birth' => $guestData['date_of_birth'] ?? null,
                ]);
            }

            DB::commit();

            // Note: Calendar event will be created after payment is completed

            return redirect()->route('invoice.show', ['booking' => $booking->id])
                ->with('success', 'Booking created successfully! Please review your invoice and proceed to payment.');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Booking creation failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create booking. Please try again.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create booking. Please try again.']);
        }
    }

    /**
     * Calculate total price based on package and guest count
     */
    private function calculateTotalPrice(Package $package, array $data): float
    {
        // Standard package pricing
        if ($package->price_standard) {
            return (float) $package->price_standard;
        }

        // Full board package pricing
        $adults = $data['total_adults'] ?? $data['total_guests'];
        $children = $data['total_children'] ?? 0;

        $adultPrice = (float) ($package->price_fullboard_adult ?? 0);
        $childPrice = (float) ($package->price_fullboard_child ?? 0);

        return ($adults * $adultPrice) + ($children * $childPrice);
    }

    /**
     * Display user's bookings
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with(['package', 'guests', 'review'])
            ->latest()
            ->paginate(10);
        return view('user.view_booking', compact('bookings'));
    }

    /**
     * Display a specific booking
     */
    public function show($id)
    {
        $booking = Booking::with(['package', 'guests'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('user.view_booking', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($booking->status === 'cancelled') {
            return back()->withErrors(['error' => 'Booking is already cancelled.']);
        }

        if ($booking->payment_status === 'paid') {
            return back()->withErrors(['error' => 'Cannot cancel paid booking. Please contact support.']);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Remove from Google Calendar
        try {
            $calendarService = app(GoogleCalendarService::class);
            $event = $calendarService->getEventByBookingId($booking->id);
            if ($event) {
                $calendarService->deleteBookingEvent($event->getId());
            }
        } catch (\Exception $e) {
            Log::warning('Failed to delete calendar event: ' . $e->getMessage());
        }

        return back()->with('success', 'Booking cancelled successfully!');
    }
}
