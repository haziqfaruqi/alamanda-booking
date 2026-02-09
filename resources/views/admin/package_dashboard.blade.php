<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Package Management | Alamanda Houseboat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
  font-family:'Inter',sans-serif;
  background:#f4f6fb;
}

/* SIDEBAR */
.sidebar{
  width:260px;
  background:#fff;
  min-height:100vh;
  border-right:1px solid #e5e7eb;
  position:fixed;
  padding:24px;
}

/* MAIN */
.main-content{
  margin-left:260px;
  padding:32px;
}

/* TOP BAR */
.top-bar{
  display:flex;
  justify-content:flex-end;
  gap:16px;
  margin-bottom:30px;
}
.avatar{
  width:44px;
  height:44px;
  border-radius:50%;
  background:#4f46e5;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
}

/* CARD */
.card{
  border:none;
  border-radius:18px;
  box-shadow:0 10px 28px rgba(0,0,0,.06);
}

/* Package Type Tabs */
.package-tabs {
  display:flex;
  gap:12px;
  margin-bottom:24px;
}
.package-tab {
  padding:10px 24px;
  border:1px solid #e5e7eb;
  border-radius:10px;
  background:#fff;
  cursor:pointer;
  transition:all 0.2s;
}
.package-tab:hover {
  background:#f9fafb;
}
.package-tab.active {
  background:#4f46e5;
  color:#fff;
  border-color:#4f46e5;
}

/* Duration Toggle */
.duration-toggle {
  display:flex;
  gap:8px;
  margin-bottom:24px;
}
.duration-btn {
  padding:8px 16px;
  border:1px solid #e5e7eb;
  border-radius:8px;
  background:#fff;
  cursor:pointer;
  font-size:14px;
  transition:all 0.2s;
}
.duration-btn:hover {
  background:#f9fafb;
}
.duration-btn.active {
  background:#10b981;
  color:#fff;
  border-color:#10b981;
}

