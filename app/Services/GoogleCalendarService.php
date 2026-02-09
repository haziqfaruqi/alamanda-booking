<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Exception;

class GoogleCalendarService
{
    protected ?Client $client = null;
    protected ?Calendar $service = null;
    protected string $calendarId;

    public function __construct()
    {
        $this->calendarId = env('GOOGLE_CALENDAR_ID', 'primary');
    }

    /**
     * Initialize authenticated client for write operations
     */
    protected function getAuthenticatedClient(): ?Client
    {
        if ($this->client) {
            return $this->client;
        }

        $jsonPath = env('GOOGLE_CALENDAR_JSON');

        // Fallback to default path if env is not set
        if (empty($jsonPath)) {
            $jsonPath = base_path('storage/app/google-calendar-credentials.json');
        }

        // Try relative path from base directory
        if ($jsonPath && !str_starts_with($jsonPath, '/') && !str_contains($jsonPath, ':')) {
            $jsonPath = base_path($jsonPath);
        }

        if (!$jsonPath || !file_exists($jsonPath)) {
            logger()->warning('Google Calendar JSON file not found', ['path' => $jsonPath]);
            return null;
        }

        $this->client = new Client();
        $this->client->setApplicationName('Alamanda Houseboat');
        $this->client->setScopes([Calendar::CALENDAR_EVENTS]);
        $this->client->setAuthConfig($jsonPath);

        return $this->client;
    }

    /**
     * Get service with authentication
     */
    protected function getService(): ?Calendar
    {
        if ($this->service) {
            return $this->service;
        }

        $client = $this->getAuthenticatedClient();
        if (!$client) {
            return null;
        }

        $this->service = new Calendar($client);
        return $this->service;
    }

    /**
     * Check if dates are available (no overlapping bookings)
     * Uses service account for authenticated access
     */
    public function isAvailable(string $startDate, string $endDate): bool
    {
        try {
            $events = $this->getEventsBetween($startDate, $endDate);

            foreach ($events as $event) {
                if ($this->isEventOverlapping($event, $startDate, $endDate)) {
                    return false;
                }
            }

            return true;
        } catch (Exception $e) {
            // Log error but allow booking if API fails
            logger()->error('Google Calendar API Error: ' . $e->getMessage());
            return true; // Fail open - allow booking if calendar check fails
        }
    }

    /**
     * Get all events between two dates
     * Uses service account authentication
     */
    public function getEventsBetween(string $startDate, string $endDate): array
    {
        $service = $this->getService();
        if (!$service) {
            logger()->warning('Google Calendar service not initialized for reading events');
            return [];
        }

        $optParams = [
            'timeMin' => $this->formatDateTime($startDate),
            'timeMax' => $this->formatDateTime($endDate),
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ];

        $results = $service->events->listEvents($this->calendarId, $optParams);
        return $results->getItems() ?? [];
    }

    /**
     * Check if an event overlaps with the given date range
     */
    protected function isEventOverlapping(Event $event, string $startDate, string $endDate): bool
    {
        $eventStart = $this->parseDateTime($event->getStart());
        $eventEnd = $this->parseDateTime($event->getEnd());

        $requestStart = strtotime($startDate);
        $requestEnd = strtotime($endDate);

        // Check for overlap
        // Events overlap if: (StartA < EndB) and (EndA > StartB)
        return ($eventStart < $requestEnd) && ($eventEnd > $requestStart);
    }

