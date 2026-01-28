<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    protected GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Check if dates are available for booking
     */
    public function check(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'check_in_date' => 'required|date|after:today',
                'check_out_date' => 'required|date|after:check_in_date',
            ]);

            $checkIn = $request->input('check_in_date');
            $checkOut = $request->input('check_out_date');

            $isAvailable = $this->calendarService->isAvailable($checkIn, $checkOut);

            return response()->json([
                'available' => $isAvailable,
                'message' => $isAvailable
                    ? 'These dates are available for booking.'
                    : 'These dates are not available. Please choose different dates.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'available' => false,
                'message' => 'Invalid dates: ' . collect($e->errors())->flatten()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'available' => true,
                'message' => 'Availability check temporarily unavailable.',
            ]);
        }
    }

    /**
     * Get list of unavailable dates (booked dates)
     */
    public function getUnavailableDates(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $events = $this->calendarService->getEventsBetween(
            $request->input('start_date'),
            $request->input('end_date')
        );

        $unavailableDates = [];

        foreach ($events as $event) {
            $start = $event->getStart();
            $end = $event->getEnd();

            $startDate = $start->getDateTime() ?? $start->getDate();
            $endDate = $end->getDateTime() ?? $end->getDate();

            // Add all dates in this range
            $current = strtotime($startDate);
            $endTimestamp = strtotime($endDate);

            while ($current < $endTimestamp) {
                $unavailableDates[] = date('Y-m-d', $current);
                $current = strtotime('+1 day', $current);
            }
        }

        return response()->json([
            'unavailable_dates' => array_values(array_unique($unavailableDates)),
        ]);
    }
}
