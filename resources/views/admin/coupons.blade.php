<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coupons | Alamanda Houseboat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

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
    .badge-active{
      background:#dcfce7;
      color:#166534;
      font-weight:600;
    }
    .badge-inactive{
      background:#fee2e2;
      color:#991b1b;
      font-weight:600;
    }
    .badge-percentage{
      background:#dbeafe;
      color:#1e40af;
      font-weight:600;
    }
    .badge-fixed{
      background:#fef3c7;
      color:#92400e;
      font-weight:600;
    }

    /* ===== COUPON CODE ===== */
    .coupon-code{
      font-family:'Courier New',monospace;
      font-weight:700;
      letter-spacing:1px;
      background:#f3f4f6;
      padding:6px 12px;
      border-radius:8px;
    }

    /* ===== MODAL ===== */
    .modal-content{
      border-radius:18px;
      border:none;
    }
    .modal-header{
      border-bottom:1px solid #e5e7eb;
      padding:20px 24px;
    }
    .modal-body{
      padding:24px;
    }
    </style>
</head>

<body>

<!-- SIDEBAR -->
@include('layouts.admin-sidebar', ['activePage' => 'coupons'])

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

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Discount Coupons</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCouponModal">
      <i class="bi bi-plus-lg me-2"></i>Create Coupon
    </button>
  </div>

  <!-- COUPONS CARD -->
  <div class="card p-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- TABLE -->
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Value</th>
            <th>Validity</th>
            <th>Usage</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          @forelse($coupons ?? [] as $coupon)
          <tr>
            <td>
              <span class="coupon-code">{{ $coupon->code }}</span>
              @if($coupon->description)
              <br><small class="text-muted">{{ $coupon->description }}</small>
              @endif
            </td>
            <td>
              <span class="badge badge-{{ $coupon->type === 'percentage' ? 'percentage' : 'fixed' }}">
                {{ ucfirst($coupon->type) }}
              </span>
            </td>
            <td>
              @if($coupon->type === 'percentage')
                <strong>{{ $coupon->value }}%</strong>
              @else
                <strong>RM {{ number_format($coupon->value, 2) }}</strong>
              @endif
              @if($coupon->min_guests > 1)
              <br><small class="text-muted">Min {{ $coupon->min_guests }} guests</small>
              @endif
            </td>
            <td>
              <small class="d-block">{{ \Carbon\Carbon::parse($coupon->valid_from)->format('d M Y') }}</small>
              <small class="text-muted">to {{ \Carbon\Carbon::parse($coupon->valid_until)->format('d M Y') }}</small>
            </td>
            <td>
              <span class="fw-semibold">{{ $coupon->used_count }}</span>
              @if($coupon->max_uses)
              <small class="text-muted"> / {{ $coupon->max_uses }}</small>
              @else
              <small class="text-muted"> / Unlimited</small>
              @endif
            </td>
            <td>
              @if($coupon->is_active && now()->between($coupon->valid_from, $coupon->valid_until) && (!$coupon->max_uses || $coupon->used_count < $coupon->max_uses))
                <span class="badge badge-active">Active</span>
              @else
                <span class="badge badge-inactive">Inactive</span>
              @endif
            </td>
            <td>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editCoupon({{ $coupon->id }})">
                  <i class="bi bi-pencil"></i>
                </button>
                <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="is_active" value="{{ $coupon->is_active ? '0' : '1' }}">
                  <button type="submit" class="btn btn-sm {{ $coupon->is_active ? 'btn-warning' : 'btn-success' }}">
                    {{ $coupon->is_active ? 'Disable' : 'Enable' }}
                  </button>
                </form>
                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No coupons found. Create your first coupon!</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

</main>

