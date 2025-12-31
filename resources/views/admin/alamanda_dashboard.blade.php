<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Alamanda Houseboat | Staff Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
  font-family:'Inter',sans-serif;
  background:#f4f6fb;
}

/* Sidebar */
.sidebar{
  width:260px;
  background:#fff;
  min-height:100vh;
  border-right:1px solid #e5e7eb;
}

/* Topbar */
.search-box{
  max-width:320px;
}

.card{
  border:none;
  border-radius:18px;
  box-shadow:0 8px 24px rgba(0,0,0,.05);
}

.kpi small{
  font-weight:600;
}

.avatar{
  width:42px;
  height:42px;
  border-radius:50%;
  background:#4f46e5;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:600;
}

/* Activity */
.activity-list{
  max-height:320px;
  overflow-y:auto;
  padding-right:8px;
}

.activity-list::-webkit-scrollbar{
  width:6px;
}

.activity-list::-webkit-scrollbar-track{
  background:#f1f1f1;
  border-radius:3px;
}

.activity-list::-webkit-scrollbar-thumb{
  background:#d1d5db;
  border-radius:3px;
}

.activity-list::-webkit-scrollbar-thumb:hover{
  background:#9ca3af;
}

.activity-item{
  border-bottom:1px solid #eee;
  padding:14px 0;
}

.activity-item:last-child{
  border-bottom:none;
}

/* Chart Container */
.chart-container{
  position:relative;
  height:250px;
  width:100%;
}
</style>
</head>

<body>

<div class="container-fluid">
  <div class="row">

    <!-- SIDEBAR -->
    @include('layouts.admin-sidebar', ['activePage' => 'dashboard'])

    <!-- MAIN -->
    <main class="col p-4">

      <!-- TOPBAR -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <input class="form-control search-box" placeholder="Search...">

        <div class="d-flex align-items-center gap-3">
          <div class="text-end">
            <div class="fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</div>
            <small class="text-muted">Administrator</small>
          </div>
          <div class="avatar">{{ substr(auth()->user()->name, 0, 1) ?? 'A' }}</div>
        </div>
      </div>

      <!-- KPI -->
      <div class="row g-4 mb-4">

        <div class="col-md-3">
          <div class="card p-4 kpi">
            <h6 class="text-muted">Total Revenue</h6>
            <h3 class="fw-bold">RM {{ number_format($totalRevenue ?? 0, 0) }}</h3>
            <small class="{{ $revenueGrowth >= 0 ? 'text-success' : 'text-danger' }}">
              {{ $revenueGrowth >= 0 ? '▲' : '▼' }} {{ number_format(abs($revenueGrowth), 2) }}%
            </small>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card p-4 kpi">
            <h6 class="text-muted">Total Bookings</h6>
            <h3 class="fw-bold">{{ $totalBookings ?? 0 }}</h3>
            <small class="{{ $bookingsGrowth >= 0 ? 'text-success' : 'text-danger' }}">
              {{ $bookingsGrowth >= 0 ? '▲' : '▼' }} {{ number_format(abs($bookingsGrowth), 2) }}%
            </small>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card p-4 kpi">
            <h6 class="text-muted">Customers</h6>
            <h3 class="fw-bold">{{ $totalCustomers ?? 0 }}</h3>
            <small class="{{ $customersGrowth >= 0 ? 'text-success' : 'text-danger' }}">
              {{ $customersGrowth >= 0 ? '▲' : '▼' }} {{ number_format(abs($customersGrowth), 2) }}%
            </small>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card p-4 kpi">
            <h6 class="text-muted">Reviews</h6>
            <h3 class="fw-bold">{{ $totalReviews ?? 0 }}</h3>
            <small class="{{ $reviewsGrowth >= 0 ? 'text-success' : 'text-danger' }}">
              {{ $reviewsGrowth >= 0 ? '▲' : '▼' }} {{ number_format(abs($reviewsGrowth), 2) }}%
            </small>
          </div>
        </div>

      </div>

      <!-- CONTENT -->
      <div class="row g-4">

        <!-- REVENUE CHART -->
        <div class="col-lg-8">
          <div class="card p-4">
            <div class="d-flex justify-content-between mb-3">
              <h5 class="fw-bold">Revenue Overview</h5>
              <select class="form-select w-auto" id="chartPeriod">
                <option value="2025">2025</option>
                <option value="2026" selected>2026</option>
              </select>
            </div>

            <p class="text-muted mb-3">
              Revenue: <strong>RM {{ number_format($totalRevenue ?? 0, 0) }}</strong> · Bookings: <strong>{{ $totalBookings ?? 0 }}</strong>
            </p>

            <div class="chart-container">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>
        </div>

        <!-- ACTIVITY -->
        <div class="col-lg-4">
          <div class="card p-4">
            <h5 class="fw-bold mb-3">Recent Activity</h5>

            <div class="activity-list">
            @forelse($recentBookings ?? [] as $booking)
            <div class="activity-item">
              <div class="fw-semibold">Booking {{ ucfirst($booking->status) }}</div>
              <small class="text-muted">{{ $booking->contact_name }} · {{ $booking->package->name ?? 'Package' }}</small>
              <div class="text-muted small">{{ $booking->created_at->diffForHumans() }}</div>
            </div>
            @empty
            <div class="text-muted text-center py-3">No recent activity</div>
            @endforelse

            @foreach($recentUsers ?? [] as $user)
            <div class="activity-item">
              <div class="fw-semibold">New Customer</div>
              <small class="text-muted">{{ $user->name }}</small>
              <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
            </div>

          </div>
        </div>

      </div>

    </main>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Chart data from server - organized by year
