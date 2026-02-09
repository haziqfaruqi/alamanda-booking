<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookings | Alamanda Houseboat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

        /* Top Bar */
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

        /* Card & Filter */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            background: #fff;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-select, .btn-filter {
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 10px;
        }

        .btn-filter {
            font-weight: 600;
            padding: 10px 20px;
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

        /* Badges Sleek */
        .badge-sleek {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .bg-success-soft { background: #e6fffa; color: #047481; }
        .bg-warning-soft { background: #fffaf0; color: #9c4221; }
        .bg-danger-soft { background: #fff5f5; color: #9b2c2c; }
        .bg-info-soft { background: #ebf8ff; color: #2c5282; }

        .action-btn {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none !important;
        }
    </style>
</head>

<body>

@include('layouts.admin-sidebar', ['activePage' => 'bookings'])

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

    <h4 class="fw-bold mb-4" style="font-size: 1.25rem;">Booking Management</h4>

    <div class="card p-4">
        
        <div class="row g-3 align-items-end mb-4">
            <div class="col-md-3">
                <label class="form-label">Month</label>
                <select class="form-select border-light-subtle" id="filterMonth">
                    <option value="">All Months</option>
                    @foreach([
                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 
                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                    ] as $num => $name)
                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Year</label>
                <select class="form-select border-light-subtle" id="filterYear">
                    @for($y = date('Y'); $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="button" class="btn btn-primary btn-filter flex-grow-1" onclick="applyFilter()">
                    <i class="fa-solid fa-filter me-2"></i> Filter
                </button>
                <button type="button" class="btn btn-outline-secondary btn-filter flex-grow-1" onclick="resetFilter()">
                    <i class="fa-solid fa-rotate-left me-2"></i> Reset
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th class="ps-4">Contact</th>
                        <th>Package Details</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Booking Date</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings ?? [] as $booking)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold" style="font-size: 0.9rem;">{{ $booking->contact_name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $booking->contact_email }}</div>
                        </td>
                        <td>
                            <div class="fw-600">{{ $booking->package->name ?? 'N/A' }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                <i class="fa-regular fa-calendar-days me-1"></i>
                                {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = match($booking->status) {
                                    'confirmed' => 'bg-success-soft',
                                    'cancelled' => 'bg-danger-soft',
                                    'completed' => 'bg-info-soft',
                                    default => 'bg-warning-soft'
                                };
                            @endphp
                            <span class="badge-sleek {{ $statusClass }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-sleek {{ $booking->payment_status === 'paid' ? 'bg-success-soft' : 'bg-warning-soft' }}">
                                <i class="fa-solid fa-circle {{ $booking->payment_status === 'paid' ? 'text-success' : 'text-warning' }} me-1" style="font-size: 6px;"></i>
                                {{ $booking->payment_status }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $booking->created_at->format('d M Y') }}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                @if($booking->payment_status === 'paid' && $booking->receipt_path)
                                    <a href="{{ route('receipt.view', $booking->id) }}" target="_blank" class="btn btn-sm btn-outline-success action-btn" title="Receipt">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary action-btn">
                                    Details
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-calendar-xmark d-block mb-2" style="font-size: 2rem; opacity: 0.2;"></i>
                            No bookings found for the selected period.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    function applyFilter() {
        const month = document.getElementById('filterMonth').value;
        const year = document.getElementById('filterYear').value;
        
        let url = new URL(window.location.href);
        
        if (month) {
            url.searchParams.set('month', month);
        } else {
            url.searchParams.delete('month');
        }
        
        url.searchParams.set('year', year);
        window.location.href = url.toString();
    }

    function resetFilter() {
        window.location.href = window.location.pathname;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>