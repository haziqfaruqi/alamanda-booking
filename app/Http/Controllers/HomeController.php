<?php

namespace App\Http\Controllers;

use App\Models\Review;

class HomeController extends Controller
{
    /**
     * Show the home page
     */
    public function index()
    {
        // Get testimonials from reviews with 4-5 star ratings
        $testimonials = Review::with(['user', 'booking'])
            ->whereIn('rating', [4, 5])
            ->whereNotNull('feedback')
            ->where('feedback', '!=', '')
            ->latest()
            ->take(4)
            ->get();

        return view('user.alamanda_home', compact('testimonials'));
    }

    /**
     * Show the gallery page
     */
    public function gallery()
    {
        return view('user.gallery');
    }
}
