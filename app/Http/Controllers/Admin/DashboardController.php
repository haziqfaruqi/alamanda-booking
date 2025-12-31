<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Review;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Get KPI data
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');
        $totalBookings = Booking::count();
        $totalCustomers = User::where('role', 'user')->count();
        $totalReviews = Review::count();

        // Calculate growth percentages (compare current month vs previous month)
        $lastMonthRevenue = Booking::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_price');

        $thisMonthRevenue = Booking::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        $lastMonthBookings = Booking::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $thisMonthBookings = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonthCustomers = User::where('role', 'user')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $thisMonthCustomers = User::where('role', 'user')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonthReviews = Review::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $thisMonthReviews = Review::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Calculate percentages
        $revenueGrowth = $lastMonthRevenue > 0
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        $bookingsGrowth = $lastMonthBookings > 0
            ? (($thisMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100
            : 0;

        $customersGrowth = $lastMonthCustomers > 0
            ? (($thisMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100
            : 0;

        $reviewsGrowth = $lastMonthReviews > 0
            ? (($thisMonthReviews - $lastMonthReviews) / $lastMonthReviews) * 100
            : 0;

        // Get recent bookings for activity feed
        $recentBookings = Booking::with(['user', 'package'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent users
        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(3)
            ->get();

        // Generate monthly data for current year (2025) and previous year (2024)
        $currentYear = now()->year;
        $chartData2024 = $this->getYearlyData(2024);
        $chartData2025 = $this->getYearlyData($currentYear);

        return view('admin.alamanda_dashboard', compact(
            'totalRevenue',
            'totalBookings',
            'totalCustomers',
            'totalReviews',
            'revenueGrowth',
            'bookingsGrowth',
            'customersGrowth',
            'reviewsGrowth',
            'recentBookings',
            'recentUsers',
            'chartData2024',
            'chartData2025'
        ));
    }

    /**
     * Get monthly data for a specific year
     */
    private function getYearlyData($year)
    {
        $chartLabels = [];
        $revenueData = [];
        $bookingsData = [];

        // All 12 months of the year
        for ($month = 1; $month <= 12; $month++) {
            $date = now()->copy()->setDate($year, $month, 1);
            $label = $date->format('M Y');
            $chartLabels[] = $label;

            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $monthRevenue = Booking::where('payment_status', 'paid')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total_price');
            $revenueData[] = $monthRevenue;

            $monthBookings = Booking::whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            $bookingsData[] = $monthBookings;
        }

        return [
            'labels' => $chartLabels,
            'revenue' => $revenueData,
            'bookings' => $bookingsData,
        ];
    }
}
