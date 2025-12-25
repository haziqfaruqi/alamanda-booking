@props(['activePage' => ''])

<!-- User Header (Solid Navbar Style - for Booking, Profile, etc.) -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

<style>
/* USER HEADER STYLES */
.user-navbar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  padding: 18px 60px;
  background: rgba(63,68,82,0.95);
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 100;
}

.user-nav-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-nav-left img { width: 48px; }

.user-nav-left span {
  color: #fff;
  font-size: 22px;
  font-weight: 600;
}

.user-nav-right {
  display: flex;
  gap: 36px;
  align-items: center;
}

.user-nav-right a {
  color: #ddd;
  text-decoration: none;
  font-weight: 500;
}

.user-nav-right a:hover { color: #ff8c32; }

.user-nav-right a.active {
  color: #ff8c32;
  font-weight: 700;
}

.user-nav-book {
  background: #ff8c32;
  color: #fff !important;
  padding: 10px 22px;
  border-radius: 30px;
  font-weight: 600;
}

/* Profile Dropdown */
.user-profile-menu {
  position: relative;
}

.user-profile-icon {
  width: 42px;
  height: 42px;
  background: #ff8c32;
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  cursor: pointer;
}

.user-dropdown {
  position: absolute;
  right: 0;
  top: 55px;
  background: #fff;
  border-radius: 12px;
  width: 180px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.15);
  display: none;
  overflow: hidden;
}

.user-dropdown.show {
  display: block;
}

.user-dropdown a {
  display: block;
  padding: 14px 18px;
  color: #333;
  text-decoration: none;
  font-weight: 500;
}

.user-dropdown a:hover {
  background: #f3f3f3;
}
</style>

<!-- User Navbar -->
<div class="user-navbar">
  <div class="user-nav-left">
    <a href="{{ url('/alamanda_home') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
      <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Alamanda Logo" />
      <span>Alamanda Houseboat</span>
    </a>
  </div>
  <div class="user-nav-right">
    <a href="{{ url('/alamanda_home') }}" class="{{ $activePage === 'home' ? 'active' : '' }}">Home</a>
    <a href="{{ url('/alamanda_home') }}#packages" class="{{ $activePage === 'packages' ? 'active' : '' }}">Packages</a>
    <a href="{{ url('/alamanda_home') }}#adventure" class="{{ $activePage === 'about' ? 'active' : '' }}">About Us</a>
    <a href="{{ url('/alamanda_home') }}#kenyir" class="{{ $activePage === 'kenyir' ? 'active' : '' }}">Kenyir Lake</a>
    <a href="{{ url('/booking') }}" class="user-nav-book {{ $activePage === 'booking' ? 'active' : '' }}">Book Now</a>

    @auth
      <div class="user-profile-menu">
        <div class="user-profile-icon" onclick="toggleUserMenu()">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="user-dropdown" id="userDropdown">
          <a href="{{ url('/edit_profile') }}">Edit Profile</a>
          <a href="{{ url('/view_booking') }}">View Bookings</a>
          <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
          <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    @else
      <a href="{{ url('/alamanda_login') }}" class="{{ $activePage === 'login' ? 'active' : '' }}">Sign In</a>
      <a href="{{ url('/alamanda_register') }}" class="{{ $activePage === 'register' ? 'active' : '' }}">Join Us</a>
    @endauth
  </div>
</div>

<script>
function toggleUserMenu() {
  const dropdown = document.getElementById("userDropdown");
  dropdown.classList.toggle("show");
}

// Close dropdown when clicking outside
document.addEventListener("click", function(event) {
  const dropdown = document.getElementById("userDropdown");
  const profileIcon = document.querySelector(".user-profile-icon");
  if (dropdown && profileIcon && !dropdown.contains(event.target) && !profileIcon.contains(event.target)) {
    dropdown.classList.remove("show");
  }
});
</script>
