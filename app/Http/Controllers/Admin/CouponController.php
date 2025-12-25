<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display all coupons
     */
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons', compact('coupons'));
    }

    /**
     * Store a new coupon
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ], [
            'valid_until.after' => 'Valid until date must be after valid from date.',
        ]);

        Coupon::create([
            'code' => strtoupper(str_replace(' ', '', $validated['code'])),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'description' => null,
            'min_guests' => 1,
            'max_uses' => null,
            'valid_from' => $validated['valid_from'],
            'valid_until' => $validated['valid_until'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Coupon created successfully!');
    }

    /**
     * Edit coupon - show details for editing
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return response()->json($coupon);
    }

    /**
     * Update coupon details
     */
    public function updateDetails(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ], [
            'valid_until.after' => 'Valid until date must be after valid from date.',
        ]);

        $coupon->update([
            'type' => $validated['type'],
            'value' => $validated['value'],
            'valid_from' => $validated['valid_from'],
            'valid_until' => $validated['valid_until'],
        ]);

        return back()->with('success', 'Coupon updated successfully!');
    }

    /**
     * Update coupon status
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $coupon->update($validated);

        return back()->with('success', 'Coupon updated successfully!');
    }

    /**
     * Delete coupon
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return back()->with('success', 'Coupon deleted successfully!');
    }

    /**
     * Validate coupon code (API endpoint for booking page)
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'guests' => 'required|integer|min:1|max:30',
        ]);

        $coupon = Coupon::where('code', strtoupper(str_replace(' ', '', $request->code)))->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code.'
            ], 404);
        }

        if (!$coupon->isValid($request->guests)) {
            $message = 'This coupon is';

            if (!$coupon->is_active) {
                $message .= ' not active.';
            } elseif (now()->lt($coupon->valid_from)) {
                $message .= ' not yet valid.';
            } elseif (now()->gt($coupon->valid_until)) {
                $message .= ' expired.';
            } elseif ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                $message .= ' has reached maximum uses.';
            } elseif ($request->guests < $coupon->min_guests) {
                $message .= " requires minimum {$coupon->min_guests} guests.";
            }

            return response()->json([
                'valid' => false,
                'message' => $message
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'coupon' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'description' => $coupon->description,
            ]
        ]);
    }
}
