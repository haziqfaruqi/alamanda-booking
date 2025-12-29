<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Generate Report | Alamanda Houseboat</title>
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
  background:#fff;
  min-height:100vh;
  border-right:1px solid #e5e7eb;
  position:fixed;
  top:0;
  left:0;
  padding:24px;
}

/* ===== MAIN ===== */
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

.generate-btn{
  background:#4f46e5;
  border:none;
}
.generate-btn:hover{
  background:#c2185b;
}
.generate-btn:disabled{
  background:#9ca3af;
  cursor:not-allowed;
}

.info-box{
  background:#f0fdf4;
  border:1px solid #bbf7d0;
  border-radius:12px;
  padding:16px;
  margin-bottom:20px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
@include('layouts.admin-sidebar', ['activePage' => 'reports'])

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

  <!-- HEADER -->
  <h4 class="fw-bold mb-4">Generate Report</h4>

  <!-- CARD -->
  <div class="card p-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- FORM -->
    <form method="POST" action="{{ route('admin.reports.generate') }}" id="reportForm">
      @csrf

      <!-- Report Type -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Report Type</label>
        <select class="form-select" name="report_type" id="reportType" required>
          <option value="" selected disabled>Select Report Type</option>
          @foreach($reportTypes ?? [] as $key => $label)
          <option value="{{ $key }}">{{ $label }}</option>
          @endforeach
        </select>
        <small class="text-muted mt-1 d-block">
          <strong>Booking Report:</strong> All booking details including customer info<br>
          <strong>Revenue Report:</strong> Paid bookings with revenue data<br>
          <strong>Package Report:</strong> Package inventory and pricing
        </small>
      </div>

      <!-- Month & Year -->
      <div class="row mb-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Month (Optional)</label>
          <select class="form-select" name="month" id="month">
            <option value="">All Months</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Year</label>
          <select class="form-select" name="year" id="year" required>
            <option value="" selected disabled>Select Year</option>
            @foreach($years ?? [] as $y)
            <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Info Box -->
      <div class="info-box">
        <div class="d-flex align-items-start gap-2">
          <span style="font-size:20px">📊</span>
          <div>
            <strong>Export Format: CSV</strong><br>
            <small class="text-muted">The report will be downloaded as a CSV file that can be opened in Excel, Google Sheets, or any spreadsheet application.</small>
          </div>
        </div>
      </div>

      <!-- BUTTON -->
      <button type="submit" class="btn generate-btn text-white px-4" id="generateBtn" disabled>
        <span id="btnText">Select Report Type and Year</span>
      </button>

    </form>

  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Form validation and handling
const reportType = document.getElementById('reportType');
const year = document.getElementById('year');
const generateBtn = document.getElementById('generateBtn');
const btnText = document.getElementById('btnText');
const reportForm = document.getElementById('reportForm');

function validateForm() {
  const isValid = reportType.value !== '' && year.value !== '';

  if (isValid) {
    generateBtn.disabled = false;
    btnText.textContent = 'Export to CSV';
  } else {
    generateBtn.disabled = true;
    if (reportType.value === '' && year.value === '') {
      btnText.textContent = 'Select Report Type and Year';
    } else if (reportType.value === '') {
      btnText.textContent = 'Select Report Type';
    } else {
      btnText.textContent = 'Select Year';
    }
  }
}

reportType.addEventListener('change', validateForm);
year.addEventListener('change', validateForm);

// Set current year as default
const currentYear = new Date().getFullYear();
const yearOptions = year.querySelectorAll('option');
for (let option of yearOptions) {
  if (option.value == currentYear) {
    option.selected = true;
    break;
  }
}
validateForm();
</script>
</body>
</html>
