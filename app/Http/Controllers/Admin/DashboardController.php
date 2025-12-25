<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        $totalReviews = 0; // TODO: Implement reviews table

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

        // Get monthly revenue for chart (last 12 months)
        $monthlyRevenue = Booking::where('payment_status', 'paid')
            ->select(DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'), DB::raw('SUM(total_price) as total'))
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%b %Y")'))
            ->orderBy(DB::raw('MIN(created_at)'))
            ->take(12)
            ->pluck('total', 'month')
            ->toArray();

        // Get monthly booking count
        $monthlyBookings = Booking::select(DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%b %Y")'))
            ->orderBy(DB::raw('MIN(created_at)'))
            ->take(12)
            ->pluck('count', 'month')
            ->toArray();

        // Generate last 12 months labels
        $chartLabels = [];
        $revenueData = [];
        $bookingsData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $label = $date->format('M Y');
            $chartLabels[] = $label;
            $revenueData[] = $monthlyRevenue[$label] ?? 0;
            $bookingsData[] = $monthlyBookings[$label] ?? 0;
        }

        return view('admin.alamanda_dashboard', compact(
            'totalRevenue',
            'totalBookings',
            'totalCustomers',
            'totalReviews',
            'recentBookings',
            'recentUsers',
            'chartLabels',
            'revenueData',
            'bookingsData'
        ));
    }
}
