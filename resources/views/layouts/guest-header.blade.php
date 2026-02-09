@props(['activePage' => ''])

<!-- Guest Header (Transparent/Floating Style - for Home, Login, Register) -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

<style>
/* GUEST HEADER STYLES */
.guest-header-nav {
  position: fixed;
  top: 20px;
  right: 50px;
  display: flex;
  gap: 45px;
  font-size: 18px;
  z-index: 50;
  transition: 0.35s ease;
}

.guest-header-nav a {
  color: rgba(255,255,255,0.9);
  text-decoration: none;
  font-weight: 500;
}

.guest-header-nav a:hover { color: #ff8c32; }

.guest-header-nav a.active {
  color: #ff8c32;
  font-weight: 700;
}

.guest-header-nav.scrolled {
  background: rgba(63,68,82,0.95);
  padding: 30px 55px;
  top: 0;
  left: 0;
  right: 0;
  width: 100%;
  justify-content: flex-end;
}

.guest-header-logo {
  position: fixed;
  top: 20px;
  left: 50px;
  z-index: 60;
  display: flex;
  align-items: center;
  gap: 12px;
}

.guest-header-logo img { width: 80px; }

.guest-header-logo-text {
  color: white;
  font-size: 22px;
  font-weight: 600;
}
</style>

<!-- Logo -->
<div class="guest-header-logo">
  <a href="{{ url('/alamanda_home') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
    <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Alamanda Logo" />
    <span class="guest-header-logo-text">Alamanda Houseboat</span>
  </a>
</div>

<!-- Navigation -->
<nav class="guest-header-nav" id="guestNavbar">
  <a href="{{ url('/alamanda_home') }}" class="{{ $activePage === 'home' ? 'active' : '' }}">Home</a>
  <a href="{{ url('/alamanda_home') }}#packages" class="{{ $activePage === 'packages' ? 'active' : '' }}">Packages</a>
  <a href="{{ url('/alamanda_home') }}#adventure" class="{{ $activePage === 'about' ? 'active' : '' }}">About Us</a>
  <a href="{{ url('/alamanda_home') }}#kenyir" class="{{ $activePage === 'kenyir' ? 'active' : '' }}">Kenyir Lake</a>
  @guest
    <a href="{{ url('/alamanda_login') }}" class="{{ $activePage === 'login' ? 'active' : '' }}">Sign In</a>
    <a href="{{ url('/alamanda_register') }}" class="{{ $activePage === 'register' ? 'active' : '' }}">Join Us</a>
  @else
    <a href="{{ url('/booking') }}" class="{{ $activePage === 'booking' ? 'active' : '' }}">Book Now</a>
  @endguest
  @guest
    <a href="{{ url('/alamanda_login') }}?redirect=booking" class="nav-book" style="background: #ff8c32; color: #fff !important; padding: 10px 22px; border-radius: 30px; font-weight: 600;">Book Now</a>
  @else
    <a href="{{ url('/booking') }}" class="nav-book" style="background: #ff8c32; color: #fff !important; padding: 10px 22px; border-radius: 30px; font-weight: 600;">Book Now</a>
  @endguest
</nav>

<script>
// Scroll effect for guest header
window.addEventListener("scroll", function() {
  const nav = document.getElementById("guestNavbar");
  if (nav) {
    if (window.scrollY > 80) nav.classList.add("scrolled");
    else nav.classList.remove("scrolled");
  }
});
</script>
