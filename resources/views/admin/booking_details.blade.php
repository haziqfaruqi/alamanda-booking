<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Booking Details | Alamanda Houseboat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
  font-family:'Inter',sans-serif;
  background:#f4f6fb;
  overflow-x:hidden;
}

/* ===== SIDEBAR ===== */
.sidebar{
  width:260px;
  background:#ffffff;
  min-height:100vh;
  border-right:1px solid #e5e7eb;
  position:fixed;
  left:0;
  top:0;
  padding:24px;
}

/* ===== MAIN CONTENT ===== */
.main-content{
  margin-left:260px;
  padding:32px;
  width:calc(100vw - 260px);
}

/* ===== TOP BAR ===== */
.top-bar{
  display:flex;
  justify-content:flex-end;
  align-items:center;
  gap:16px;
  margin-bottom:30px;
}

.avatar{
  width:46px;
  height:46px;
  border-radius:50%;
  background:#4f46e5;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
}

/* ===== CARD ===== */
.card{
  border:none;
  border-radius:18px;
  box-shadow:0 10px 28px rgba(0,0,0,.06);
}

/* ===== SECTION TITLE ===== */
.section-title{
  font-weight:700;
  margin-bottom:16px;
}

/* ===== BADGE ===== */
.badge-completed{
  background:#dcfce7;
  color:#166534;
  font-weight:600;
}

.badge-pending{
  background:#fef3c7;
  color:#92400e;
  font-weight:600;
}

.badge-confirmed{
  background:#dbeafe;
  color:#1e40af;
  font-weight:600;
}

.badge-cancelled{
  background:#fee2e2;
  color:#991b1b;
  font-weight:600;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
@include('layouts.admin-sidebar', ['activePage' => 'bookings'])

<!-- MAIN -->
<main class="main-content">

  <!-- TOP BAR -->
  <div class="top-bar">
    <div class="text-end">
      <div class="fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</div>
      <small class="text-muted">Administrator</small>
    </div>
    <div class="avatar">{{ substr(auth()->user()->name, 0, 1) ?? 'A' }}</div>
  </div>

  <!-- BACK BUTTON -->
  <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary mb-4">
    ← Back to Bookings
  </a>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <!-- BOOKING SUMMARY -->
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0">Booking Summary</h5>
      <span class="badge badge-{{ $booking->status }} px-3 py-2">
        {{ ucfirst($booking->status) }}
      </span>
    </div>

    <div class="row g-4">
      <div class="col-md-3">
        <p class="mb-1 fw-semibold">Package</p>
        <p>{{ $booking->package->name ?? 'N/A' }}</p>
      </div>
      <div class="col-md-3">
        <p class="mb-1 fw-semibold">Check-In</p>
        <p>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</p>
      </div>
      <div class="col-md-3">
        <p class="mb-1 fw-semibold">Check-Out</p>
        <p>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</p>
      </div>
      <div class="col-md-3">
        <p class="mb-1 fw-semibold">Booking Date</p>
        <p>{{ $booking->created_at->format('d M Y') }}</p>
      </div>
    </div>
  </div>

  <!-- CONTACT DETAILS -->
  <div class="card p-4 mb-4">
    <h6 class="section-title">Contact Details</h6>
    <div class="row">
      <div class="col-md-4">
        <p class="mb-1"><strong>Name</strong></p>
        <p>{{ $booking->contact_name }}</p>
      </div>
      <div class="col-md-4">
        <p class="mb-1"><strong>Email</strong></p>
        <p>{{ $booking->contact_email }}</p>
      </div>
      <div class="col-md-4">
        <p class="mb-1"><strong>Phone</strong></p>
        <p>{{ $booking->contact_phone ?? '-' }}</p>
      </div>
    </div>
  </div>

  <!-- GUEST LIST -->
  <div class="card p-4 mb-4">
    <h6 class="section-title">Guest List ({{ $booking->total_guests ?? $booking->guests->count() }} Pax)</h6>

    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>IC / Passport</th>
            <th>Category</th>
          </tr>
        </thead>
        <tbody>
          @forelse($booking->guests as $index => $guest)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $guest->guest_name }}</td>
            <td>{{ $guest->guest_ic ?? '-' }}</td>
            <td>{{ ucfirst($guest->guest_type) }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center text-muted py-3">No guest details recorded</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- PAYMENT SUMMARY -->
  <div class="card p-4">
    <h6 class="section-title">Payment Summary</h6>

    <div class="row">
      <div class="col-md-3">
        <p class="mb-1">Adults</p>
        <strong>{{ $booking->total_adults }} pax</strong>
      </div>
      <div class="col-md-3">
        <p class="mb-1">Children</p>
        <strong>{{ $booking->total_children ?? 0 }} pax</strong>
      </div>
      <div class="col-md-3">
        <p class="mb-1">Payment Status</p>
        <strong>{{ ucfirst($booking->payment_status) }}</strong>
      </div>
      <div class="col-md-3">
        <p class="mb-1">Total Payment</p>
        <strong class="fs-5">RM {{ number_format($booking->total_price, 2) }}</strong>
      </div>
    </div>

    @if($booking->special_requests)
    <hr class="my-3">
    <div class="row">
      <div class="col-12">
        <p class="mb-1"><strong>Special Requests</strong></p>
        <p>{{ $booking->special_requests }}</p>
      </div>
    </div>
    @endif
  </div>

  <!-- STATUS UPDATE FORM -->
  <div class="card p-4 mt-4">
    <h6 class="section-title">Update Booking Status</h6>

    <form method="POST" action="{{ route('admin.bookings.update-status', $booking->id) }}">
      @csrf
      @method('PUT')

      <div class="row align-items-end">
        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary w-100">Update Status</button>
        </div>
      </div>
    </form>
  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
