<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the report page
     */
    public function index()
    {
        $reportTypes = [
            'bookings' => 'Booking Report',
            'revenue' => 'Revenue Report',
            'packages' => 'Package Report',
        ];

        $years = Booking::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Always include 2026
        if (!in_array(2026, $years)) {
            array_unshift($years, 2026);
        }

        if (empty($years)) {
            $years = [2026];
        }

        return view('admin.generate_report', compact('reportTypes', 'years'));
    }

    /**
     * Generate and export report as CSV
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:bookings,revenue,packages',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $reportType = $validated['report_type'];
        $month = $validated['month'] ?? null;
        $year = $validated['year'];

        $fileName = $this->getFileName($reportType, $month, $year);
        $data = $this->getReportData($reportType, $month, $year);
        $headers = $this->getReportHeaders($reportType);

        return $this->exportCsv($fileName, $headers, $data);
    }

    /**
     * Generate file name for the report
     */
    private function getFileName($reportType, $month, $year)
    {
        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : 'All';

        $names = [
            'bookings' => 'booking_report',
            'revenue' => 'revenue_report',
            'packages' => 'package_report',
        ];

        return "{$names[$reportType]}_{$monthName}_{$year}.csv";
    }

    /**
     * Get report headers based on type
     */
    private function getReportHeaders($reportType)
    {
        $headers = [
            'bookings' => ['ID', 'Contact Name', 'Email', 'Phone', 'Package', 'Check-In', 'Check-Out', 'Adults', 'Children', 'Guest Names', 'Total Price', 'Status', 'Payment Status', 'Booking Date'],
            'revenue' => ['ID', 'Contact Name', 'Package', 'Check-In Date', 'Total Price', 'Payment Status', 'Booking Date'],
            'packages' => ['ID', 'Package Name', 'Duration', 'Standard Price', 'Full Board Adult', 'Full Board Child', 'Active', 'Created Date'],
        ];

        return $headers[$reportType] ?? [];
    }

    /**
     * Get report data based on type
     */
    private function getReportData($reportType, $month, $year)
    {
        $query = match($reportType) {
            'bookings' => Booking::with(['package', 'guests']),
            'revenue' => Booking::with('package')->where('payment_status', 'paid'),
            'packages' => Package::query(),
        };

        // Filter by year
        $query->whereYear('created_at', $year);

        // Filter by month if specified
        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        $records = $query->latest()->get();

        return $this->formatReportData($records, $reportType);
    }

    /**
     * Format report data for CSV export
     */
    private function formatReportData($records, $reportType)
    {
        return $records->map(function ($record) use ($reportType) {
            return match($reportType) {
                'bookings' => [
                    $record->id,
                    $record->contact_name,
                    $record->contact_email,
                    $record->contact_phone ?? '',
                    $record->package->name ?? 'N/A',
                    $record->check_in_date->format('d M Y'),
                    $record->check_out_date->format('d M Y'),
                    $record->total_adults,
                    $record->total_children ?? 0,
                    $record->guests->pluck('guest_name')->implode(', '),
                    $record->total_price,
                    ucfirst($record->status),
                    ucfirst($record->payment_status),
                    $record->created_at->format('d M Y'),
                ],
                'revenue' => [
                    $record->id,
                    $record->contact_name,
                    $record->package->name ?? 'N/A',
                    $record->check_in_date->format('d M Y'),
                    $record->total_price,
                    ucfirst($record->payment_status),
                    $record->created_at->format('d M Y'),
                ],
                'packages' => [
                    $record->id,
                    $record->name,
                    $record->duration,
                    $record->price_standard,
                    $record->price_fullboard_adult,
                    $record->price_fullboard_child,
                    $record->is_active ? 'Yes' : 'No',
                    $record->created_at->format('d M Y'),
                ],
            };
        })->toArray();
    }

    /**
     * Export data as CSV
     */
    private function exportCsv($fileName, $headers, $data)
    {
        $headers = array_map(function ($h) {
            return '"' . str_replace('"', '""', $h) . '"';
        }, $headers);

        $rows = array_merge([$headers], array_map(function ($row) {
            return array_map(function ($cell) {
                return '"' . str_replace('"', '""', (string) $cell) . '"';
            }, $row);
        }, $data));

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, str_replace('"', '', $row));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
