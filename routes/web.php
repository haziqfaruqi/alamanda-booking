<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return redirect()->route('home');
});

// Home / Landing Page
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/alamanda_home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.view');

// Gallery Page
Route::get('/gallery', [App\Http\Controllers\HomeController::class, 'gallery'])->name('gallery');

// ==================== AUTH ROUTES ====================
// Login
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::get('/alamanda_login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login.view');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.update');

// Register
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::get('/alamanda_register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register.view');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');

// ==================== USER ROUTES (Protected) ====================
Route::middleware(['auth', 'is.not.admin'])->group(function () {
    // Booking
    Route::get('/booking', [App\Http\Controllers\BookingController::class, 'create'])->name('booking');
    Route::post('/booking', [App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', [App\Http\Controllers\BookingController::class, 'index'])->name('bookings');
    Route::get('/view_booking', [App\Http\Controllers\BookingController::class, 'index'])->name('view-booking.view');
    Route::get('/booking/{id}', [App\Http\Controllers\BookingController::class, 'show'])->name('booking.show');
    Route::delete('/booking/{id}', [App\Http\Controllers\BookingController::class, 'cancel'])->name('booking.cancel');

    // Profile
    Route::get('/edit_profile', [App\Http\Controllers\UserController::class, 'editProfile'])->name('edit-profile');
    Route::get('/edit-profile', [App\Http\Controllers\UserController::class, 'editProfile'])->name('edit-profile.view');
    Route::post('/edit_profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('update-profile');

    // Payment
    Route::get('/payment/{booking}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment');
    Route::post('/payment/{booking}', [App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process');

    // Reviews
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{bookingId}', [App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
    Route::put('/reviews/{id}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// ToyyibPay Callback Routes (outside auth - called by ToyyibPay server)
Route::get('/payment/toyyibpay/return', [App\Http\Controllers\PaymentController::class, 'toyyibpayReturn'])->name('payment.toyyibpay.return');
Route::post('/payment/toyyibpay/callback', [App\Http\Controllers\PaymentController::class, 'toyyibpayCallback'])->name('payment.toyyibpay.callback');

// Invoice & Receipt Routes (accessible by both users and admins)
Route::middleware(['auth'])->group(function () {
    // Invoice
    Route::get('/invoice/{booking}', [App\Http\Controllers\InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{id}/generate', [App\Http\Controllers\InvoiceController::class, 'generate'])->name('invoice.generate');
    Route::get('/invoice/{id}/download', [App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download');

    // Receipt
    Route::get('/receipt/{id}/generate', [App\Http\Controllers\ReceiptController::class, 'generate'])->name('receipt.generate');
    Route::get('/receipt/{id}/download', [App\Http\Controllers\ReceiptController::class, 'download'])->name('receipt.download');
    Route::get('/receipt/{id}/view', [App\Http\Controllers\ReceiptController::class, 'view'])->name('receipt.view');
});

// ==================== ADMIN ROUTES (Protected) ====================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Admin Management
    Route::get('/admin_dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admins');
    Route::post('/admin_dashboard', [App\Http\Controllers\Admin\AdminController::class, 'store'])->name('admins.store');
    Route::put('/admin_dashboard/{id}', [App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admins.update');
    Route::delete('/admin_dashboard/{id}', [App\Http\Controllers\Admin\AdminController::class, 'destroy'])->name('admins.destroy');

    // User Management
    Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users');
    Route::post('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');

    // Booking Management
    Route::get('/bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{id}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{id}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Package Management
    Route::get('/packages', [App\Http\Controllers\Admin\PackageController::class, 'index'])->name('packages');
    Route::get('/packages/create', [App\Http\Controllers\Admin\PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [App\Http\Controllers\Admin\PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{id}/edit', [App\Http\Controllers\Admin\PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{id}', [App\Http\Controllers\Admin\PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{id}', [App\Http\Controllers\Admin\PackageController::class, 'destroy'])->name('packages.destroy');

    // Coupon Management
    Route::get('/coupons', [App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupons');
    Route::post('/coupons', [App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{id}/edit', [App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('coupons.edit');
    Route::post('/coupons/{id}/update', [App\Http\Controllers\Admin\CouponController::class, 'updateDetails'])->name('coupons.update-details');
    Route::put('/coupons/{id}', [App\Http\Controllers\Admin\CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{id}', [App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupons.destroy');

    // Reports
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
    Route::post('/reports/generate', [App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');
});

// Admin static views (temporary, for testing)
Route::view('/admin/staff/login', 'admin.alamanda_staff')->name('staff.login.view');
Route::view('/admin/bookings-summary', 'admin.booking_summary')->name('admin.bookings-summary.view');

// ==================== API ROUTES ====================
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Availability Check
    Route::get('/availability/check', [App\Http\Controllers\Api\AvailabilityController::class, 'check'])->name('availability.check');
    Route::get('/availability/unavailable-dates', [App\Http\Controllers\Api\AvailabilityController::class, 'getUnavailableDates'])->name('availability.unavailable-dates');

    // Coupon Validation
    Route::post('/coupons/validate', [App\Http\Controllers\Admin\CouponController::class, 'validateCode'])->name('coupons.validate');
});