    /**
     * Create a new booking event in Google Calendar
     * Requires Service Account authentication
     */
    public function createBookingEvent(array $bookingData): ?Event
    {
        $service = $this->getService();
        if (!$service) {
            logger()->warning('Google Calendar service not initialized - skipping event creation');
            return null;
        }

        // Check if event already exists for this booking to prevent duplicates
        if (isset($bookingData['booking_id'])) {
            $existingEvent = $this->getEventByBookingId($bookingData['booking_id']);
            if ($existingEvent) {
                logger()->info('Calendar event already exists for booking', ['booking_id' => $bookingData['booking_id'], 'event_id' => $existingEvent->getId()]);
                return $existingEvent;
            }
        }

        try {
            $event = new Event();
            $event->setSummary($bookingData['title'] ?? 'Houseboat Booking');
            $event->setDescription($bookingData['description'] ?? '');

            $start = new EventDateTime();
            $start->setDateTime($this->formatDateTime($bookingData['start_date'], '14:30'));
            $start->setTimeZone('Asia/Kuala_Lumpur');
            $event->setStart($start);

            $end = new EventDateTime();
            $end->setDateTime($this->formatDateTime($bookingData['end_date'], '12:00'));
            $end->setTimeZone('Asia/Kuala_Lumpur');
            $event->setEnd($end);

            // Add extended properties for booking reference
            if (isset($bookingData['booking_id'])) {
                $extendedProperties = new \Google\Service\Calendar\EventExtendedProperties();
                $extendedProperties->setShared([
                    'booking_id' => $bookingData['booking_id'],
                ]);
                $event->setExtendedProperties($extendedProperties);
            }

            $createdEvent = $service->events->insert($this->calendarId, $event);

            logger()->info('Calendar event created', ['event_id' => $createdEvent->getId()]);
            return $createdEvent;
        } catch (Exception $e) {
            logger()->error('Failed to create calendar event: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update an existing booking event
     */
    public function updateBookingEvent(string $eventId, array $bookingData): ?Event
    {
        $service = $this->getService();
        if (!$service) {
            return null;
        }

        try {
            $event = $service->events->get($this->calendarId, $eventId);

            if (isset($bookingData['title'])) {
                $event->setSummary($bookingData['title']);
            }
            if (isset($bookingData['description'])) {
                $event->setDescription($bookingData['description']);
            }

            if (isset($bookingData['start_date'])) {
                $start = new EventDateTime();
                $start->setDateTime($this->formatDateTime($bookingData['start_date'], '14:30'));
                $start->setTimeZone('Asia/Kuala_Lumpur');
                $event->setStart($start);
            }

            if (isset($bookingData['end_date'])) {
                $end = new EventDateTime();
                $end->setDateTime($this->formatDateTime($bookingData['end_date'], '12:00'));
                $end->setTimeZone('Asia/Kuala_Lumpur');
                $event->setEnd($end);
            }

            $updatedEvent = $service->events->update($this->calendarId, $eventId, $event);

            return $updatedEvent;
        } catch (Exception $e) {
            logger()->error('Failed to update calendar event: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a booking event from Google Calendar
     */
    public function deleteBookingEvent(string $eventId): bool
    {
        $service = $this->getService();
        if (!$service) {
            return false;
        }

        try {
            $service->events->delete($this->calendarId, $eventId);
            logger()->info('Calendar event deleted', ['event_id' => $eventId]);
            return true;
        } catch (Exception $e) {
            logger()->error('Failed to delete calendar event: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get event by booking ID from extended properties
     */
    public function getEventByBookingId(string $bookingId): ?Event
    {
        $service = $this->getService();
        if (!$service) {
            return null;
        }

        try {
            $optParams = [
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ];

            $results = $service->events->listEvents($this->calendarId, $optParams);

            foreach ($results->getItems() as $event) {
                $extendedProps = $event->getExtendedProperties();
                if ($extendedProps && $extendedProps->getShared()) {
                    $shared = $extendedProps->getShared();
                    if (isset($shared['booking_id']) && $shared['booking_id'] == $bookingId) {
                        return $event;
                    }
                }
            }

            return null;
        } catch (Exception $e) {
            logger()->error('Failed to find event by booking ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Format date/time for Google Calendar API
     * Adds default time if only date is provided
     * Check-in: 2:30 PM (14:30), Check-out: 12:00 PM (noon)
     */
    protected function formatDateTime(string $date, string $defaultTime = '14:30'): string
    {
        // If date is a Carbon object or has time component, extract just the date part
        // Database dates come as "2026-01-05 00:00:00" - we need to strip the time
        $dateOnly = substr($date, 0, 10); // Get just YYYY-MM-DD

        // Now add our default time
        $dateWithTime = $dateOnly . ' ' . $defaultTime;

        // Create DateTime in Asia/Kuala_Lumpur timezone to avoid offset issues
        $dt = new \DateTime($dateWithTime, new \DateTimeZone('Asia/Kuala_Lumpur'));
        $formatted = $dt->format('c'); // ISO 8601 format with timezone
        logger()->info('formatDateTime', ['input' => $date, 'dateOnly' => $dateOnly, 'output' => $formatted]);
        return $formatted;
    }

    /**
     * Parse datetime from Google Calendar event
     */
    protected function parseDateTime($eventDateTime): int
    {
        if ($eventDateTime->getDate()) {
            // All-day event
            return strtotime($eventDateTime->getDate());
        }
        // Regular datetime event
        return strtotime($eventDateTime->getDateTime());
    }

    /**
     * Get calendar ID
     */
    public function getCalendarId(): string
    {
        return $this->calendarId;
    }
}
