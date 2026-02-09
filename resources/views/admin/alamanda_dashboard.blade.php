<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alamanda | Premium Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fa;
            color: #2d3748;
            margin: 0;
        }

        .main-content {
            margin-left: 260px; /* Ruang untuk sidebar */
            padding: 2rem 2.5rem;
        }

        /* Topbar Clean */
        .topbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 2rem;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            padding: 6px 14px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        /* KPI Card Sleek */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            background: #fff;
        }

        .kpi-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .kpi-value {
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 0;
        }

        .trend { font-size: 0.75rem; font-weight: 700; }

        /* Chart & Activity Section */
        .section-header { font-size: 0.95rem; font-weight: 700; margin-bottom: 1.5rem; }
        
        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .act-title { font-size: 0.8rem; font-weight: 700; display: block; }
        .act-meta { font-size: 0.75rem; color: #718096; }
        .act-time { font-size: 0.7rem; color: #4e73df; font-weight: 600; margin-top: 3px; display: block; }
    </style>
</head>
<body>

    @include('layouts.admin-sidebar', ['activePage' => 'dashboard'])

    <main class="main-content">
        
        <div class="topbar">
            <div class="admin-profile">
                <div class="text-end">
                    <div style="font-size: 0.8rem; font-weight: 700;">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div style="font-size: 0.65rem; color: #718096;">Administrator</div>
                </div>
                <div style="width: 32px; height: 32px; background: #4e73df; color: #fff; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <span class="kpi-label">Revenue</span>
                    <div class="d-flex justify-content-between align-items-end">
                        <h3 class="kpi-value">RM {{ number_format($totalRevenue ?? 0, 0) }}</h3>
                        <span class="trend text-success">{{ number_format($revenueGrowth ?? 0, 1) }}%</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <span class="kpi-label">Bookings</span>
                    <div class="d-flex justify-content-between align-items-end">
                        <h3 class="kpi-value">{{ $totalBookings ?? 0 }}</h3>
                        <span class="trend text-success">{{ number_format($bookingsGrowth ?? 0, 1) }}%</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <span class="kpi-label">Customers</span>
                    <div class="d-flex justify-content-between align-items-end">
                        <h3 class="kpi-value">{{ $totalCustomers ?? 0 }}</h3>
                        <span class="trend text-muted">0%</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <span class="kpi-label">Reviews</span>
                    <div class="d-flex justify-content-between align-items-end">
                        <h3 class="kpi-value">{{ $totalReviews ?? 0 }}</h3>
                        <span class="trend text-success">100%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="section-header mb-0">Revenue Analytics</h5>
                        <select class="form-select form-select-sm w-auto" id="chartPeriod" style="font-size: 11px; border-radius: 6px;">
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4">
                    <h5 class="section-header mb-3">Recent Activity</h5>
                    <div class="activity-list">
                        @forelse($recentBookings ?? [] as $booking)
                        <div class="activity-item">
                            <span class="act-title">Booking {{ ucfirst($booking->status) }}</span>
                            <span class="act-meta">{{ $booking->contact_name }} • {{ $booking->package->name ?? 'Houseboat' }}</span>
                            <span class="act-time">{{ $booking->created_at->diffForHumans() }}</span>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted small">No recent activity</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        // Logik Chart Kekal Menggunakan Data Database
        const chartDataByYear = {
            '2025': { labels: @js($chartData2025['labels'] ?? []), revenue: @js($chartData2025['revenue'] ?? []) },
            '2026': { labels: @js($chartData2026['labels'] ?? []), revenue: @js($chartData2026['revenue'] ?? []) }
        };

        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartDataByYear['2026'].labels,
                datasets: [{
                    label: 'Revenue',
                    data: chartDataByYear['2026'].revenue,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });

        document.getElementById('chartPeriod').addEventListener('change', function() {
            const year = this.value;
            revenueChart.data.labels = chartDataByYear[year].labels;
            revenueChart.data.datasets[0].data = chartDataByYear[year].revenue;
            revenueChart.update();
        });
    </script>
</body>
</html>