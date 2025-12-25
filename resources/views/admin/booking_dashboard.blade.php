<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bookings | Alamanda Houseboat</title>
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

/* Avatar */
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
  width:100%;
}

/* ===== BADGES ===== */
.badge-completed{
  background:#dcfce7;
  color:#166534;
  font-weight:600;
}
.badge-cancelled{
  background:#fee2e2;
  color:#991b1b;
  font-weight:600;
}

/* ===== DETAILS BOX ===== */
.detail-box{
  background:#f9fafb;
  border-radius:14px;
  padding:22px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
@include('layouts.admin-sidebar', ['activePage' => 'bookings'])

<!-- MAIN CONTENT -->
<main class="main-content">

  <!-- TOP BAR -->
  <div class="top-bar">
    <div class="text-end">
      <div class="fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</div>
      <small class="text-muted">Administrator</small>
    </div>
    <div class="avatar">{{ substr(auth()->user()->name, 0, 1) ?? 'A' }}</div>
  </div>

  <h4 class="fw-bold mb-4">Bookings</h4>

  <!-- BOOKINGS CARD -->
  <div class="card p-4">

    <!-- FILTER -->
    <div class="row g-3 align-items-end mb-4">
      <div class="col-md-3">
        <label class="form-label fw-semibold">Month</label>
        <select class="form-select">
          <option selected>All Months</option>
          <option>January</option>
          <option>February</option>
          <option>March</option>
          <option>April</option>
          <option>May</option>
          <option>June</option>
          <option>July</option>
          <option>August</option>
          <option>September</option>
          <option>October</option>
          <option>November</option>
          <option>December</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label fw-semibold">Year</label>
        <select class="form-select">
          <option selected>2025</option>
          <option>2024</option>
          <option>2023</option>
        </select>
      </div>

      <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-primary w-100">Filter</button>
        <button class="btn btn-outline-secondary w-100">Reset</button>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>Contact</th>
            <th>Details</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Booking Date</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          @forelse($bookings ?? [] as $booking)
          <tr>
            <td>
              <strong>{{ $booking->contact_name }}</strong><br>
              <small class="text-muted">{{ $booking->contact_email }}</small>
            </td>
            <td>
              <strong>{{ $booking->package->name ?? 'N/A' }}</strong><br>
              <small>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }} – {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</small>
            </td>
            <td>
              <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : ($booking->status === 'completed' ? 'info' : 'warning')) }} px-3 py-2">
                {{ ucfirst($booking->status) }}
              </span>
            </td>
            <td>
              <span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }} px-3 py-2">
                {{ ucfirst($booking->payment_status) }}
              </span>
            </td>
            <td>{{ $booking->created_at->format('d M Y') }}</td>
            <td>
              <div class="d-flex gap-2">
                @if($booking->payment_status === 'paid' && $booking->receipt_path)
                  <a href="{{ route('receipt.view', $booking->id) }}" target="_blank" class="btn btn-sm btn-outline-success" title="View Receipt">
                    <i class="bi bi-file-earmark-text"></i> Receipt
                  </a>
                @endif
                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                  View Details
                </a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No bookings found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
