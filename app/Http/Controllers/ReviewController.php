<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * Store a new review.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only review your own bookings.'
            ], 403);
        }

        // Check if user already reviewed this booking
        if (Review::hasReviewed(auth()->id(), $booking->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this booking.'
            ], 422);
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'booking_id' => $booking->id,
            'package_id' => $booking->package_id,
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your review!',
            'review' => $review
        ]);
    }

    /**
     * Get review for a specific booking.
     */
    public function show($bookingId): JsonResponse
    {
        $review = Review::where('booking_id', $bookingId)
            ->where('user_id', auth()->id())
            ->first();

        return response()->json([
            'review' => $review
        ]);
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $review = Review::findOrFail($id);

        // Check if the review belongs to the authenticated user
        if ($review->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only update your own reviews.'
            ], 403);
        }

        $review->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully!',
            'review' => $review
        ]);
    }

    /**
     * Delete a review.
     */
    public function destroy($id): JsonResponse
    {
        $review = Review::findOrFail($id);

        // Check if the review belongs to the authenticated user
        if ($review->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own reviews.'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully!'
        ]);
    }
}