<!-- CREATE COUPON MODAL -->
<div class="modal fade" id="createCouponModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Create New Coupon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf
        <div class="modal-body">
          <!-- Code -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Coupon Code</label>
            <input type="text" name="code" class="form-control" placeholder="e.g., RAYA2025" required>
            <small class="text-muted">Spaces will be removed automatically. Code will be uppercase.</small>
          </div>

          <!-- Type -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Discount Type</label>
            <select name="type" class="form-select" required>
              <option value="percentage">Percentage (%)</option>
              <option value="fixed">Fixed Amount (RM)</option>
            </select>
          </div>

          <!-- Value -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Discount Value</label>
            <input type="number" name="value" class="form-control" step="0.01" min="0" placeholder="e.g., 20" required>
            <small class="text-muted" id="valueHelp">Enter percentage amount (e.g., 20 for 20%)</small>
          </div>

          <!-- Valid From -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Valid From</label>
            <input type="datetime-local" name="valid_from" class="form-control" required>
          </div>

          <!-- Valid Until -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Valid Until</label>
            <input type="datetime-local" name="valid_until" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Create Coupon</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EDIT COUPON MODAL -->
<div class="modal fade" id="editCouponModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Coupon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="editCouponForm" action="">
        @csrf
        <input type="hidden" id="editCouponId" name="coupon_id" value="">
        <div class="modal-body">
          <!-- Code (Read-only) -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Coupon Code</label>
            <input type="text" id="editCode" class="form-control" readonly>
            <small class="text-muted">Code cannot be changed.</small>
          </div>

          <!-- Type -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Discount Type</label>
            <select id="editType" name="type" class="form-select" required>
              <option value="percentage">Percentage (%)</option>
              <option value="fixed">Fixed Amount (RM)</option>
            </select>
          </div>

          <!-- Value -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Discount Value</label>
            <input type="number" id="editValue" name="value" class="form-control" step="0.01" min="0" required>
          </div>

          <!-- Valid From -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Valid From</label>
            <input type="datetime-local" id="editValidFrom" name="valid_from" class="form-control" required>
          </div>

          <!-- Valid Until -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Valid Until</label>
            <input type="datetime-local" id="editValidUntil" name="valid_until" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Coupon</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// Update help text based on discount type
document.querySelector('select[name="type"]').addEventListener('change', function() {
  const helpText = document.getElementById('valueHelp');
  if (this.value === 'percentage') {
    helpText.textContent = 'Enter percentage amount (e.g., 20 for 20%)';
  } else {
    helpText.textContent = 'Enter fixed amount in RM (e.g., 100 for RM100 off)';
  }
});

// Edit coupon function
async function editCoupon(id) {
  try {
    const response = await fetch(`/admin/coupons/${id}/edit`, {
      headers: {
        'X-CSRF-TOKEN': csrfToken || '',
        'Accept': 'application/json'
      }
    });

    if (!response.ok) {
      const errorText = await response.text();
      console.error('Response error:', response.status, errorText);
      throw new Error('Failed to fetch coupon');
    }

    const coupon = await response.json();
    console.log('Coupon data:', coupon);

    // Set form action URL
    document.getElementById('editCouponForm').action = `/admin/coupons/${id}/update`;

    // Populate modal with coupon data
    document.getElementById('editCouponId').value = coupon.id;
    document.getElementById('editCode').value = coupon.code;
    document.getElementById('editType').value = coupon.type;
    document.getElementById('editValue').value = coupon.value;

    // Format dates for datetime-local input
    const validFrom = new Date(coupon.valid_from);
    const validUntil = new Date(coupon.valid_until);

    // Format to YYYY-MM-DDTHH:mm
    const formatDateTime = (date) => {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');
      return `${year}-${month}-${day}T${hours}:${minutes}`;
    };

    document.getElementById('editValidFrom').value = formatDateTime(validFrom);
    document.getElementById('editValidUntil').value = formatDateTime(validUntil);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editCouponModal'));
    modal.show();

  } catch (error) {
    console.error('Error:', error);
    alert('Failed to load coupon details. Please try again.');
  }
}
</script>
</body>
</html>
