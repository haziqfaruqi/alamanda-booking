<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coupons | Alamanda Houseboat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --main-bg: #f4f7fa;
            --accent: #4e73df;
            --text-main: #2d3748;
            --text-muted: #718096;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.05);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--main-bg);
            color: var(--text-main);
            margin: 0;
        }

        .main-content {
            margin-left: 260px;
            padding: 2.5rem;
            min-height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 2rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            padding: 6px 14px;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
        }

        .avatar-small {
            width: 32px;
            height: 32px;
            background: var(--accent);
            color: #fff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            background: #fff;
        }

        /* Coupon Code Styling */
        .coupon-code {
            font-family: 'Courier New', monospace;
            font-weight: 800;
            color: var(--accent);
            background: #f0f3ff;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px dashed var(--accent);
        }

        /* Table Style */
        .table thead th {
            background-color: #f8f9fc;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            border: none;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            font-size: 0.85rem;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Badges */
        .badge-sleek {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }
        .status-active { background: #e6fffa; color: #047481; }
        .status-inactive { background: #fff5f5; color: #9b2c2c; }
        .type-percentage { background: #ebf8ff; color: #2c5282; }
        .type-fixed { background: #fffaf0; color: #9c4221; }

        .btn-add {
            background-color: var(--accent);
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .action-btn {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>

<body>

@include('layouts.admin-sidebar', ['activePage' => 'coupons'])

<main class="main-content">
    <div class="top-bar">
        <div class="user-profile">
            <div class="text-end">
                <div style="font-size: 0.8rem; font-weight: 700;">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div style="font-size: 0.65rem; color: var(--text-muted);">Administrator</div>
            </div>
            <div class="avatar-small">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="font-size: 1.25rem;">Discount Coupons</h4>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#createCouponModal">
            <i class="fa-solid fa-plus me-2"></i>Create Coupon
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px; font-size: 0.85rem;">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card p-2">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Coupon Info</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Validity Period</th>
                        <th>Usage</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons ?? [] as $coupon)
                    <tr>
                        <td class="ps-4">
                            <span class="coupon-code">{{ $coupon->code }}</span>
                            @if($coupon->description)
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">{{ $coupon->description }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge-sleek {{ $coupon->type === 'percentage' ? 'type-percentage' : 'type-fixed' }}">
                                {{ $coupon->type }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold">
                                {{ $coupon->type === 'percentage' ? $coupon->value.'%' : 'RM '.number_format($coupon->value, 2) }}
                            </div>
                            @if($coupon->min_guests > 1)
                            <small class="text-muted">Min {{ $coupon->min_guests }} guests</small>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 0.8rem;">
                                <div class="fw-600">{{ \Carbon\Carbon::parse($coupon->valid_from)->format('d M Y') }}</div>
                                <div class="text-muted">Until {{ \Carbon\Carbon::parse($coupon->valid_until)->format('d M Y') }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-primary">{{ $coupon->used_count }}</span>
                            <span class="text-muted">/ {{ $coupon->max_uses ?? '∞' }}</span>
                        </td>
                        <td>
                            @php
                                $isActive = $coupon->is_active && now()->between($coupon->valid_from, $coupon->valid_until) && (!$coupon->max_uses || $coupon->used_count < $coupon->max_uses);
                            @endphp
                            <span class="badge-sleek {{ $isActive ? 'status-active' : 'status-inactive' }}">
                                {{ $isActive ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary action-btn" onclick="editCoupon({{ $coupon->id }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="is_active" value="{{ $coupon->is_active ? '0' : '1' }}">
                                    <button type="submit" class="btn btn-sm action-btn {{ $coupon->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                        {{ $coupon->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}" onsubmit="return confirm('Delete this coupon?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger action-btn">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">No coupons found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="createCouponModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Create New Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.coupons.store') }}">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted uppercase">Coupon Code</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. RAYA2026" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Type</label>
                            <select name="type" class="form-select" id="createType">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed (RM)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Value</label>
                            <input type="number" name="value" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Valid From</label>
                        <input type="datetime-local" name="valid_from" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Valid Until</label>
                        <input type="datetime-local" name="valid_until" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">Create Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCouponModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Edit Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editCouponForm" action="">
                @csrf @method('PUT')
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Coupon Code</label>
                        <input type="text" id="editCode" class="form-control bg-light" readonly>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Type</label>
                            <select id="editType" name="type" class="form-select">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed (RM)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Value</label>
                            <input type="number" id="editValue" name="value" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Valid From</label>
                        <input type="datetime-local" id="editValidFrom" name="valid_from" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Valid Until</label>
                        <input type="datetime-local" id="editValidUntil" name="valid_until" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">Update Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

async function editCoupon(id) {
    try {
        const response = await fetch(`/admin/coupons/${id}/edit`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        const coupon = await response.json();

        document.getElementById('editCouponForm').action = `/admin/coupons/${id}/update`;
        document.getElementById('editCode').value = coupon.code;
        document.getElementById('editType').value = coupon.type;
        document.getElementById('editValue').value = coupon.value;

        const formatDT = (dateStr) => {
            const d = new Date(dateStr);
            return d.toISOString().slice(0, 16);
        };

        document.getElementById('editValidFrom').value = formatDT(coupon.valid_from);
        document.getElementById('editValidUntil').value = formatDT(coupon.valid_until);

        new bootstrap.Modal(document.getElementById('editCouponModal')).show();
    } catch (e) { alert('Error loading coupon.'); }
}
</script>
</body>
</html>