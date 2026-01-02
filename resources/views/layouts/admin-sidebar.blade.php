{{-- Admin Sidebar Navigation --}}
{{-- Usage: @include('layouts.admin-sidebar', ['activePage' => 'dashboard']) ---

  Available activePage values:
  - dashboard, admin, users, bookings, packages, coupons, reviews, reports, invoice
--}}
@php
  $active = $activePage ?? 'dashboard';
@endphp

<aside class="col-auto sidebar p-4">
  <div class="sidebar-logo">
    <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Alamanda Logo" />
    <span>Alamanda</span>
  </div>

  <!-- Dashboard -->
  <a href="{{ url('/admin/dashboard') }}" class="sidebar-link {{ $active === 'dashboard' ? 'active' : '' }}">
    <span>🏠</span> Dashboard
  </a>

  <!-- Users Section -->
  <div class="sidebar-section-label">Users</div>
  <a href="{{ url('/admin/admin_dashboard') }}" class="sidebar-link {{ $active === 'admin' ? 'active' : '' }}">
    <span>👤</span> Admin
  </a>
  <a href="{{ url('/admin/users') }}" class="sidebar-link {{ $active === 'users' ? 'active' : '' }}">
    <span>👥</span> User Management
  </a>

  <!-- Other Section -->
  <div class="sidebar-section-label mt-3">Others</div>
  <a href="{{ url('/admin/bookings') }}" class="sidebar-link {{ $active === 'bookings' ? 'active' : '' }}">
    <span>📦</span> Bookings
  </a>
  <a href="{{ url('/admin/packages') }}" class="sidebar-link {{ $active === 'packages' ? 'active' : '' }}">
    <span>🛥</span> Packages
  </a>
  <a href="{{ url('/admin/coupons') }}" class="sidebar-link {{ $active === 'coupons' ? 'active' : '' }}">
    <span>🎟</span> Coupons
  </a>
  <a href="{{ url('/admin/reviews') }}" class="sidebar-link {{ $active === 'reviews' ? 'active' : '' }}">
    <span>⭐</span> Reviews
  </a>
  <a href="{{ url('/admin/reports') }}" class="sidebar-link {{ $active === 'reports' ? 'active' : '' }}">
    <span>📄</span> Generate Report
  </a>

  <!-- Logout -->
  <form method="POST" action="{{ url('/logout') }}" class="mt-3">
    @csrf
    <button type="submit" class="sidebar-link text-danger" style="background:none;border:none;cursor:pointer;width:100%;text-align:left;">
      <span>🚪</span> Logout
    </button>
  </form>
</aside>

<style>
  .sidebar-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
  }

  .sidebar-logo img {
    width: 40px;
    height: 40px;
    object-fit: contain;
  }

  .sidebar-logo span {
    font-weight: 700;
    font-size: 18px;
    color: #374151;
  }

  .sidebar-section-label {
    color: #9ca3af;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    margin-top: 16px;
    margin-bottom: 8px;
    padding-left: 6px;
  }

  .sidebar-link {
    text-decoration: none;
    color: #374151;
    padding: 12px 18px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    margin-bottom: 6px;
    transition: all 0.2s ease;
  }

  .sidebar-link:hover,
  .sidebar-link.active {
    background: #eef2ff;
    color: #4f46e5;
  }
</style>