/* Package Card */
.package-card {
  border:2px solid #e5e7eb;
  border-radius:16px;
  padding:20px;
  margin-bottom:16px;
  background:#fff;
  transition:all 0.2s;
}
.package-card:hover {
  border-color:#4f46e5;
}
.package-card.exists {
  border-color:#10b981;
  background:#f0fdf4;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
@include('layouts.admin-sidebar', ['activePage' => 'packages'])

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
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Package Management</h4>
  </div>

  <!-- SUCCESS MESSAGE -->
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <!-- ERROR MESSAGES -->
  @if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  @if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <div class="card p-4">
    <!-- Package Type Tabs -->
    <div class="package-tabs">
      <button class="package-tab @if(request('type', 'standard') == 'standard') active @endif" onclick="switchTab('standard')" id="tab-standard">
        <strong>Standard Package</strong>
      </button>
      <button class="package-tab @if(request('type') == 'fullboard') active @endif" onclick="switchTab('fullboard')" id="tab-fullboard">
        <strong>Full Board Package</strong>
      </button>
    </div>

    <!-- Duration Toggle -->
    <div class="duration-toggle">
      <button class="duration-btn @if(request('duration', '2D1N') == '2D1N') active @endif" onclick="switchDuration('2D1N')" id="dur-2D1N">2D1N</button>
      <button class="duration-btn @if(request('duration') == '3D2N') active @endif" onclick="switchDuration('3D2N')" id="dur-3D2N">3D2N</button>
      <button class="duration-btn @if(request('duration') == '4D3N') active @endif" onclick="switchDuration('4D3N')" id="dur-4D3N">4D3N</button>
      <button class="duration-btn @if(request('duration') == '5D4N') active @endif" onclick="switchDuration('5D4N')" id="dur-5D4N">5D4N</button>
      <button class="duration-btn @if(request('duration') == 'custom') active @endif" onclick="showCustomDuration()" id="dur-custom">+ Custom</button>
    </div>

    <!-- Custom Duration Input (hidden by default) -->
    <div id="customDurationInput" class="mb-4" style="display:none;">
      <div class="row align-items-center">
        <div class="col-md-4">
          <label class="form-label">Enter Custom Duration</label>
          <input type="text" id="customDurationValue" class="form-control" placeholder="e.g., 5D4N, 1 Week" maxlength="20">
        </div>
        <div class="col-md-2">
          <label class="form-label">&nbsp;</label>
          <button type="button" onclick="applyCustomDuration()" class="btn btn-primary w-100">Apply</button>
        </div>
      </div>
    </div>

    <!-- Current Selection Display -->
    @php
    $currentType = request('type', 'standard');
    $currentDuration = request('duration', '2D1N');
    $searchName = $currentType == 'standard' ? 'Standard ' : 'Full Board ';
    // Try to find package by combined name first (e.g., "Standard 2D1N")
    $currentPackage = $packages->where('name', $searchName . $currentDuration)->first();
    // Fallback to separate name and duration check
    if (!$currentPackage) {
        $currentPackage = $packages->where('name', $currentType == 'standard' ? 'Standard' : 'Full Board')->where('duration', $currentDuration)->first();
    }
    @endphp

    <!-- Standard Package Content -->
    <div id="content-standard" @if(request('type', 'standard') == 'standard') style="display:block;" @else style="display:none;" @endif>
      <div class="mb-4">
        <h6 class="text-muted">Standard Package - {{ $currentDuration }}</h6>
        <p class="text-muted small">Per charter pricing (entire boat)</p>
      </div>

      <div class="package-card @if($currentPackage && request('type', 'standard') == 'standard') exists @endif">
        @if($currentPackage && request('type', 'standard') == 'standard')
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h5 class="mb-2">Standard {{ $currentDuration }}</h5>
              <p class="text-success mb-2"><strong>Currently Active</strong></p>
              <p class="mb-1">Price: <span class="fw-bold">RM {{ number_format($currentPackage->price_standard, 0) }}</span></p>
              <p class="text-muted small mb-0">Created: {{ $currentPackage->created_at->format('M d, Y') }}</p>
            </div>
            <div>
              <button class="btn btn-sm btn-outline-primary me-2" onclick="showEditModal()">Edit Price</button>
              <form method="POST" action="{{ route('admin.packages.destroy', $currentPackage->id) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this package?')">Remove</button>
              </form>
            </div>
          </div>
        @else
          <form method="POST" action="{{ route('admin.packages.store') }}">
            @csrf
            <input type="hidden" name="name" value="Standard">
            <input type="hidden" name="duration" value="{{ $currentDuration }}">
            <input type="hidden" name="price_fullboard_adult" value="0">
            <input type="hidden" name="price_fullboard_child" value="0">
            <input type="hidden" name="is_active" value="1">

            <h5 class="mb-3">Add Standard {{ $currentDuration }} Package</h5>
            <div class="row align-items-center">
              <div class="col-md-6">
                <label class="form-label">Price (RM) *</label>
                <input type="number" name="price_standard" class="form-control" required min="0" step="0.01">
              </div>
              <div class="col-md-6">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control" placeholder="Optional">
              </div>
              <div class="col-12 mt-3">
                <button type="submit" class="btn btn-success">+ Add Package</button>
              </div>
            </div>
          </form>
        @endif
      </div>
    </div>

    <!-- Fullboard Package Content -->
    <div id="content-fullboard" @if(request('type') == 'fullboard') style="display:block;" @else style="display:none;" @endif>
      <div class="mb-4">
        <h6 class="text-muted">Full Board Package - {{ $currentDuration }}</h6>
        <p class="text-muted small">Per person pricing (includes meals)</p>
      </div>

      <div class="package-card @if($currentPackage && request('type') == 'fullboard') exists @endif">
        @if($currentPackage && request('type') == 'fullboard')
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h5 class="mb-2">Full Board {{ $currentDuration }}</h5>
              <p class="text-success mb-2"><strong>Currently Active</strong></p>
              <p class="mb-1">Adult: <span class="fw-bold">RM {{ number_format($currentPackage->price_fullboard_adult, 0) }}</span></p>
              <p class="mb-1">Child: <span class="fw-bold">RM {{ number_format($currentPackage->price_fullboard_child, 0) }}</span></p>
              <p class="text-muted small mb-0">Created: {{ $currentPackage->created_at->format('M d, Y') }}</p>
            </div>
            <div>
              <button class="btn btn-sm btn-outline-primary me-2" onclick="showEditModal()">Edit Price</button>
              <form method="POST" action="{{ route('admin.packages.destroy', $currentPackage->id) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this package?')">Remove</button>
              </form>
            </div>
          </div>
        @else
          <form method="POST" action="{{ route('admin.packages.store') }}">
            @csrf
            <input type="hidden" name="name" value="Full Board">
            <input type="hidden" name="duration" value="{{ $currentDuration }}">
            <input type="hidden" name="price_standard" value="0">
            <input type="hidden" name="is_active" value="1">

            <h5 class="mb-3">Add Full Board {{ $currentDuration }} Package</h5>
            <div class="row align-items-center">
              <div class="col-md-4">
                <label class="form-label">Adult Price (RM) *</label>
                <input type="number" name="price_fullboard_adult" class="form-control" required min="0" step="0.01">
              </div>
              <div class="col-md-4">
                <label class="form-label">Child Price (RM) *</label>
                <input type="number" name="price_fullboard_child" class="form-control" required min="0" step="0.01">
              </div>
              <div class="col-md-4">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control" placeholder="Optional">
              </div>
              <div class="col-12 mt-3">
                <button type="submit" class="btn btn-success">+ Add Package</button>
              </div>
            </div>
          </form>
        @endif
      </div>
    </div>

  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function switchTab(type) {
  const url = new URL(window.location);
  url.searchParams.set('type', type);
  url.searchParams.set('duration', url.searchParams.get('duration') || '2D1N');
  window.location.href = url.toString();
}

function switchDuration(duration) {
  const url = new URL(window.location);
  url.searchParams.set('duration', duration);
  url.searchParams.set('type', url.searchParams.get('type') || 'standard');
  window.location.href = url.toString();
}

function showCustomDuration() {
  document.getElementById('customDurationInput').style.display = 'block';
  document.getElementById('customDurationValue').focus();

  // Update active state
  document.querySelectorAll('.duration-btn').forEach(btn => btn.classList.remove('active'));
  document.getElementById('dur-custom').classList.add('active');
}

function applyCustomDuration() {
  const value = document.getElementById('customDurationValue').value.trim();
  if (!value) {
    alert('Please enter a duration');
    return;
  }

  const url = new URL(window.location);
  url.searchParams.set('duration', value);
  url.searchParams.set('type', url.searchParams.get('type') || 'standard');
  window.location.href = url.toString();
}

// Show custom input if URL has custom duration
document.addEventListener('DOMContentLoaded', function() {
  const urlParams = new URLSearchParams(window.location.search);
  const duration = urlParams.get('duration');

  // Check if duration is not one of the presets
  const presets = ['2D1N', '3D2N', '4D3N', '5D4N'];
  if (duration && !presets.includes(duration)) {
    document.getElementById('customDurationInput').style.display = 'block';
    document.getElementById('customDurationValue').value = duration;

    document.querySelectorAll('.duration-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('dur-custom').classList.add('active');
  }
});
</script>
</body>
</html>
