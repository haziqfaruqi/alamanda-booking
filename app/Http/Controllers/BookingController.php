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
        $guests = $request->input('guests', []);

        $rules = [
            'package_id' => 'required|exists:packages,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
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
        ];

        // Add phone/email required only for first guest
        $rules['guests.0.phone'] = 'required|string|max:20';
        $rules['guests.0.email'] = 'required|email|max:255';
        $rules['guests.*.phone'] = 'nullable|string|max:20';
        $rules['guests.*.email'] = 'nullable|email|max:255';

        $validated = $request->validate($rules, [
            'guests.*.id_type.required' => 'ID type is required.',
            'guests.*.id_number.required' => 'ID number is required.',
            'guests.*.date_of_birth.required' => 'Date of birth is required for passport holders.',
            'guests.0.phone.required' => 'Phone number is required for the first guest.',
            'guests.0.email.required' => 'Email address is required for the first guest.',
        ]);

        // Custom validation: DOB is required for passport holders
        foreach ($guests as $index => $guest) {
            if (($guest['id_type'] ?? '') === 'passport' && empty($guest['date_of_birth']) && $index > 0) {
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

        // Use first guest's contact info as primary contact
        $firstGuest = $validated['guests'][0];

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
                'coupon_id' => $appliedCoupon ? $appliedCoupon->id : null,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'contact_name' => $firstGuest['name'],
                'contact_phone' => $firstGuest['phone'],
                'contact_email' => $firstGuest['email'],
            ]);

            // Create guests
            foreach ($validated['guests'] as $index => $guestData) {
                $isFirstGuest = $index === 0;

                // For first guest with IC, calculate age from IC
                if ($isFirstGuest && ($guestData['id_type'] ?? '') === 'ic') {
                    $guestAge = $this->calculateAgeFromIC($guestData['id_number']);
                } else {
                    $guestAge = $guestData['age'] ?? null;
                }

                // Determine guest type based on age (if provided)
                $guestType = 'adult';
                if ($guestAge !== null) {
                    $guestType = $guestAge <= 12 ? 'child' : 'adult';
                }

                Guest::create([
                    'booking_id' => $booking->id,
                    'guest_name' => $guestData['name'],
                    'guest_ic' => $guestData['id_type'] === 'ic' ? $guestData['id_number'] : null,
                    'phone' => $guestData['phone'] ?? null,
                    'email' => $guestData['email'] ?? null,
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
     * Calculate age from Malaysian IC number
     */
    private function calculateAgeFromIC(string $icNumber): ?int
    {
        if (strlen($icNumber) < 12) {
            return null;
        }

        $yy = (int) substr($icNumber, 0, 2);
        $currentYear = (int) date('Y');
        $currentCentury = (int) floor($currentYear / 100) * 100;

        $birthYear = $currentCentury + $yy;
        if ($birthYear > $currentYear) {
            $birthYear -= 100;
        }

        return $currentYear - $birthYear;
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