const chartDataByYear = {
  '2025': {
    labels: @js($chartData2025['labels'] ?? []),
    revenue: @js($chartData2025['revenue'] ?? []),
    bookings: @js($chartData2025['bookings'] ?? [])
  },
  '2026': {
    labels: @js($chartData2026['labels'] ?? []),
    revenue: @js($chartData2026['revenue'] ?? []),
    bookings: @js($chartData2026['bookings'] ?? [])
  }
};

// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
let currentYear = '2026';

const revenueChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: chartDataByYear[currentYear].labels,
    datasets: [
      {
        label: 'Revenue (RM)',
        data: chartDataByYear[currentYear].revenue,
        borderColor: '#4f46e5',
        backgroundColor: 'rgba(79, 70, 229, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        yAxisID: 'y'
      },
      {
        label: 'Bookings',
        data: chartDataByYear[currentYear].bookings,
        borderColor: '#ff8c32',
        backgroundColor: 'rgba(255, 140, 50, 0.1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4,
        yAxisID: 'y1'
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
      mode: 'index',
      intersect: false
    },
    plugins: {
      legend: {
        display: true,
        position: 'top'
      },
      tooltip: {
        backgroundColor: 'rgba(0,0,0,0.8)',
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 8
      }
    },
    scales: {
      x: {
        grid: {
          display: false
        }
      },
      y: {
        type: 'linear',
        display: true,
        position: 'left',
        title: {
          display: true,
          text: 'Revenue (RM)'
        },
        grid: {
          color: 'rgba(0,0,0,0.05)'
        }
      },
      y1: {
        type: 'linear',
        display: true,
        position: 'right',
        title: {
          display: true,
          text: 'Bookings'
        },
        grid: {
          drawOnChartArea: false
        }
      }
    }
  }
});

// Handle year selection change
document.getElementById('chartPeriod').addEventListener('change', function() {
  currentYear = this.value;
  revenueChart.data.labels = chartDataByYear[currentYear].labels;
  revenueChart.data.datasets[0].data = chartDataByYear[currentYear].revenue;
  revenueChart.data.datasets[1].data = chartDataByYear[currentYear].bookings;
  revenueChart.update();
});
</script>
</body>
</html>
