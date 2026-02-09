<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Details | Alamanda Houseboat</title>
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

        /* Card & Section */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            background: #fff;
            margin-bottom: 1.5rem;
        }

        .section-header {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .label-text {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .value-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-main);
        }

        /* Badge Style */
        .badge-sleek {
            padding: 6px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .status-pending { background: #fffaf0; color: #9c4221; }
        .status-confirmed { background: #ebf8ff; color: #2c5282; }
        .status-completed { background: #e6fffa; color: #047481; }
        .status-cancelled { background: #fff5f5; color: #9b2c2c; }

        /* Table Style */
        .table thead th {
            background-color: #f8f9fc;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--text-muted);
            padding: 12px;
            border: none;
        }

        .btn-back {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-back:hover { color: var(--accent); }
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.bookings') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left me-2"></i> Back to Bookings
            </a>
            <h4 class="fw-bold mt-2" style="font-size: 1.25rem;">Booking Details #{{ $booking->id }}</h4>
        </div>
        <span class="badge-sleek status-{{ $booking->status }}">
            <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> {{ $booking->status }}
        </span>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px; font-size: 0.85rem;">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card p-4">
                <h6 class="section-header"><i class="fa-solid fa-file-invoice text-primary"></i> Booking Summary</h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="label-text">Package Plan</label>
                        <div class="value-text text-primary">{{ $booking->package->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="label-text">Check-In</label>
                        <div class="value-text">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="label-text">Check-Out</label>
                        <div class="value-text">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <h6 class="section-header"><i class="fa-solid fa-users text-primary"></i> Guest List ({{ $booking->total_guests }} Pax)</h6>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
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
                                <td class="text-muted">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $guest->guest_name }}</td>
                                <td>{{ $guest->guest_ic ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark fw-bold" style="font-size: 0.7rem;">{{ strtoupper($guest->guest_type) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">No guest details recorded</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4">
                <h6 class="section-header"><i class="fa-solid fa-address-card text-primary"></i> Customer Information</h6>
                <div class="mb-3">
                    <label class="label-text">Full Name</label>
                    <div class="value-text">{{ $booking->contact_name }}</div>
                </div>
                <div class="mb-3">
                    <label class="label-text">Email Address</label>
                    <div class="value-text" style="font-size: 0.85rem;">{{ $booking->contact_email }}</div>
                </div>
                <div class="mb-0">
                    <label class="label-text">Phone Number</label>
                    <div class="value-text">{{ $booking->contact_phone ?? '-' }}</div>
                </div>
            </div>

            <div class="card p-4">
                <h6 class="section-header"><i class="fa-solid fa-credit-card text-primary"></i> Payment Details</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small fw-bold">Status</span>
                    <span class="fw-bold {{ $booking->payment_status === 'paid' ? 'text-success' : 'text-warning' }}">{{ strtoupper($booking->payment_status) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small fw-bold">Total Guests</span>
                    <span class="fw-bold">{{ $booking->total_guests }} Pax</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Grand Total</span>
                    <span class="fw-bold text-primary fs-5">RM {{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>

            <div class="card p-4 bg-light border-0">
                <h6 class="section-header"><i class="fa-solid fa-arrows-rotate text-primary"></i> Update Status</h6>
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <select name="status" class="form-select border-0 shadow-sm" style="font-size: 0.85rem;">
                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold btn-sm py-2">Update Booking</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>