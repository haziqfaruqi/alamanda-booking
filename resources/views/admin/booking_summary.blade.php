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

.sidebar h4{
  font-weight:700;
  margin-bottom:30px;
}

.sidebar-logo{
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom:24px;
}

.sidebar-logo img{
  width:40px;
  height:40px;
  object-fit:contain;
}

.sidebar-logo span{
  font-weight:700;
  font-size:18px;
  color:#374151;
}

.sidebar a{
  text-decoration:none;
  color:#374151;
  padding:12px 18px;
  border-radius:12px;
  display:flex;
  align-items:center;
  gap:12px;
  font-weight:500;
  margin-bottom:8px;
}

.sidebar a:hover,
.sidebar a.active{
  background:#eef2ff;
  color:#4f46e5;
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
<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Alamanda Logo" />
    <span>Alamanda</span>
  </div>
  <a href="alamanda_dashboard.html"><span>🏠</span> Dashboard</a><br>
  Users<BR><br>
  <a href="admin_dashboard.html"><span>👥</span> Admin </a>
   <a href="user_dashboard.html"><span>👥</span> User Management</a>
   <br> Others<br><br>
  <a href="booking_dashboard.html"><span>📦</span> Bookings</a>
  <a href="package_dashboard.html"><span>🛥</span> Packages</a>
  <a href="generate_report.html"><span>📄</span> Generate Report</a>
  <a href="#"><span>⚙️</span> Download Invoice</a>
  <a class="text-danger"><span>🚪</span> Logout</a>
</aside>

<!-- MAIN CONTENT -->
<main class="main-content">

  <!-- TOP BAR -->
  <div class="top-bar">
    <div class="text-end">
      <div class="fw-semibold">Sofiya Alissa</div>
      <small class="text-muted">Staff Administrator</small>
    </div>
    <div class="avatar">S</div>
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
            <th>Booking Date</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>

          <!-- BOOKING -->
          <tr>
            <td>
              <strong>Ahmad Rahman</strong><br>
              <small class="text-muted">ahmad@email.com</small>
            </td>
            <td>
              <strong>Full Board</strong><br>
              <small>12 Aug – 14 Aug 2025</small>
            </td>
            <td>
              <span class="badge badge-completed px-3 py-2">Completed</span>
            </td>
            <td>10 Aug 2025</td>
            <td>
              <button class="btn btn-sm btn-outline-primary"
                data-bs-toggle="collapse"
                data-bs-target="#detail1">
                View Details
              </button>
            </td>
          </tr>

          <!-- DETAILS -->
          <tr class="collapse" id="detail1">
            <td colspan="5">
              <div class="detail-box mt-3">
                <div class="row g-4">
                  <div class="col-md-4">
                    <h6 class="fw-bold">Contact Details</h6>
                    <p class="mb-1">📞 012-3456789</p>
                    <p class="mb-0">📧 ahmad@email.com</p>
                  </div>
                  <div class="col-md-4">
                    <h6 class="fw-bold">Guests Summary</h6>
                    <p class="mb-1">Adults: 18</p>
                    <p class="mb-0">Children: 7</p>
                  </div>
                  <div class="col-md-4 text-md-end">
                    <h6 class="fw-bold">Payment</h6>
                    <p class="mb-1">Total: <strong>RM 12,350</strong></p>
                    <a href="booking_details.html" class="btn btn-primary btn-sm">
                      View Full Details
                    </a>
                  </div>
                </div>
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
