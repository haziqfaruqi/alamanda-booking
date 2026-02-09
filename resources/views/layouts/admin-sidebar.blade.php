{{-- Admin Sidebar Navigation --}}
{{-- Usage: @include('layouts.admin-sidebar', ['activePage' => 'dashboard']) ---

  Available activePage values:
  - dashboard, admin, users, bookings, packages, coupons, reviews, reports, invoice
--}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

@php
  $active = $activePage ?? 'dashboard';
@endphp

<aside class="sidebar">
  <div class="sidebar-brand">
    <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Alamanda Logo">
  </div>

  <div class="nav-label">Main Menu</div>
  <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ $active === 'dashboard' ? 'active' : '' }}">
    <i class="fa-solid fa-chart-pie"></i> Dashboard
  </a>

  <div class="nav-label">Management</div>
  <a href="{{ url('/admin/admin_dashboard') }}" class="nav-link {{ $active === 'admin' ? 'active' : '' }}">
    <i class="fa-solid fa-user-shield"></i> Admin List
  </a>
  <a href="{{ url('/admin/users') }}" class="nav-link {{ $active === 'users' ? 'active' : '' }}">
    <i class="fa-solid fa-users-gear"></i> Customers
  </a>

  <div class="nav-label">Operations</div>
  <a href="{{ url('/admin/bookings') }}" class="nav-link {{ $active === 'bookings' ? 'active' : '' }}">
    <i class="fa-solid fa-calendar-check"></i> Bookings
  </a>
  <a href="{{ url('/admin/packages') }}" class="nav-link {{ $active === 'packages' ? 'active' : '' }}">
    <i class="fa-solid fa-anchor"></i> Boat Packages
  </a>
  <a href="{{ url('/admin/coupons') }}" class="nav-link {{ $active === 'coupons' ? 'active' : '' }}">
    <i class="fa-solid fa-ticket"></i> Promo Coupons
  </a>

  <div class="nav-label">Feedback</div>
  <a href="{{ url('/admin/reviews') }}" class="nav-link {{ $active === 'reviews' ? 'active' : '' }}">
    <i class="fa-solid fa-star"></i> Guest Reviews
  </a>
  <a href="{{ url('/admin/reports') }}" class="nav-link {{ $active === 'reports' ? 'active' : '' }}">
    <i class="fa-solid fa-file-invoice-dollar"></i> Business Reports
  </a>

  <div class="sidebar-footer">
    <form method="POST" action="{{ url('/logout') }}">
      @csrf
      <button type="submit" class="logout-btn">
        <i class="fa-solid fa-power-off"></i> Logout
      </button>
    </form>
  </div>
</aside>

<style>
  .sidebar {
    width: 260px;
    height: 100vh;
    background: #1e2632;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    font-family: 'Plus Jakarta Sans', sans-serif;
    
    /* FIX: Tambah dua baris ni untuk bagi dia boleh scroll */
    overflow-y: auto;
    overflow-x: hidden;
  }

  /* Custom Scrollbar supaya nampak lagi sleek (Optional) */
  .sidebar::-webkit-scrollbar {
    width: 5px;
  }
  
  .sidebar::-webkit-scrollbar-track {
    background: #1e2632;
  }
  
  .sidebar::-webkit-scrollbar-thumb {
    background: #374151;
    border-radius: 10px;
  }

  .sidebar-brand {
    padding: 2.5rem 1.5rem;
    display: flex;
    justify-content: center;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    flex-shrink: 0; /* Pastikan logo tak mengecil bila scroll */
  }

  .sidebar-brand img {
    width: 150px;
    filter: brightness(0) invert(1);
    object-fit: contain;
  }

  .nav-label {
    color: #4e5d71;
    font-size: 0.65rem;
    text-transform: uppercase;
    font-weight: 800;
    padding: 1.5rem 1.5rem 0.5rem;
    letter-spacing: 1.2px;
  }

  .nav-link {
    color: #a0aec0;
    padding: 0.75rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none !important;
    font-size: 0.85rem;
    font-weight: 500;
    transition: 0.2s;
  }

  .nav-link i { width: 18px; text-align: center; font-size: 16px; }

  .nav-link:hover, .nav-link.active {
    color: #fff;
    background: rgba(255,255,255,0.05);
  }

  .nav-link.active {
    border-left: 4px solid #4e73df;
    background: rgba(78, 115, 223, 0.1);
  }

  /* Footer Section - Gunakan padding bawah supaya tak rapat sangat dengan last menu */
  .sidebar-footer {
    margin-top: auto;
    padding: 20px 20px 40px 20px; 
    border-top: 1px solid rgba(255,255,255,0.05);
  }

  .logout-btn {
    width: 100%;
    background: none;
    border: none;
    color: #fc8181;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
  }
</style>