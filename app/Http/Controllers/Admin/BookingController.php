<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display the booking list page
     */
    public function index()
    {
        $bookings = Booking::with(['package', 'user'])
            ->latest()
            ->get();

        return view('admin.booking_dashboard', compact('bookings'));
    }

    /**
     * Display a single booking details
     */
    public function show($id)
    {
        $booking = Booking::with(['package', 'user', 'guests'])
            ->findOrFail($id);

        return view('admin.booking_details', compact('booking'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->status = $validated['status'];

        if ($validated['status'] === 'confirmed') {
            $booking->confirmed_at = now();
        } elseif ($validated['status'] === 'cancelled') {
            $booking->cancelled_at = now();
        }

        $booking->save();

        return back()->with('success', 'Booking status updated successfully!');
    }
}
